<?php
/**
 * Created by PhpStorm.
 * User: robneal
 * Date: 11/08/2016
 * Time: 12:52
 */

namespace FruitsBundle\Service;

class GetFruitDetailService
{
    /** @var GetFruitsContentService */
    private $contentService;

    public function __construct(GetFruitsContentService $contentService)
    {
        $this->contentService = $contentService;
    }

    public function generate($url)
    {
        $productPageSize = $this->contentService->getPageSize();
        $productPage = $this->contentService->getPageContent($url);

        return [
            'title' => $productPage->filter('h1')->text(),
            'size' => $productPageSize,
            'unit_price' => $this->buildUnitPrice($productPage->filter('p.pricePerUnit')->text()),
            'description' => trim($productPage->filter('#information .productText')->first()->text())
        ];
    }

    private function buildUnitPrice($unitPriceText)
    {
        return number_format(preg_replace('/[^\d\.]/', '', $unitPriceText), 2);
    }


}