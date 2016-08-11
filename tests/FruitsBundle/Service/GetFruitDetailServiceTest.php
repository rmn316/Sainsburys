<?php

namespace FruitsBundle\Test\Service;

use FruitsBundle\Service\GetFruitDetailService;
use FruitsBundle\Service\GetFruitsContentService;
use Symfony\Component\DomCrawler\Crawler;

class GetFruitDetailServiceTest extends \PHPUnit_Framework_TestCase
{
    /** @var GetFruitDetailService */
    private $getFruitDetailService;
    /** @var GetFruitsContentService|\PHPUnit_Framework_MockObject_InvocationMocker */
    private $getFruitContentService;

    public function setUp()
    {
        $this->getFruitContentService = $this->getMockBuilder(GetFruitsContentService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->getFruitDetailService = new GetFruitDetailService($this->getFruitContentService);
    }

    public function testGenerate()
    {
        $this->getFruitContentService->expects($this->once())
            ->method('getPageSize')
            ->with()
            ->willReturn('1.2MB');

        $this->getFruitContentService->expects($this->once())
            ->method('getPageContent')
            ->with('TEST_URL')
            ->willReturn(new Crawler(file_get_contents(__DIR__ . '/data/productpage.html')));

        $result = $this->getFruitDetailService->generate('TEST_URL');

        $this->assertSame("Sainsbury's Conference Pears, Ripe & Ready x4 (minimum)", $result['title']);
        $this->assertSame('1.2MB', $result['size']);
        $this->assertSame('1.50', $result['unit_price']);
        $this->assertSame('Conference', $result['description']);

    }
}