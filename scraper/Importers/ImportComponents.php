<?php

namespace Importers;

use GuzzleHttp\Client;
use Sunra\PhpSimple\HtmlDomParser;

class ImportComponents
{
    protected $cachePath = 'data-cache/components/';
    protected $sepaUrl = 'http://amskv.sepa.gov.rs/';
    protected $sepaPage = 'pregledpodatakazbirni.php';

    protected $cacheFile;
    protected $client;

    public function doImport()
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

        $components = [];

        $html = HtmlDomParser::str_get_html($content);

        foreach ($html->find('label[class=komponentelabela]') as $label) {
            if (count($label->children())) {
                $sepaId = $label->children(0)->attr['value'];
                $name = $label->children(1)->plaintext;

                $component = [
                    'sepa_id' => intval($sepaId),
                    'name' => trim($name),
                ];

                array_push($components, $component);
            }
        }

        return $components;
    }
}