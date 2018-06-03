<?php
/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 01/06/2018
 * Time: 22:13
 */

namespace ZONNY\UnitTest;

require_once dirname(__FILE__) . '/../vendor/autoload.php';
require_once dirname(__FILE__) . '/../config.php';

use DateTime;
use ZONNY\Models\Account\User;
use ZONNY\Models\Helpers\Log;
use PHPUnit\Framework\TestCase;
use ZONNY\Repositories\Helpers\LogRepository;
use ZONNY\Utils\Database;

class LogTest extends TestCase
{

    public function testProcurement(){
        $entityManager = Database::getEntityManager();
        $logLinkRepository = LogRepository::getRepository();

        $user = new User();
        $user->setName("user1");
        $user->setKeyApp("key1");
        $user->setPlatform(1);
        $user->setCreationDatetime(new DateTime());
        $entityManager->persist($user);

        $log = new Log();
        $log->setUser($user);
        $log->setType("test");
        $log->setUrlRequest('/account/3018');
        $log->setIp('192.138.0.0');
        $log->setHackAttempt(false);
        $log->setCreationDatetime(new DateTime());
        $entityManager->persist($log);
        $entityManager->flush();
        /**
         * @var Log $log_copy
         */
        $log_copy = $logLinkRepository->find($log->getId());
        $this->assertEquals($log->getId(), $log_copy->getId());

        $entityManager->remove($log);
        $entityManager->remove($user);
        $entityManager->flush();
    }

    public function testInsertion(){
        $entityManager = Database::getEntityManager();

        $user = new User();
        $user->setName("user1");
        $user->setKeyApp("key1");
        $user->setPlatform(1);
        $user->setCreationDatetime(new DateTime());
        $entityManager->persist($user);

        $log = new Log();
        $log->setUser($user);
        $log->setType("test");
        $log->setUrlRequest('/account/3018');
        $log->setIp('192.138.0.0');
        $log->setHackAttempt(false);
        $log->setCreationDatetime(new DateTime());
        $entityManager->persist($log);
        $entityManager->flush();

        $this->assertNotNull($log->getId());

        $entityManager->remove($log);
        $entityManager->remove($user);
        $entityManager->flush();
    }

    public function testDeletion(){
        $entityManager = Database::getEntityManager();

        $user = new User();
        $user->setName("user1");
        $user->setKeyApp("key1");
        $user->setPlatform(1);
        $user->setCreationDatetime(new DateTime());
        $entityManager->persist($user);

        $log = new Log();
        $log->setUser($user);
        $log->setType("test");
        $log->setUrlRequest('/account/3018');
        $log->setIp('192.138.0.0');
        $log->setHackAttempt(false);
        $log->setCreationDatetime(new DateTime());
        $entityManager->persist($log);
        $entityManager->flush();

        $entityManager->remove($log);
        $entityManager->remove($user);
        $entityManager->flush();
        $this->assertNull($log->getId());
    }

    public function testModification(){
        $entityManager = Database::getEntityManager();
        $logLinkRepository = LogRepository::getRepository();

        $user = new User();
        $user->setName("user1");
        $user->setKeyApp("key1");
        $user->setPlatform(1);
        $user->setCreationDatetime(new DateTime());
        $entityManager->persist($user);

        $log = new Log();
        $log->setUser($user);
        $log->setType("test");
        $log->setUrlRequest('/account/3018');
        $log->setIp('192.138.0.0');
        $log->setHackAttempt(false);
        $log->setCreationDatetime(new DateTime());
        $entityManager->persist($log);
        $entityManager->flush();
        $log->setHackAttempt(true);
        $entityManager->flush();
        /**
         * @var Log $log_copy
         */
        $log_copy = $logLinkRepository->find($log->getId());
        $this->assertTrue($log_copy->getHackAttempt());

        $entityManager->remove($log);
        $entityManager->remove($user);
        $entityManager->flush();
    }


}
