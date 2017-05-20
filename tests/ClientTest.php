<?php

namespace Andaris\ComputerVision\Tests;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

use Andaris\ComputerVision\Client;
use Andaris\ComputerVision\Exception\ClientException;

class ClientTest extends TestCase
{
    public function testApiError()
    {
        $mock = new MockHandler([
            //@codingStandardsIgnoreStart
            new Response(401, [], '{ "statusCode": 401, "message": "Access denied due to missing subscription key. Make sure to include subscription key when making requests to an API." }'),
            //@codingStandardsIgnoreEnd
        ]);

        $handler = HandlerStack::create($mock);
        $httpClient = new HttpClient(['handler' => $handler]);

        $client = new Client('ffffffffffffffffffffffffffffffff', 'localhost', $httpClient);
        $this->expectException(ClientException::class);
        $client->analyze('here would be the binary image data');
    }

    public function testApiSuccess()
    {
        //@codingStandardsIgnoreStart
        $jsonResult = '{"tags":[{"name":"outdoor","confidence":0.96376746892929077},{"name":"animal","confidence":0.91122323274612427},{"name":"penguin","confidence":0.88990730047225952,"hint":"bird"},{"name":"black","confidence":0.67244237661361694},{"name":"aquatic bird","confidence":0.543761670589447,"hint":"bird"},{"name":"colorful","confidence":0.2672017514705658}],"requestId":"1b54ff3c-4ace-4329-8e44-f4a287974dfe","metadata":{"width":1024,"height":768,"format":"Jpeg"}}';
        //@codingStandardsIgnoreEnd
        $expectedResult = json_decode($jsonResult, true);

        $mock = new MockHandler([
            //@codingStandardsIgnoreStart
            new Response(200, [], $jsonResult),
            //@codingStandardsIgnoreEnd
        ]);

        $handler = HandlerStack::create($mock);
        $httpClient = new HttpClient(['handler' => $handler]);

        $client = new Client('ffffffffffffffffffffffffffffffff', 'localhost', $httpClient);
        $this->assertSame($expectedResult, $client->analyze('here would be the binary image data'));
    }
}
