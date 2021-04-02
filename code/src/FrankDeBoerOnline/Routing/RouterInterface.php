<?php

namespace FrankDeBoerOnline\Routing;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface RouterInterface
{

    /**
     * @param Request $request
     * @param Routing $routing
     */
    public function __construct(Request $request, Routing $routing);

    /**
     * @return Response|bool
     */
    public function handleRequest();

    /**
     * @return callable|bool
     */
    public function getHandlerFunction();

    /**
     * @return string[]
     */
    public function getHandlerPrefixes();

    /**
     * @return Request
     */
    public function getRequest();

    /**
     * @return Routing
     */
    public function getRouting();

    /**
     * @return Response
     */
    public function getResponse();

}