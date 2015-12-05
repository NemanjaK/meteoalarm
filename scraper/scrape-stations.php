<?php

require_once 'bootstrap.php';

use Importers\ImportAllStations;
use Importers\ImportStation;
use App\Repository\Entity\Station;
use App\Repository\Exceptions\QueryException;
use GuzzleHttp\Exception\ConnectException;

$stationsImporter = new ImportAllStations();
try {
    $stations = $stationsImporter->doImport();
} catch (ConnectException $e) {
    echo $e->getMessage() . PHP_EOL;
    die;
}

foreach ($stations as $key => $station) {
    $stationImporter = new ImportStation($station['sepa_id']);
    try {
        $stationDetails = $stationImporter->doImport();
    } catch (ConnectException $e) {
        echo $e->getMessage() . PHP_EOL;
        die;
    }
    $stations[$key] = array_merge($station, $stationDetails);
}

foreach ($stations as $s) {
    try {
        $station = new Station($s);
        $station->save();
    } catch (QueryException $e) {
        echo $s['name'] . ' skipped because SEPA_ID ' . $s['sepa_id'] . ' or EOI_CODE ' . $s['eoi_code'] . ' most likely already exists' . PHP_EOL;
        echo $e->getMessage() . PHP_EOL;
    }
}

echo 'All stations saved' . PHP_EOL . PHP_EOL;