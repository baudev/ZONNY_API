<?php

use PHPUnit\Framework\TestCase;
use ZONNY\Utils\Database;

require_once dirname(__FILE__) . '/../vendor/autoload.php';
require_once dirname(__FILE__) . '/../config.php';

class DatabaseTest extends TestCase
{

    private $_db;

    /**
     * Vérifie la connexion à la base de données
     */
    public function testConnect()
    {
        $this->_db = new Database();
        try {
            $this->_db->connectPostgreSQL();
            $this->assertEquals(get_class($this->_db->getDb()), 'PDO');
        }
        catch (PDOException $e){
            print_r($e);
            $this->expectException(PDOException::class);
        } catch (Exception $e) {
            $this->expectException(Exception::class);
        }
    }
}
