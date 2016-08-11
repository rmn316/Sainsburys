<?php

namespace FruitsBundle\Service;

use Symfony\Component\DomCrawler\Crawler;

class GetFruitsService
{
    /** @var GetFruitsContentService */
    private $fruitContentService;
    /** @var GetFruitDetailService */
    private $detailService;

    /**
     * GetFruitsService constructor.
     * @param GetFruitsContentService $contentService
     * @param GetFruitDetailService $detailService
     */
    public function __construct(GetFruitsContentService $contentService, GetFruitDetailService $detailService)
    {
        $this->fruitContentService = $contentService;
        $this->detailService = $detailService;
    }

    /**
     * Process/Scrape the url to return an array of specified data.
     *
     * @param string $url
     * @return array
     */
    public function processPage($url)
    {
        $crawler = $this->fruitContentService->getPageContent($url);
        $links = $this->getLinks($crawler);
        return $this->buildPageData($links);
    }

    /**
     * @param array $links
     * @return array
     */
    private function buildPageData(array $links)
    {
        $totalUnitPrice = 0;
        $result = [];
        foreach ($links as $link) {

            $record = $this->detailService->generate($link);
            $totalUnitPrice += $record['unit_price'];
            $result[] = $record;
        }

        return ['results' => $result, 'total' => number_format($totalUnitPrice, 2)];
    }

    /**
     * Get an array of page links to inspect from the listing page.
     *
     * @param Crawler $crawler
     * @return array
     */
    private function getLinks(Crawler $crawler)
    {
        return $crawler->filter('#productLister .productInfo a')->each(function(Crawler $node) {
            return $node->attr('href');
        });
    }
}