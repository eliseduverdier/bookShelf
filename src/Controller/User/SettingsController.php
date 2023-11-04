<?php

namespace App\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SettingsController extends AbstractController
{

    #[Route('/settings', name: 'settings', methods: ['GET'])]
    public function signup(Request $request): Response
    {
        return $this->render('user/settings.html.twig', [
            'currentUser' => $this->getUser(),
        ]);
    }
}
