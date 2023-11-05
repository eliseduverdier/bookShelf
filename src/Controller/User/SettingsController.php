<?php

namespace App\Controller\User;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Service\Attribute\Required;

class SettingsController extends AbstractController
{
    #[Required]
    public UserRepository $userRepository;

    #[Route('/settings', name: 'settings')]
    public function index(Request $request): Response
    {
        return $this->render('user/settings.html.twig', [
            'currentUser' => $this->getUser(),
        ]);
    }

    #[Route('/settings/color', name: 'settings_color', methods: ['POST'])]
    public function color(Request $request): Response
    {
        $this->userRepository->changeFavColor(
            $this->getUser(),
            $request->request->get('color')
        );
        return $this->redirect('/settings');
    }
}
