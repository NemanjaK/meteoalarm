<?php

namespace Importers;

use GuzzleHttp\Client;
use Sunra\PhpSimple\HtmlDomParser;

class ImportAllStations
{
    protected $cachePath = 'data-cache/station/';
    protected $sepaUrl = 'http://amskv.sepa.gov.rs/';
    protected $sepaPage = 'pregledstanica.php';

    protected $cacheFile;
    protected $client;

    public function __construct()
    {
        $this->cachePath .= date("Y-m-d") . '/';
        if (is_readable($this->cachePath) === false) {
            mkdir($this->cachePath, 0755, true);
        }

        $this->cacheFile = md5($this->sepaUrl . $this->sepaPage . '-' . date("Y-m-d")) . '.dat';
        $this->client = new Client([
            'base_uri' => $this->sepaUrl,
            'timeout' => 5.0,
        ]);
    }

    public function doImport()
    {
        if (file_exists($this->cachePath . $this->cacheFile) === false) {
            $response = $this->client->get($this->sepaPage);

            if ($response->getStatusCode() > 204) {
                echo "Can't fetch list \n";
                exit(1);
            }
            $content = $response->getBody();
            file_put_contents($this->cachePath . $this->cacheFile, $content);
        } else {
            $content = file_get_contents($this->cachePath . $this->cacheFile);
        }

        $nodes = [];

        $html = HtmlDomParser::str_get_html($content);

        foreach ($html->find('tr') as $row) {
            if (count($row->children()) === 7) {
                $rowId = $row->children(0)->plaintext;
                $code = $row->children(1)->plaintext;
                $name = trim($row->children(3)->plaintext);
                $network = $row->children(4)->plaintext;
                $type = $row->children(5)->plaintext;

                //get sepa cms ID
                preg_match("/stanica=([\d]+)/", $row->children(3)->innertext, $matches);
                if (isset($matches[1]) === true && is_numeric($matches[1]) && intval($matches[1]) > 0) {
                    $sepaId = $matches[1];

                    $node = [
                        'eoi_code' => trim($code),
                        'name' => trim($name),
                        'network' => trim($network),
                        'type' => trim($type),
                        'sepa_id' => intval($sepaId),
                    ];

                    array_push($nodes, $node);
                }
            }
        }

        return $nodes;
    }
}