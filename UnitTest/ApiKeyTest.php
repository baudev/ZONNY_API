<?php
/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 03/06/2018
 * Time: 22:06
 */

namespace ZONNY\UnitTest;

require_once dirname(__FILE__) . '/../vendor/autoload.php';
require_once dirname(__FILE__) . '/../config.php';

use ZONNY\Utils\ApiKey;
use PHPUnit\Framework\TestCase;

class ApiKeyTest extends TestCase
{

    public function testGenerateApiKey()
    {
        $api_key = ApiKey::generateApiKey();
        $this->assertEquals(API_KEY_CHARAC_NUMBER, strlen($api_key));
    }
}
