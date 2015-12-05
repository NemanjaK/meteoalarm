<?php

require_once 'bootstrap.php';

use Importers\ImportAllStations;
use Importers\ImportStation;

$stationsImporter = new ImportAllStations();
$stations = $stationsImporter->doImport();

foreach ($stations as $key => $station) {
    $stationImporter = new ImportStation($station['sepa_id']);
    $stationDetails = $stationImporter->doImport();
    $stations[$key] = array_merge($station, $stationDetails);
}

var_dump($stations);
die;