<?php

namespace App\Console\Commands;

use App\Repository\ComponentRepository;
use App\Repository\Entity\Component;
use App\Repository\Entity\Measurement;
use App\Repository\Entity\Station;
use App\Repository\Entity\StationAqiHistoryItem;
use App\Repository\MeasurementRepository;
use App\Repository\StationRepository;
use Illuminate\Console\Command;

class CalculateStationCAQI extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'measurement:caqi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculates CAQI score for each station.';

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Get all stations
        $stationRepository = StationRepository::getInstance();
        $measurementRepository = MeasurementRepository::getInstance();
        $stations = $stationRepository->getAll();
        Component::initializeCoefficients();

        // calculate for last 30 days
        $start = new \DateTime();
        $start->sub(new \DateInterval("P7D"));
        $totalSteps = 7 * 24;
        for ($i = 1; $i <= $totalSteps; $i++) {
            $end = clone $start;
            $end = $end->add(new \DateInterval("PT1H"));
            /** @var Station $station */
            foreach ($stations as $station) {

                if ($station->getType() === Station::TYPE_INDUSTRIAL) {
                    continue;
                }

                $indexes = [];
                $dailyAverages = [];
                $stationCaqi = null;
                echo "[" . date("Y-m-d H:i:s") . "] Calculating AQI for station " . $station->getEoiCode() . " for interval " . $start->format("Y-m-d H:i:s") . " - " . $end->format("Y-m-d H:i:s") . PHP_EOL;
                // get all measurements in the last hour
                $hourlyMeasurements = $measurementRepository->getLatestForStationAndTime($station, $start, $end);
                if (!empty($hourlyMeasurements)) {
                    echo "[" . date("Y-m-d H:i:s") . "] Fetched measurements total " . count($hourlyMeasurements) . " for CAQI." . PHP_EOL;
                    // Calculate CAQI index for each of the components/indices
                    /** @var Measurement $hourlyMeasurement */
                    foreach ($hourlyMeasurements as $hourlyMeasurement) {
                        // Skip CO since we use 8 hour average here.
                        if ($hourlyMeasurement->getComponent()->getSepaId() === Component::COMPONENT_CO) {
                            continue;
                        }
                        echo "[" . date("Y-m-d H:i:s") . "] Calculating CAQI for component " . $hourlyMeasurement->getComponent()->getName() . "." . PHP_EOL;
                        $component = $hourlyMeasurement->getComponent();
                        // Get coefficients
                        $coeff = isset(Component::$coefficients[$station->getType()][$component->getSepaId()]) ? Component::$coefficients[$station->getType()][$component->getSepaId()] : false;

                        // If coeff is not defined than it's not included in CAQI calculation.
                        if (!$coeff) {
                            echo "[" . date("Y-m-d H:i:s") . "] " . $hourlyMeasurement->getComponent()->getName() . " not CAQI component." . PHP_EOL;
                            continue;
                        }

                        $coeffValue = null;
                        if ($component->getSepaId() === Component::COMPONENT_PM2P5 || $component->getSepaId() === Component::COMPONENT_PM10) {
                            $coeff = $coeff['hourly'];
                        }

                        // Find coeff according to the value
                        foreach ($coeff as $limit => $value) {
                            if ($hourlyMeasurement->getValue() < $limit) {
                                $coeffValue = $value;
                                break;
                            }
                        }

                        if ($component->getSepaId() === Component::COMPONENT_CO) {
                            $hourlyMeasurement->setValue($hourlyMeasurement->getValue() * 1000);
                        }

                        if (isset($coeffValue)) {
                            // Calculate CAQI for component
                            $caqi = $coeffValue * $hourlyMeasurement->getValue();
                            $indexes[$hourlyMeasurement->getComponent()->getSepaId()] = round($caqi);
                        } else {
                            $indexes[$hourlyMeasurement->getComponent()->getSepaId()] = 101;
                        }

                        echo "[" . date("Y-m-d H:i:s") . "] CAQI for " . $hourlyMeasurement->getComponent()->getName() . " is " . $indexes[$hourlyMeasurement->getComponent()->getSepaId()] . " calculated for value " . $hourlyMeasurement->getValue() . "." . PHP_EOL;

                        if (!isset($stationCaqi) || $stationCaqi < $indexes[$hourlyMeasurement->getComponent()->getSepaId()]) {
                            $stationCaqi = $indexes[$hourlyMeasurement->getComponent()->getSepaId()];
                        }
                    }
                }

                // Daily / 8 hours average.
                $dailyStart = clone $end;
                $dailyStart->sub(new \DateInterval("P1D"));
                $componentRepository = ComponentRepository::getInstance();
                $pm10Component = $componentRepository->getBySepaId(Component::COMPONENT_PM10);
                $pm10dailyAverage = $measurementRepository->getStationAverageForTimeAndComponent($station, $pm10Component, $dailyStart, $end);
                if (isset($pm10dailyAverage)) {
                    $coefs = Component::$coefficients[$station->getType()][Component::COMPONENT_PM10]['daily'];
                    $coeffValue = null;
                    // Find coeff according to the value
                    foreach ($coefs as $limit => $value) {
                        if ($pm10dailyAverage < $limit) {
                            $coeffValue = $value;
                            break;
                        }
                    }

                    if (isset($coeffValue)) {
                        $dailyAverages[Component::COMPONENT_PM10] = $coeffValue * $pm10dailyAverage;
                    } else {
                        $dailyAverages[Component::COMPONENT_PM10] = 101;
                    }

                    if (!isset($stationCaqi) || $stationCaqi < $dailyAverages[Component::COMPONENT_PM10]) {
                        $stationCaqi = $dailyAverages[Component::COMPONENT_PM10];
                    }
                }

                $pm25Component = $componentRepository->getBySepaId(Component::COMPONENT_PM2P5);
                $pm25dailyAverage = $measurementRepository->getStationAverageForTimeAndComponent($station, $pm25Component, $dailyStart);
                if (isset($pm25dailyAverage)) {
                    $coefs = Component::$coefficients[$station->getType()][Component::COMPONENT_PM2P5]['daily'];
                    $coeffValue = null;
                    // Find coeff according to the value
                    foreach ($coefs as $limit => $value) {
                        if ($pm25dailyAverage < $limit) {
                            $coeffValue = $value;
                            break;
                        }
                    }

                    if (isset($coeffValue)) {
                        $dailyAverages[Component::COMPONENT_PM2P5] = $coeffValue * $pm25dailyAverage;
                    } else {
                        $dailyAverages[Component::COMPONENT_PM2P5] = 101;
                    }

                    if (!isset($stationCaqi) || $stationCaqi < $dailyAverages[Component::COMPONENT_PM2P5]) {
                        $stationCaqi = $dailyAverages[Component::COMPONENT_PM2P5];
                    }
                }

                // CO 8-hour moving average
                $coComponent = $componentRepository->getBySepaId(Component::COMPONENT_CO);
                $avgStart = clone $end;
                $avgStart->sub(new \DateInterval("PT8H"));
                $coAverage = $measurementRepository->getStationAverageForTimeAndComponent($station, $coComponent, $avgStart, $end);
                if (isset($coAverage)) {
                    $coefs = Component::$coefficients[$station->getType()][Component::COMPONENT_CO];
                    $coeffValue = null;
                    // Find coeff according to the value
                    foreach ($coefs as $limit => $value) {
                        if ($coAverage < $limit) {
                            $coeffValue = $value;
                            break;
                        }
                    }

                    if (isset($coeffValue)) {
                        $indexes[Component::COMPONENT_CO] = $coeffValue * $coAverage;
                    } else {
                        $indexes[Component::COMPONENT_CO] = 101;
                    }

                    if (!isset($stationCaqi) || $stationCaqi < $indexes[Component::COMPONENT_CO]) {
                        $stationCaqi = $indexes[Component::COMPONENT_CO];
                    }
                }

                $historyTimestamp = $start->format("Y-m-d H:i:s");
                if (isset($stationCaqi)) {
                    $station->setAqiValue($stationCaqi);
                    $station->setAqiTimestamp($historyTimestamp);
                    $station->save();

                    $historyItem = new StationAqiHistoryItem();
                    $historyItem->setStationId($station->getId());
                    foreach ($indexes as $sepaKey => $value) {
                        $historyItem->setIndex($sepaKey, $value);
                    }
                    foreach ($dailyAverages as $sepaKey => $value) {
                        $historyItem->setIndex($sepaKey, $value, 'daily');
                    }
                    $historyItem->setTimestamp($historyTimestamp);
                    $historyItem->save();
                }
            }
            $start = clone $end;
        }
    }
}
