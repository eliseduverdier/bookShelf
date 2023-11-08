<?php

namespace App\Tests\Repository;

use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;

class UserRepositoryTest extends TestCase
{
    protected UserRepository $userRepository;

    protected function setUp(): void
    {
        // todo setup database for tests life in front-v2
        $registry = $this->createMock(ManagerRegistry::class);
        $this->userRepository = new UserRepository($registry);
    }

}
