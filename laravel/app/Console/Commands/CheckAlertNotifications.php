<?php

namespace App\Console\Commands;

use App\Repository\ComponentRepository;
use App\Repository\MeasurementRepository;
use App\Repository\StationRepository;
use Illuminate\Console\Command;

class CheckAlertNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alerts:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates each subscriber permitted values and push notifications into alerts queue if needed.';

    private $stationRepository;
    private $measurementsRepository;
    private $componentRepository;

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->stationRepository = StationRepository::getInstance();
        $this->measurementsRepository = MeasurementRepository::getInstance();
        $this->componentRepository = ComponentRepository::getInstance();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $stations = $this->stationRepository->getAll();
        $components = $this->componentRepository->getAll();
        foreach ($stations as $station) {
            foreach ($components as $component) {
                // Get latest measurements
                $measurement = $this->measurementsRepository->getLatestForStationAndComponent($station, $component);
            }
        }
    }
}
