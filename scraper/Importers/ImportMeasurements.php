<?php

namespace Importers;

use GuzzleHttp\Client;
use Sunra\PhpSimple\HtmlDomParser;

class ImportMeasurements
{
    protected $cachePath = 'data-cache/measurements/';
    protected $sepaUrl = 'http://amskv.sepa.gov.rs/';
    protected $sepaPage = 'konektori/pregled_tabela_uporedni.php';
    protected $stationQueryString = 'stanice';
    protected $componentsQueryString = 'komponente';

    protected $cacheFile;
    protected $client;

    protected $stationSepaId;
    protected $componentsSepaIdArray;

    public function __construct($stationSepaId, $componentsSepaIdArray)
    {
        $this->cachePath .= date("Y-m-d") . '/';
        if (is_readable($this->cachePath) === false) {
            mkdir($this->cachePath, 0755, true);
        }
        $this->cacheFile = md5($this->sepaUrl . $this->sepaPage . '-s-' . $stationSepaId . '-' . date("Y-m-d H")) . '.dat';
        $this->client = new Client([
            'base_uri' => $this->sepaUrl,
            'timeout' => 15.0,
        ]);

        $this->stationSepaId = $stationSepaId;
        $this->componentsSepaIdArray = $componentsSepaIdArray;
    }

    public function doImport()
    {
        $componentIdsArray = [];
        foreach ($this->componentsSepaIdArray as $component) {
            array_push($componentIdsArray, $component['sepa_id']);
        }

        if (file_exists($this->cachePath . $this->cacheFile) === false) {
            $response = $this->client->get($this->sepaPage, ['query' => [
                $this->stationQueryString => (array)$this->stationSepaId,
                $this->componentsQueryString => $componentIdsArray,
                'periodi' => ['dana7'],
                'agregacija' => [1],
                'pregledtabela_length' => 100,
            ]]);

            if ($response->getStatusCode() > 204) {
                echo "Can't fetch list \n";
                exit(1);
            }
            $content = $response->getBody();
            file_put_contents($this->cachePath . $this->cacheFile, $content);
        } else {
            $content = file_get_contents($this->cachePath . $this->cacheFile);
        }

        $html = HtmlDomParser::str_get_html($content);

        $measurements = [];

        foreach ($html->find('tr') as $row) {
            if ($row->children(0) !== 'Vreme' && $row->children(0)->tag === 'td') {
                $measureTimestamp = date("Y-m-d H:i:s", strtotime($row->children(0)->plaintext));
                $measurementComponents = [];
                foreach ($this->componentsSepaIdArray as $key => $component) {
                    $rowId = $key + 1;
                    $value = $row->children($rowId)->plaintext;
                    if (empty($value) === true) {
                        $value = null;
                    } else {
                        $value = floatval($value);
                    }
                    $measurementComponents[$component['sepa_id']] = $value;
                }

                $hourly = [
                    'timestamp' => $measureTimestamp,
                    'com_values' => $measurementComponents,
                ];
                array_push($measurements, $hourly);
            }
        }

        return $measurements;
    }
}