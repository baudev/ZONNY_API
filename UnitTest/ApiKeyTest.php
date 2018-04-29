<?php

use ZONNY\Utils\ApiKey;
use PHPUnit\Framework\TestCase;

require_once dirname(__FILE__) . '/../vendor/autoload.php';
require_once dirname(__FILE__) . '/../config.php';
\ZONNY\Utils\Application::init();

class ApiKeyTest extends TestCase
{

    public function testGenerateApiKey()
    {
        $this->assertRegExp('#\w{100}#', ApiKey::generateApiKey());
    }
}
