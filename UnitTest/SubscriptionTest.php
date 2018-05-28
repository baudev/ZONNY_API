<?php
/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 28/05/2018
 * Time: 23:08
 */

namespace ZONNY\UnitTest;

require_once dirname(__FILE__) . '/../vendor/autoload.php';
require_once dirname(__FILE__) . '/../config.php';

use DateTime;
use ZONNY\Models\Account\Subscription;
use PHPUnit\Framework\TestCase;
use ZONNY\Models\Account\User;
use ZONNY\Repositories\Account\SubscriptionRepository;
use ZONNY\Utils\Database;

class SubscriptionTest extends TestCase
{

    public function testProcurement(){
        $entityManager = Database::getEntityManager();
        $reportRepository = SubscriptionRepository::getRepository();

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

        $subscription = new Subscription();
        $subscription->setUser($user);
        $subscription->setFollowed($user2);
        $subscription->setCreationDatetime(new DateTime());

        $entityManager->persist($subscription);
        $entityManager->flush();
        /**
         * @var Subscription $subscription_copy
         */
        $subscription_copy = $reportRepository->find($subscription->getId());
        $this->assertEquals($subscription->getId(), $subscription_copy->getId());

        $entityManager->remove($subscription);
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

        $subscription = new Subscription();
        $subscription->setUser($user);
        $subscription->setFollowed($user2);
        $subscription->setCreationDatetime(new DateTime());

        $entityManager->persist($subscription);
        $entityManager->flush();

        $this->assertNotNull($subscription->getId());

        $entityManager->remove($subscription);
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

        $subscription = new Subscription();
        $subscription->setUser($user);
        $subscription->setFollowed($user2);
        $subscription->setCreationDatetime(new DateTime());

        $entityManager->persist($subscription);
        $entityManager->flush();

        $entityManager->remove($subscription);
        $entityManager->remove($user);
        $entityManager->remove($user2);
        $entityManager->flush();
        $this->assertNull($subscription->getId());
    }

    public function testModification(){
        $entityManager = Database::getEntityManager();
        $reportRepository = SubscriptionRepository::getRepository();

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

        $subscription = new Subscription();
        $subscription->setUser($user);
        $subscription->setFollowed($user2);
        $subscription->setCreationDatetime(new DateTime());

        $entityManager->persist($subscription);
        $entityManager->flush();
        $subscription->setFollowed($user);
        $entityManager->flush();
        /**
         * @var Subscription $subscription_copy
         */
        $subscription_copy = $reportRepository->find($subscription->getId());
        $this->assertEquals($user->getId(), $subscription_copy->getFollowed()->getId());

        $entityManager->remove($subscription);
        $entityManager->remove($user);
        $entityManager->remove($user2);
        $entityManager->flush();
    }


}
