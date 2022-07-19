<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class FirstController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(SessionInterface $session): Response
    {

        $todos = [
            'achat' => 'acheter clé usb',
            'cours' => 'Finaliser mon cours',
            'correction' => 'corriger mes examems'
        ];
        if(!$session->has('todos')) {
            $session->set('todos', $todos);
        }

        return $this->render('first/index.html.twig');
    }
}
