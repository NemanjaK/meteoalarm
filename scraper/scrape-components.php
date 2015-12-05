<?php

require_once 'bootstrap.php';

use Importers\ImportComponents;
use App\Repository\Entity\Component;
use App\Repository\Exceptions\QueryException;
use GuzzleHttp\Exception\ConnectException;

$importer = new ImportComponents();
try {
    $components = $importer->doImport();
} catch (ConnectException $e) {
    echo $e->getMessage() . PHP_EOL;
    die;
}

foreach ($components as $c) {
    try {
        $component = new Component($c);
        $component->save();
    } catch (QueryException $e) {
        echo $c['name'] . ' skipped because SEPA_ID ' . $c['sepa_id'] . ' most likely already exists' . PHP_EOL;
        echo $e->getMessage() . PHP_EOL;
    }

}

echo 'All components saved' . PHP_EOL . PHP_EOL;