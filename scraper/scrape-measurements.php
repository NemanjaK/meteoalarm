<?php

require_once 'bootstrap.php';

use Importers\ImportAllStations;
use Importers\ImportComponents;
use Importers\ImportMeasurements;
use App\Repository\Entity\Measurement;
use App\Repository\Exceptions\QueryException;
use GuzzleHttp\Exception\ConnectException;
use App\Repository\StationRepository;
use App\Repository\ComponentRepository;

$stationsImporter = new ImportAllStations();
try {
    $stations = $stationsImporter->doImport();
} catch (ConnectException $e) {
    echo $e->getMessage() . PHP_EOL;
    die;
}

$componentsImporter = new ImportComponents();
try {
    $components = $componentsImporter->doImport();
} catch (ConnectException $e) {
    echo $e->getMessage() . PHP_EOL;
    die;
}

foreach ($stations as $key => $station) {
    if (isset($measurements[$station['sepa_id']]) === false) {
        $measurements[$station['sepa_id']] = [];
    }

    $measurementImporter = new ImportMeasurements($station['sepa_id'], $components);
    try {
        $measurements = $measurementImporter->doImport();
    } catch (ConnectException $e) {
        echo $e->getMessage() . PHP_EOL;
        die;
    }

    $stationRepo = StationRepository::getInstance();
    $componentRepo = ComponentRepository::getInstance();

    $station = $stationRepo->findBySepaId($station['sepa_id']);

    foreach ($measurements as $hourly) {

        $timestamp = $hourly['timestamp'];

        foreach ($hourly['com_values'] as $componentSepaId => $value) {
            if (is_null($value) === false) {
                $component = $componentRepo->findBySepaId($componentSepaId);
                try {
                    $measurement = new Measurement([
                        'station_id' => $station->getId(),
                        'component_id' => $component->getId(),
                        'value' => doubleval($value),
                        'measure_timestamp' => $timestamp,
                        'alert' => 0,
                    ]);
                    $measurement->save();
                } catch (QueryException $e) {
                    echo 'Skpped' . PHP_EOL;
                }

            }
        }


    }
}

echo 'All measurements saved' . PHP_EOL . PHP_EOL;