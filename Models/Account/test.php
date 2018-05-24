<?php
/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 24/05/2018
 * Time: 12:45
 */

require_once '../../config.php';
require_once '../../vendor/autoload.php';

$entityManager = \ZONNY\Utils\Database::getEntityManager();

$user = new \ZONNY\Models\Account\User();

$user->setName("user1");
$user->setKeyApp("key1");
$user->setPlatform(1);
$user->setCreationDatetime(new DateTime());
$entityManager->persist($user);

$user2 = new \ZONNY\Models\Account\User();
$user2->setName("user2");
$user2->setKeyApp("key2");
$user2->setPlatform(1);
$user2->setCreationDatetime(new DateTime());
$entityManager->persist($user2);

$link = new \ZONNY\Models\Account\FriendsLink();
$link->setUser1($user);
$link->setUser2($user2);
$link->setCreationDatetime(new DateTime());

$entityManager->persist($link);


$entityManager->flush();
echo $link->getId();
/**
 * @var \ZONNY\Models\Account\FriendsLink $test
 */
$test = $entityManager->find(\ZONNY\Models\Account\FriendsLink::class, 13);
print_r($test->getId());

