<?php

namespace App\Controller\User;

use App\Services\Security\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'login', methods: ['GET'])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('user/login.html.twig', [
            'lastUsername' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ]);
    }

    #[Route('/login', name: 'log_user', methods: ['POST'])]
    public function logUser(
        Request $request, UserService $userService
    ): Response
    {
        try {
            $userService->login(
                $request->request->get('_username'),
                $request->request->get('_password')
            );
            return $this->redirect('/');
        } catch (\Exception $e) {
            return $this->render('user/login.html.twig', [
                'lastUsername' => $request->request->get('_username'),
                'error' => $e->getMessage(),
            ]);
        }
    }

    #[Route('/logout', name: 'logout', methods: ['GET'])]
    public function logoutUser(Security $security): Response
    {
        $security->logout(false);
        return $this->redirect('/');
    }
}
