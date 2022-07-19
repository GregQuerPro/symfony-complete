<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/todo')]
class FirstController extends AbstractController
{
    #[Route('/', name: 'todo')]
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

    #[Route(
        '/add/{name}/{content}',
        name: 'todo.add',
        defaults: ['content' => 'sf6']
    )]
    public function addTodo(SessionInterface $session, $name, $content): RedirectResponse {
        $this->editTodo($session, 'add', $name, $content);
        return $this->redirectToRoute('todo');
    }

    #[Route('/update/{name}/{content}', name: 'todo.update')]
    public function updateTodo(SessionInterface $session, $name, $content): RedirectResponse {
        $this->editTodo($session, 'update', $name, $content);
        return $this->redirectToRoute('todo');
    }

    #[Route('/delete/{name}', name: 'todo.delete')]
    public function deleteTodo(SessionInterface $session, $name): RedirectResponse {
        $this->editTodo($session, 'delete', $name);
        return $this->redirectToRoute('todo');
    }

    #[Route('/reset', name: 'todo.reset')]
    public function resetTodo(SessionInterface $session): RedirectResponse {
        if ($session->has('todos')) {
            $session->remove('todos');
            $this->addFlash('success', "La liste des todos a été réinitialisée");
        } else {
            $this->addFlash('errors', "La liste des todos n'existe pas dans la session");
        }
        return $this->redirectToRoute('todo');
    }

    private function editTodo(SessionInterface $session, string $type, string $name, ?string $content = null ): void {
        $message = $this->getFlashMessage($type);
        if ($session->has('todos')) {
            $todos = $session->get('todos');
            if(!isset($todos[$name]) && $type !== 'add'){
                $this->addFlash('errors', "Ce todo n'existe pas et ne peut donc pas être modifié");
            } else {
                if($type !== 'delete') {
                    $todos[$name] = $content;
                    if($type === 'add') {
                        $this->addFlash('errors', "Ce todo existe déjà");
                        $addError = true;
                    }
                } else {
                    unset($todos[$name]);
                }
                $session->set('todos', $todos);
                if (!isset($addError)){
                   $this->addFlash('success', $message);
                }
            }
        } else {
            $this->addFlash('errors', "La liste des todos n'est pas encore initialisée");
        }

    }

    private function getFlashMessage(string $type): string {
        $message = '';
        if($type === 'add') {
            $message = 'Le nouveau todo a été ajouté avec succès';
        } elseif($type === 'update') {
            $message = 'Le nouveau todo a été modifié avec succès';
        } elseif($type === 'delete') {
            $message = 'Le nouveau todo a été supprimé avec succès';
        }
        return $message;
    }



}
