<?php

namespace FruitsBundle\Service;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;


/**
 * Created by PhpStorm.
 * User: robneal
 * Date: 10/08/2016
 * Time: 21:12
 */
class GetFruitsContentService
{
    /** @var Client */
    private $client;

    /**
     * GetFruitsContentService constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param $url
     * @return Crawler
     */
    public function getPageContent($url)
    {
        return $this->client->request('GET', $url);
    }

    /**
     * @param int $precision
     * @return string
     */
    public function getPageSize($precision = 2)
    {
        return $this->formatBytes($this->client->getInternalResponse()->getHeaders()['Content-Length'][0], $precision);
    }

    /**
     * @param $bytes
     * @param $precision
     * @return string
     */
    private function formatBytes($bytes, $precision) {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        // Uncomment one of the following alternatives
        // $bytes /= pow(1024, $pow);
        // $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . '' . $units[$pow];
    }
}