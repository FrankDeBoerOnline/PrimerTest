<?php

namespace FrankDeBoerOnline\Routing;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class AbstractRouter implements RouterInterface
{

    /**
     * @var Request
     */
    private $request;

    /**
     * @var Routing
     */
    private $routing;

    /**
     * @var Response
     */
    private $response;

    public function __construct(Request $request, Routing $routing)
    {
        $this->setRequest($request);
        $this->setRouting($routing);
    }

    public function handleRequest()
    {
        $handlerFunction = $this->getHandlerFunction();
        if(!$handlerFunction) {
            return false;
        }

        $response = $this->$handlerFunction();
        if($response === null) {
            return $this->getResponse();
        }

        return $response;
    }

    public function getHandlerFunction()
    {
        $prefixes = $this->getHandlerPrefixes();

        $pathParts = preg_split('/\//', $this->getRequest()->getPathInfo(), null, PREG_SPLIT_NO_EMPTY);
        foreach($pathParts as $k => $v) {
            $pathParts[$k] = ucwords($v);
        }

        while(count($pathParts)) {
            $function = implode('', $pathParts);
            foreach($prefixes as $prefix) {
                if(is_callable([$this, $prefix . $function])) {
                    return $prefix . $function;
                }
            }
            array_pop($pathParts);
        }

        $function = 'Index';
        foreach($prefixes as $prefix) {
            if(is_callable([$this, $prefix . $function])) {
                return $prefix . $function;
            }
        }

        return false;
    }

    public function getHandlerPrefixes()
    {
        switch($this->getRequest()->getMethod()) {
            case Request::METHOD_GET:
                return ['request', 'get'];
            case Request::METHOD_POST:
                return ['request', 'post'];
        }

        return ['request'];
    }

    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param Request $request
     * @return $this
     */
    private function setRequest(Request $request)
    {
        $this->request = $request;
        return $this;
    }

    public function getRouting()
    {
        return $this->routing;
    }

    /**
     * @param Routing $routing
     * @return $this
     */
    private function setRouting(Routing $routing)
    {
        $this->routing = $routing;
        return $this;
    }

    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param Response $response
     * @return $this
     */
    protected function setResponse(Response $response)
    {
        $this->response = $response;
        return $this;
    }

    /**
     * @param string $content
     * @param int $status
     * @param array $headers
     * @return Response
     */
    protected function respond($content = '', int $status = 200, array $headers = [])
    {
        $response = $this->getRouting()->respond($content, $status, $headers);
        $this->setResponse($response);
        return $response;
    }

    /**
     * @param mixed $data    The response data
     * @param int   $status  The response status code
     * @param array $headers An array of response headers
     * @return JsonResponse
     */
    protected function respondJson($data = null, int $status = Response::HTTP_OK, array $headers = [])
    {
        $jsonResponse = $this->getRouting()->respondJson($data, $status, $headers);
        $this->setResponse($jsonResponse);
        return $jsonResponse;
    }

    /**
     * @param string $errorMessage
     * @param int $status
     * @return JsonResponse
     */
    protected function respondJsonError($errorMessage, int $status = Response::HTTP_BAD_REQUEST)
    {
        $jsonResponse = $this->getRouting()->respondJsonError($errorMessage, $status);
        $this->setResponse($jsonResponse);
        return $jsonResponse;
    }

}