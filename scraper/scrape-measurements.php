<?php

require_once 'bootstrap.php';

use Importers\ImportAllStations;
use Importers\ImportComponents;
use Importers\ImportMeasurements;

$stationsImporter = new ImportAllStations();
$stations = $stationsImporter->doImport();

$componentsImporter = new ImportComponents();
$components = $componentsImporter->doImport();

$measurements = [];

foreach ($stations as $key => $station) {
    if (isset($measurements[$station['sepa_id']]) === false) {
        $measurements[$station['sepa_id']] = [];
    }

    $measurementImporter = new ImportMeasurements($station['sepa_id'], $components);
    $measurements[$station['sepa_id']] = $measurementImporter->doImport();
}
die;