<?php

namespace FrankDeBoerOnline\Routing;

class TestRouter extends AbstractRouter
{

    public function requestIndex()
    {
        $this->respond('Index page');
    }

    public function getTestPath()
    {
        $this->respondJson(['result' => 'Test passed']);
    }

    public function postTest()
    {
        $this->respondJsonError('Error');
    }

    public function requestHealthcheck()
    {
        return $this->respond('Health ok');
    }

}