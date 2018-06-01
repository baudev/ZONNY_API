<?php
/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 01/06/2018
 * Time: 22:03
 */

namespace ZONNY\UnitTest;

require_once dirname(__FILE__) . '/../vendor/autoload.php';
require_once dirname(__FILE__) . '/../config.php';

use DateTime;
use ZONNY\Models\Account\User;
use ZONNY\Models\Helpers\Error;
use PHPUnit\Framework\TestCase;
use ZONNY\Repositories\Chat\StateRepository;
use ZONNY\Repositories\Helpers\ErrorRepository;
use ZONNY\Utils\Database;

class ErrorTest extends TestCase
{

    public function testProcurement(){
        $entityManager = Database::getEntityManager();
        $errorLinkRepository = ErrorRepository::getRepository();

        $user = new User();
        $user->setName("user1");
        $user->setKeyApp("key1");
        $user->setPlatform(1);
        $user->setCreationDatetime(new DateTime());
        $entityManager->persist($user);

        $error = new Error();
        $error->setUser($user);
        $error->setType("error_unknowned");
        $error->setMessage("Long message explaining the error");
        $error->setCode(20);
        $error->setVariables('{ one: 20, second: 30');
        $error->setCreationDatetime(new DateTime());
        $entityManager->persist($error);
        $entityManager->flush();
        /**
         * @var Error $error_copy
         */
        $error_copy = $errorLinkRepository->find($error->getId());
        $this->assertEquals($error->getId(), $error_copy->getId());

        $entityManager->remove($error);
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

        $error = new Error();
        $error->setUser($user);
        $error->setType("error_unknowned");
        $error->setMessage("Long message explaining the error");
        $error->setCode(20);
        $error->setVariables('{ one: 20, second: 30');
        $error->setCreationDatetime(new DateTime());
        $entityManager->persist($error);
        $entityManager->flush();

        $this->assertNotNull($error->getId());

        $entityManager->remove($error);
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

        $error = new Error();
        $error->setUser($user);
        $error->setType("error_unknowned");
        $error->setMessage("Long message explaining the error");
        $error->setCode(20);
        $error->setVariables('{ one: 20, second: 30');
        $error->setCreationDatetime(new DateTime());
        $entityManager->persist($error);
        $entityManager->flush();

        $entityManager->remove($error);
        $entityManager->remove($user);
        $entityManager->flush();
        $this->assertNull($error->getId());
    }

    public function testModification(){
        $entityManager = Database::getEntityManager();
        $errorLinkRepository = ErrorRepository::getRepository();

        $user = new User();
        $user->setName("user1");
        $user->setKeyApp("key1");
        $user->setPlatform(1);
        $user->setCreationDatetime(new DateTime());
        $entityManager->persist($user);

        $error = new Error();
        $error->setUser($user);
        $error->setType("error_unknowned");
        $error->setMessage("Long message explaining the error");
        $error->setCode(20);
        $error->setVariables('{ one: 20, second: 30');
        $error->setCreationDatetime(new DateTime());
        $entityManager->persist($error);
        $entityManager->flush();
        $error->setCode(40);
        $entityManager->flush();
        /**
         * @var Error $error_copy
         */
        $error_copy = $errorLinkRepository->find($error->getId());
        $this->assertEquals(40, $error_copy->getCode());

        $entityManager->remove($error);
        $entityManager->remove($user);
        $entityManager->flush();
    }

}
