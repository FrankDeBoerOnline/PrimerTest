<?php

namespace Tests\FrankDeBoerOnline\Routing;

use FrankDeBoerOnline\Routing\Error\RoutingError;
use FrankDeBoerOnline\Routing\Error\RoutingErrorInvalidConfiguration;
use FrankDeBoerOnline\Routing\Error\RoutingErrorInvalidRoutingTableRow;
use FrankDeBoerOnline\Routing\Error\RoutingErrorInvalidRoutingType;
use FrankDeBoerOnline\Routing\Routing;
use FrankDeBoerOnline\Routing\TestRouter;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\PrimerTest\AbstractTest;

class RoutingTest extends AbstractTest
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

    /**
     * @throws RoutingErrorInvalidConfiguration
     * @throws RoutingErrorInvalidRoutingTableRow
     */
    public function testConstructWithDefaults()
    {
        $routing = new Routing();
        $this->assertGreaterThan(0, count($routing->getRouters()));
    }

    public function testCustomArray()
    {
        $routing = $this->getRoutingObject();
        $routers = $routing->getRouters();
        $this->assertGreaterThan(0, count($routers));
        $this->assertNotNull($routers[TestRouter::class]);
    }

    /**
     * @throws RoutingErrorInvalidConfiguration
     * @throws RoutingErrorInvalidRoutingTableRow
     */
    public function testExceptionInvalidConfiguration()
    {
        $this->expectException(RoutingErrorInvalidConfiguration::class);
        $routing = new Routing();
        $routing->loadRoutingTableFromConfig('invalid_config_name');
    }

    /**
     * @param string $urlPattern
     * @param string $urlPath
     * @param bool $returnsNotNull
     * @dataProvider dataProviderGetRoutingClassName
     */
    public function testGetRoutingClassName($urlPattern, $urlPath, $returnsNotNull)
    {
        $routing = $this->getRoutingObject($urlPattern);
        $routerClassName = $routing->getRouterClassName($urlPath);
        if(!$returnsNotNull) {
            $this->assertNull($routerClassName);

        } else {
            $this->assertSame(TestRouter::class, $routing->getRouterClassName($urlPath));
        }
    }

    /**
     * @return array
     */
    public function dataProviderGetRoutingClassName()
    {
        return [

            # Valid
            ['url' => '^(/|)$', 'path' => '', 'notNull' => true],
            ['url' => '^(/|)$', 'path' => '/', 'notNull' => true],
            ['url' => '^(/|)Test', 'path' => '/Test/Path', 'notNull' => true],
            ['url' => '^(/|)Test', 'path' => 'Test/Path', 'notNull' => true],

            # Not valid
            ['url' => '^/$', 'path' => '', 'notNull' => false],
            ['url' => '^(/|)$', 'path' => '/Test/Path', 'notNull' => false],
        ];
    }

    /**
     * @throws RoutingErrorInvalidConfiguration
     * @throws RoutingErrorInvalidRoutingType
     */
    public function testRouteExceptionInvalidRoutingType()
    {
        $this->expectException(RoutingErrorInvalidRoutingType::class);

        $request = Request::create('/');
        $routing = $this->getRoutingObject();

        $routing->route($request, 'InvalidRoutingType');
    }

    /**
     * @throws RoutingErrorInvalidConfiguration
     */
    public function testRouteRequestUriNoSuccess()
    {

        $request = Request::create('/Test/WrongPath?var1=test');
        $routing = $this->getRoutingObject('^/Test/Path$');

        $this->assertFalse($routing->routeRequestUri($request));
    }

    /**
     * @throws RoutingErrorInvalidConfiguration
     * @throws RoutingErrorInvalidRoutingTableRow
     */
    public function testRouteRequestUriExceptionRoutingErrorInvalidConfiguration()
    {
        $this->expectException(RoutingErrorInvalidConfiguration::class);

        $request = Request::create('/Test/Path?var1=test');
        # Create routing with class that does not implement RouterInterface
        $routing = new Routing([
            ['url' => '/Test', 'class' => Routing::class]
        ]);

        $routing->routeRequestUri($request);
    }

    /**
     * @throws RoutingErrorInvalidConfiguration
     */
    public function testRouteRequestUri()
    {
        $request = Request::create('/Test/Path?var1=test');
        $routing = $this->getRoutingObject('/Test');

        $this->assertNotFalse($routing->routeRequestUri($request));
    }

    public function testRespond()
    {
        $routing = $this->getRoutingObject();
        $response = $routing->respond();

        $this->assertSame(Response::class, get_class($response));
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testRespondJson()
    {
        $routing = $this->getRoutingObject();
        $jsonResponse = $routing->respondJson();

        $this->assertSame(JsonResponse::class, get_class($jsonResponse));
        $this->assertSame('{}', $jsonResponse->getContent());
    }

    public function testRespondJsonError()
    {
        $routing = $this->getRoutingObject();
        $jsonResponse = $routing->respondJsonError('Testing');

        $this->assertSame(JsonResponse::class, get_class($jsonResponse));
        $this->assertSame('{"error":"Testing"}', $jsonResponse->getContent());
    }

    public function testRouteHealthcheck()
    {
        $request = Request::create('/healthcheck');
        $routing = new Routing();

        $response = $routing->route($request);

        $this->assertSame('Health ok', $response->getContent());
    }

}