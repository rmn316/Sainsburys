<?php

namespace FruitsBundle\Test\Service;

use FruitsBundle\Service\GetFruitsContentService;
use Goutte\Client;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\DomCrawler\Crawler;

class GetFruitsContentServiceTest extends \PHPUnit_Framework_TestCase
{
    /** @var GetFruitsContentService */
    private $getFruitsContentService;
    /** @var Client|\PHPUnit_Framework_MockObject_InvocationMocker */
    private $client;

    public function setUp()
    {
        $this->client = $this->getMockBuilder(Client::class)->disableOriginalConstructor()->getMock();

        $this->getFruitsContentService = new GetFruitsContentService($this->client);
    }

    public function testGetPageContent()
    {
        $this->client->expects($this->once())
            ->method('request')
            ->with('GET', 'URL')
            ->willReturn(new Crawler());

        $this->getFruitsContentService->getPageContent('URL');
    }

    public function testGetPageSize()
    {
        $headers = [
            'Content-Length' => [
                1234
            ]
        ];

        $this->client->expects($this->once())
            ->method('getInternalResponse')
            ->with()
            ->willReturn(new Response('', 200, $headers));

        $result = $this->getFruitsContentService->getPageSize();

        $this->assertSame('1234KB', $result);
    }
}