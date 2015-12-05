<?php

namespace Importers;

use GuzzleHttp\Client;
use Sunra\PhpSimple\HtmlDomParser;

class ImportStation
{
    protected $cachePath = 'data-cache/station/';
    protected $sepaUrl = 'http://amskv.sepa.gov.rs/';
    protected $sepaPage = 'pregledpodataka.php';
    protected $queryString = 'stanica';

    protected $sepaId;
    protected $cacheFile;
    protected $client;

    public function __construct($sepaId)
    {
        $this->cachePath .= date("Y-m-d") . '/';

        $this->cacheFile = md5($this->sepaUrl . $this->sepaPage . '-s-' . $sepaId . '-' . date("Y-m-d")) . '.dat';
        $this->client = new Client([
            'base_uri' => $this->sepaUrl,
            'timeout' => 5.0,
        ]);
        $this->sepaId = $sepaId;
    }

    public function doImport()
    {
        if (file_exists($this->cachePath . $this->cacheFile) === false) {
            $response = $this->client->get($this->sepaPage, ['query' => [
                $this->queryString => $this->sepaId
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

        foreach ($html->find('tr') as $row) {
            if (count($row->children()) === 2) {
                switch (trim($row->children(0)->plaintext)) {
                    case 'Naziv stanice':
                        $name = $row->children(1)->plaintext;
                        break;
                    case 'Grad':
                        $city = $row->children(1)->plaintext;
                        break;
                    case 'Početak rada':
                        $started = $row->children(1)->plaintext;
                        break;
                    case 'Pripada mreži':
                        $network = $row->children(1)->plaintext;
                        break;
                    case 'EOI Code':
                        $eoiCode = $row->children(1)->plaintext;
                        break;
                    case 'Klasifikacija':
                        $type = $row->children(1)->plaintext;
                        break;
                    case 'Zona':
                        $zone = $row->children(1)->plaintext;
                        break;
                    case 'Latitude':
                        $latitude = $row->children(1)->plaintext;
                        break;
                    case 'Longitude':
                        $longitude = $row->children(1)->plaintext;
                        break;
                    case 'Nadmorska visina':
                        $altitude = $row->children(1)->plaintext;
                        break;
                }
            }
        }

        $node = [
            'name' => $name,
            'city' => $city,
            'started' => $started,
            'eoi_code' => trim($eoiCode),
            'network' => trim($network),
            'type' => trim($type),
            'zone' => trim($zone),
            'latitude' => floatval($latitude),
            'longitude' => floatval($longitude),
            'altitude' => intval(str_replace('m', '', $altitude)),
        ];

        return $node;
    }
}