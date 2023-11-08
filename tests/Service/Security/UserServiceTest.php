<?php

namespace App\Tests\Service\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Security\UserService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Contracts\Service\Attribute\Required;

class UserServiceTest extends TestCase
{
    protected UserService $userService;
    protected UserRepository $userRepository;
    protected UserPasswordHasher $passwordHasher;
    protected Security $security;
    protected EntityManagerInterface $em;

    protected function setUp(): void
    {
        $this->userService = new UserService();

        $this->userRepository = $this->createMock(UserRepository::class);
        $this->userService->userRepository = $this->userRepository;

        $this->passwordHasher = $this->createMock(UserPasswordHasher::class);
        $this->userService->passwordHasher = $this->passwordHasher;

        $this->security = $this->createMock(Security::class);
        $this->userService->security = $this->security;

        $this->em = $this->createMock(EntityManagerInterface::class);
        $this->userService->em = $this->em;
    }

    public function testLoginNoUser()
    {
        $this->userRepository->method('findOneBy')->willReturn(null);
        $this->userService->userRepository = $this->userRepository;

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Login error');
        $this->userService->login('unknown', 'password');
    }

    public function testLoginInvalidPassword()
    {
        $this->userRepository->method('findOneBy')->willReturn(new User('user'));
        $this->passwordHasher->method('isPasswordValid')->willReturn(false);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Login error');
        $this->userService->login('unknown', 'password');
    }

    public function testLoginEntityManagerError()
    {
        $this->userRepository->method('findOneBy')->willReturn(new User('user'));
        $this->passwordHasher->method('isPasswordValid')->willReturn(true);

        $this->em->method('persist')->willThrowException(new \Exception('entity manager error'));
        $this->userService->em = $this->em;

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('entity manager error');
        $this->userService->login('unknown', 'password');
    }

    public function testLoginSecurityError()
    {
        $this->userRepository->method('findOneBy')->willReturn(new User('user'));
        $this->passwordHasher->method('isPasswordValid')->willReturn(true);

        $this->security->method('login')->willThrowException(new \Exception('Login method error'));
        $this->userService->security = $this->security;

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Login method error');
        $this->userService->login('unknown', 'password');
    }

    public function testLoginOK()
    {
        $this->userRepository->method('findOneBy')->willReturn(new User('user'));
        $this->passwordHasher->method('isPasswordValid')->willReturn(true);

        $this->expectNotToPerformAssertions(); // no returns, test simply no error
        $this->userService->login('unknown', 'password');
    }
}
