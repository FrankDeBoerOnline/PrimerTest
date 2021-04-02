<?php

namespace FrankDeBoerOnline\Routing;

use FrankDeBoerOnline\Configuration\Configuration;
use FrankDeBoerOnline\Error\Error;
use FrankDeBoerOnline\Routing\Error\RoutingErrorInvalidConfiguration;
use FrankDeBoerOnline\Routing\Error\RoutingErrorInvalidRoutingTableRow;
use FrankDeBoerOnline\Routing\Error\RoutingErrorInvalidRoutingType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Routing
{

    CONST ENV_CONFIG_NAME = 'ROUTING';

    CONST ROUTING_TYPE_URI = 'URI';
    CONST ROUTING_TYPE_REQUEST_URI = 'REQUEST_URI';

    /**
     * @var string[]
     */
    private $routingTable = [];

    /**
     * Routing constructor.
     * @param array $customRoutingTable
     * @param string $configName
     * @throws RoutingErrorInvalidConfiguration
     * @throws RoutingErrorInvalidRoutingTableRow
     */
    function __construct($customRoutingTable = [], $configName = self::ENV_CONFIG_NAME)
    {
        $this->loadRoutingTable($customRoutingTable, $configName);
    }

    /**
     * @param string $routingType
     * @param array $customRoutingTable
     * @param string $configName
     * @return void
     * @throws RoutingErrorInvalidConfiguration
     * @throws RoutingErrorInvalidRoutingTableRow
     * @throws RoutingErrorInvalidRoutingType
     */
    static public function globalRoute($routingType = self::ROUTING_TYPE_REQUEST_URI, $customRoutingTable = [], $configName = self::ENV_CONFIG_NAME)
    {
        $routing = new static($customRoutingTable, $configName);
        $response = $routing->route(Request::createFromGlobals(), $routingType);

        if (!$response) {
            $response = $routing->respond('Not found', Response::HTTP_NOT_FOUND);
        }

        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->send();
    }

    /**
     * @param Request $request
     * @param string $routingType
     * @return Response|bool
     * @throws RoutingErrorInvalidConfiguration
     * @throws RoutingErrorInvalidRoutingType
     */
    public function route(Request $request, $routingType = self::ROUTING_TYPE_REQUEST_URI)
    {
        switch($routingType) {
            case self::ROUTING_TYPE_REQUEST_URI:
                return $this->routeRequestUri($request);
            case self::ROUTING_TYPE_URI:
                return $this->routeUri($request);
        }

        throw new RoutingErrorInvalidRoutingType();
    }

    /**
     * @param Request $request
     * @return Response|bool
     * @throws RoutingErrorInvalidConfiguration
     */
    public function routeRequestUri(Request $request)
    {
        $routerClassName = $this->getRouterClassName($request->getRequestUri());
        if(!$routerClassName) {
            return false;
        }

        return $this->handleRequest($request, $routerClassName);
    }

    /**
     * @param Request $request
     * @return Response|bool
     * @throws RoutingErrorInvalidConfiguration
     */
    public function routeUri(Request $request)
    {
        $routerClassName = $this->getRouterClassName($request->getUri());
        if(!$routerClassName) {
            return false;
        }

        return $this->handleRequest($request, $routerClassName);
    }

    /**
     * @param Request $request
     * @param string $routerClassName
     * @return Response|bool
     * @throws RoutingErrorInvalidConfiguration
     */
    public function handleRequest(Request $request, $routerClassName)
    {
        $interfaces = class_implements($routerClassName);
        if(!isset($interfaces[RouterInterface::class])) {
            throw new RoutingErrorInvalidConfiguration();
        }

        /**
         * @var RouterInterface $router
         */
        $router = new $routerClassName($request, $this);
        return $router->handleRequest();
    }

    /**
     * @return string[]
     */
    public function getRoutingTable()
    {
        return $this->routingTable;
    }

    /**
     * @return array
     */
    public function getRouters()
    {
        $routers = [];
        foreach($this->routingTable as $url => $class) {
            if(!isset($routers[$class])) {
                $routers[$class] = [];
            }
            $routers[$class][] = $url;
        }
        return $routers;
    }

    /**
     * @param array $customRoutingTable
     * @param string $configName
     * @return int
     * @throws RoutingErrorInvalidConfiguration
     * @throws RoutingErrorInvalidRoutingTableRow
     */
    public function loadRoutingTable($customRoutingTable = [], $configName = self::ENV_CONFIG_NAME)
    {
        $unconvertedRoutingTable = $customRoutingTable ? $customRoutingTable : $this->loadRoutingTableFromConfig($configName);

        $this->routingTable = [];
        foreach($unconvertedRoutingTable as $index => $routingRow) {
            if(!isset($routingRow['url']) || !isset($routingRow['class'])) {
                throw new RoutingErrorInvalidRoutingTableRow();
            }

            if(!class_exists($routingRow['class'])) {
                throw new RoutingErrorInvalidRoutingTableRow();
            }

            $this->routingTable[(string)$routingRow['url']] = (string)$routingRow['class'];
        }

        return count($this->routingTable);
    }

    /**
     * @param string $configName
     * @return array
     * @throws RoutingErrorInvalidConfiguration
     */
    public function loadRoutingTableFromConfig($configName = self::ENV_CONFIG_NAME)
    {
        try {
            $config = Configuration::get();
            if(!isset($config[$configName])) {
                throw new RoutingErrorInvalidConfiguration();
            }

        } catch(Error $e) {
            throw new RoutingErrorInvalidConfiguration('', 0, $e);
        }

        return $config[$configName];
    }

    /**
     * @param $uri
     * @return string|null
     */
    public function getRouterClassName($uri)
    {
        foreach($this->getRoutingTable() as $urlPattern => $routerClassName) {
            $regex = '#' . $urlPattern . '#';
            if(preg_match($regex, $uri)) {
                return $routerClassName;
            }
        }

        return null;
    }

    /**
     * @param string $content
     * @param int $status
     * @param array $headers
     * @return Response
     */
    public function respond($content = '', int $status = 200, array $headers = [])
    {
        $response = new Response($content, $status, $headers);
        return $response;
    }

    /**
     * @param mixed $data    The response data
     * @param int   $status  The response status code
     * @param array $headers An array of response headers
     * @return JsonResponse
     */
    public function respondJson($data = null, int $status = Response::HTTP_OK, array $headers = [])
    {
        $jsonResponse = new JsonResponse($data, $status, $headers);
        return $jsonResponse;
    }

    /**
     * @param string $errorMessage
     * @param int $status
     * @return JsonResponse
     */
    public function respondJsonError($errorMessage, int $status = Response::HTTP_BAD_REQUEST)
    {
        return $this->respondJson(['error' => $errorMessage], $status);
    }

}