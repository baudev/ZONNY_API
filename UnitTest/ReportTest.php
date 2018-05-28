<?php
/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 28/05/2018
 * Time: 22:52
 */

namespace ZONNY\UnitTest;

require_once dirname(__FILE__) . '/../vendor/autoload.php';
require_once dirname(__FILE__) . '/../config.php';

use DateTime;
use ZONNY\Models\Account\Report;
use PHPUnit\Framework\TestCase;
use ZONNY\Models\Account\User;
use ZONNY\Repositories\Account\ReportRepository;
use ZONNY\Utils\Database;
use ZONNY\Utils\DatetimeISO8601;

class ReportTest extends TestCase
{

    public function testProcurement(){
        $entityManager = Database::getEntityManager();
        $reportRepository = ReportRepository::getRepository();

        $user = new User();
        $user->setName("user1");
        $user->setKeyApp("key1");
        $user->setPlatform(1);
        $user->setCreationDatetime(new DateTime());
        $entityManager->persist($user);

        $user2 = new User();
        $user2->setName("user2");
        $user2->setKeyApp("key2");
        $user2->setPlatform(1);
        $user2->setCreationDatetime(new DateTime());
        $entityManager->persist($user2);

        $report = new Report();
        $report->setConcernedUser($user);
        $report->setByUser($user2);
        $report->setCategory("Other reason");
        $report->setMessage("He insulted me !");
        $report->setCreationDatetime(new Datetime());

        $entityManager->persist($report);
        $entityManager->flush();
        /**
         * @var Report $report_copy
         */
        $report_copy = $reportRepository->find($report->getId());
        $this->assertEquals($report->getId(), $report_copy->getId());

        $entityManager->remove($report);
        $entityManager->remove($user);
        $entityManager->remove($user2);
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

        $user2 = new User();
        $user2->setName("user2");
        $user2->setKeyApp("key2");
        $user2->setPlatform(1);
        $user2->setCreationDatetime(new DateTime());
        $entityManager->persist($user2);

        $report = new Report();
        $report->setConcernedUser($user);
        $report->setByUser($user2);
        $report->setCategory("Other reason");
        $report->setMessage("He insulted me !");
        $report->setCreationDatetime(new Datetime());

        $entityManager->persist($report);
        $entityManager->flush();

        $this->assertNotNull($report->getId());

        $entityManager->remove($report);
        $entityManager->remove($user);
        $entityManager->remove($user2);
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

        $user2 = new User();
        $user2->setName("user2");
        $user2->setKeyApp("key2");
        $user2->setPlatform(1);
        $user2->setCreationDatetime(new DateTime());
        $entityManager->persist($user2);

        $report = new Report();
        $report->setConcernedUser($user);
        $report->setByUser($user2);
        $report->setCategory("Other reason");
        $report->setMessage("He insulted me !");
        $report->setCreationDatetime(new Datetime());

        $entityManager->persist($report);
        $entityManager->flush();

        $entityManager->remove($report);
        $entityManager->remove($user);
        $entityManager->remove($user2);
        $entityManager->flush();
        $this->assertNull($report->getId());
    }

    public function testModification(){
        $entityManager = Database::getEntityManager();
        $reportRepository = ReportRepository::getRepository();

        $user = new User();
        $user->setName("user1");
        $user->setKeyApp("key1");
        $user->setPlatform(1);
        $user->setCreationDatetime(new DateTime());
        $entityManager->persist($user);

        $user2 = new User();
        $user2->setName("user2");
        $user2->setKeyApp("key2");
        $user2->setPlatform(1);
        $user2->setCreationDatetime(new DateTime());
        $entityManager->persist($user2);

        $report = new Report();
        $report->setConcernedUser($user);
        $report->setByUser($user2);
        $report->setCategory("Other reason");
        $report->setMessage("He insulted me !");
        $report->setCreationDatetime(new Datetime());

        $entityManager->persist($report);
        $entityManager->flush();
        $report->setMessage("I hate him");
        $entityManager->flush();
        /**
         * @var Report $report_copy
         */
        $report_copy = $reportRepository->find($report->getId());
        $this->assertEquals("I hate him", $report_copy->getMessage());

        $entityManager->remove($report);
        $entityManager->remove($user);
        $entityManager->remove($user2);
        $entityManager->flush();
    }

}
