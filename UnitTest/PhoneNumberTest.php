<?php
/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 28/05/2018
 * Time: 22:41
 */

namespace ZONNY\UnitTest;

require_once dirname(__FILE__) . '/../vendor/autoload.php';
require_once dirname(__FILE__) . '/../config.php';

use DateTime;
use ZONNY\Models\Account\PhoneNumber;
use PHPUnit\Framework\TestCase;
use ZONNY\Models\Account\User;
use ZONNY\Repositories\Account\PhoneNumberRepository;
use ZONNY\Utils\Database;

class PhoneNumberTest extends TestCase
{
    public function testProcurement(){
        $entityManager = Database::getEntityManager();
        $phoneNumberRepository = PhoneNumberRepository::getRepository();

        $user = new User();
        $user->setName("user1");
        $user->setKeyApp("key1");
        $user->setPlatform(1);
        $user->setCreationDatetime(new DateTime());
        $entityManager->persist($user);



        $phoneNumber = new PhoneNumber();
        $phoneNumber->setUser($user);
        $phoneNumber->setPhoneNumber("+336012345678");
        $phoneNumber->setCreationDatetime(new DateTime());
        $entityManager->persist($phoneNumber);
        $entityManager->flush();
        /**
         * @var PhoneNumber $phoneNumber_copy
         */
        $phoneNumber_copy = $phoneNumberRepository->find($phoneNumber->getId());
        $this->assertEquals($phoneNumber->getId(), $phoneNumber_copy->getId());

        $entityManager->remove($phoneNumber);
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



        $phoneNumber = new PhoneNumber();
        $phoneNumber->setUser($user);
        $phoneNumber->setPhoneNumber("+336012345678");
        $phoneNumber->setCreationDatetime(new DateTime());
        $entityManager->persist($phoneNumber);
        $entityManager->flush();

        $this->assertNotNull($phoneNumber->getId());

        $entityManager->remove($phoneNumber);
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



        $phoneNumber = new PhoneNumber();
        $phoneNumber->setUser($user);
        $phoneNumber->setPhoneNumber("+336012345678");
        $phoneNumber->setCreationDatetime(new DateTime());
        $entityManager->persist($phoneNumber);
        $entityManager->flush();

        $entityManager->remove($phoneNumber);
        $entityManager->remove($user);
        $entityManager->flush();
        $this->assertNull($phoneNumber->getId());
    }

    public function testModification(){
        $entityManager = Database::getEntityManager();
        $phoneNumberRepository = PhoneNumberRepository::getRepository();

        $user = new User();
        $user->setName("user1");
        $user->setKeyApp("key1");
        $user->setPlatform(1);
        $user->setCreationDatetime(new DateTime());
        $entityManager->persist($user);



        $phoneNumber = new PhoneNumber();
        $phoneNumber->setUser($user);
        $phoneNumber->setPhoneNumber("+336012345678");
        $phoneNumber->setCreationDatetime(new DateTime());
        $entityManager->persist($phoneNumber);
        $entityManager->flush();
        $phoneNumber->setPhoneNumber("+336012345679");
        $entityManager->flush();
        /**
         * @var PhoneNumber $phoneNumber_copy
         */
        $phoneNumber_copy = $phoneNumberRepository->find($phoneNumber->getId());
        $this->assertEquals("+336012345679", $phoneNumber_copy->getPhoneNumber());

        $entityManager->remove($phoneNumber);
        $entityManager->remove($user);
        $entityManager->flush();
    }
}