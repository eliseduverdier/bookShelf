<?php

namespace App\Services\Security;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\Service\Attribute\Required;

class UserService
{
    #[Required]
    public Security $security;
    #[Required]
    public UserRepository $userRepository;
    #[Required]
    public UserPasswordHasherInterface $passwordHasher;
    #[Required]
    public EntityManagerInterface $em;

    public function login(string $username, string $plaintextPassword)
    {
        $user = $this->userRepository->findOneBy(['username' => $username]);

        if (!$user) {
            throw new \Exception('Login error !');
        }

        if (!$this->passwordHasher->isPasswordValid($user, $plaintextPassword)) {
            throw new \Exception('Login error !');
        }

        try {
            $this->security->login($user, 'form_login');
            $user->lastConnectedAt = new \DateTime();
            $this->em->persist($user);
            $this->em->flush();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
