<?php
require_once 'vendor/autoload.php';
require_once 'config.php';
use \ZONNY\Utils\Database;

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet(Database::getEntityManager());