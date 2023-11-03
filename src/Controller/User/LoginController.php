<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'login', methods: ['GET'])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('login/login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ]);
    }

    #[Route('/login', name: 'log_user', methods: ['POST'])]
    public function logUser(
        Request $request,
        Security $security,
        UserRepository $userRepository,
        AuthenticationUtils $authenticationUtils
    ): Response
    {
        $username = $request->request->get('_username');
        $user = $userRepository->findOneBy(['username' => $username]);
        if (!$user) {
            return $this->render('login/login.html.twig', [
                'last_username' => $authenticationUtils->getLastUsername(),
                'error' => "Username « $username » doesn’t exists",
            ]);
        }
        $security->login($user, 'form_login');


        return $this->redirect('/');
    }
}
