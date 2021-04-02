<?php

use FrankDeBoerOnline\Configuration\Configuration;
use FrankDeBoerOnline\Configuration\ResourceFinder;

ResourceFinder::addConfigDirectory(__DIR__);

Configuration::get();