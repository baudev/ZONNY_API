<?php

use ZONNY\Utils\Log;
use PHPUnit\Framework\TestCase;
use ZONNY\Utils\PrivateError;
use ZONNY\Utils\PublicError;

require_once dirname(__FILE__) . '/../vendor/autoload.php';
require_once dirname(__FILE__) . '/../config.php';
\ZONNY\Utils\Application::init();

class LogTest extends TestCase
{

    public function test__construct()
    {
        try {
            new Log(null, "TEST CHECK UP");
        } catch (Exception $e) {
            $this->fail("Error while creating log. Line ".__LINE__.". ".$e->getMessage());
        }
        $this->assertTrue(TRUE);
    }
}

