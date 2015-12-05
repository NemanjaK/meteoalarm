<?php

require_once 'bootstrap.php';

use Importers\ImportComponents;

$importer = new ImportComponents();

$components = $importer->doImport();
var_dump($components);
die;