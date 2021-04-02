<?php

namespace Tests\FrankDeBoerOnline\Routing;

use FrankDeBoerOnline\Routing\Error\RoutingError;
use FrankDeBoerOnline\Routing\Routing;
use FrankDeBoerOnline\Routing\TestRouter;
use Symfony\Component\HttpFoundation\Request;
use Tests\PrimerTest\AbstractTest;

class RouterTest extends AbstractTest
{

    /**
     * @param string $url
     * @return Routing
     */
    private function getRoutingObject($url = '^(/|)')
    {
        try {
            return (new Routing([
                [
                    'url' => $url,
                    'class' => TestRouter::class
                ]
            ]));

        } catch (RoutingError $e) {
            $this->fail('Default routing code not working: ' . $e->getMessage());
        }
    }

    public function getRequestObject($url = '/Test/Path') {
        return Request::create($url);
    }

    public function testDefaults()
    {
        $request = $this->getRequestObject();
        $routing = $this->getRoutingObject();
        $router = new TestRouter($request, $routing);

        $this->assertSame($request, $router->getRequest());
        $this->assertSame($routing, $router->getRouting());
    }

    /**
     * @param string $requestMethod
     * @param array $expectedPrefix
     * @dataProvider dataProviderGetHandlerPrefixes
     */
    public function testGetHandlerPrefixes($requestMethod, $expectedPrefix)
    {
        $request = $this->getRequestObject();
        $request->setMethod($requestMethod);

        $routing = $this->getRoutingObject();
        $router = new TestRouter($request, $routing);

        $this->assertSame($expectedPrefix, $router->getHandlerPrefixes());
    }

    public function dataProviderGetHandlerPrefixes()
    {
        return [
            ['method' => Request::METHOD_GET, ['request', 'get']],
            ['method' => Request::METHOD_POST, ['request', 'post']],
        ];
    }

    public function testGetHandlerFunction()
    {
        $request = $this->getRequestObject();
        $request->setMethod(Request::METHOD_GET);

        $routing = $this->getRoutingObject();
        $router = new TestRouter($request, $routing);

        $this->assertSame('getTestPath', $router->getHandlerFunction());
    }

    public function testHandleRequest()
    {
        $request = $this->getRequestObject();
        $request->setMethod(Request::METHOD_GET);

        $routing = $this->getRoutingObject();
        $router = new TestRouter($request, $routing);

        $this->assertSame('{"result":"Test passed"}', $router->handleRequest()->getContent());
    }

    public function testHandleRequestIndex()
    {
        $request = $this->getRequestObject('/');
        $request->setMethod(Request::METHOD_GET);

        $routing = $this->getRoutingObject();
        $router = new TestRouter($request, $routing);

        $this->assertSame('Index page', $router->handleRequest()->getContent());
    }

    public function testHandleRequestPost()
    {
        $request = $this->getRequestObject('/Test/Path');
        $request->setMethod(Request::METHOD_POST);

        $routing = $this->getRoutingObject();
        $router = new TestRouter($request, $routing);

        $this->assertSame('{"error":"Error"}', $router->handleRequest()->getContent());
    }

}