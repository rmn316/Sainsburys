<?php

namespace FruitsBundle\Test\Service;

use FruitsBundle\Service\GetFruitDetailService;
use FruitsBundle\Service\GetFruitsContentService;
use FruitsBundle\Service\GetFruitsService;
use Symfony\Component\DomCrawler\Crawler;

class GetFruitsServiceTest extends \PHPUnit_Framework_TestCase
{
    /** @var GetFruitsService */
    private $getFruitsService;
    /** @var GetFruitsContentService|\PHPUnit_Framework_MockObject_InvocationMocker */
    private $getFruitsContentService;
    /** @var GetFruitDetailService|\PHPUnit_Framework_MockObject_InvocationMocker */
    private $getFruitDetailService;

    public function setUp()
    {
        $this->getFruitsContentService = $this->getMockBuilder(GetFruitsContentService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->getFruitDetailService = $this->getMockBuilder(GetFruitDetailService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->getFruitsService = new GetFruitsService($this->getFruitsContentService, $this->getFruitDetailService);
    }

    public function testProcessPage()
    {
        $this->getFruitsContentService->expects($this->once())
            ->method('getPageContent')
            ->with('TEST_LISTING_PAGE')
            ->willReturn(new Crawler(file_get_contents(__DIR__ . '/data/listing.html')));

        $this->getFruitDetailService->expects($this->atLeastOnce())
            ->method('generate')
            ->willReturn(
                [
                    'title' => 'Test Title',
                    'size' => '123MB',
                    'unit_price' => 1.00,
                    'description' => 'Test Description'
                ]
            );

        $result = $this->getFruitsService->processPage('TEST_LISTING_PAGE');

        $this->assertSame(7, count($result['results']));
        $this->assertSame("7.00", $result['total']);
    }

}