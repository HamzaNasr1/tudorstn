<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class LoginController extends AbstractController
{
    #[Route('/', name: 'app_login')]
    public function index(SessionInterface $session): Response
    {
        /*if (!$session->has('login') || $session->get('login') === 1) {
            return $this->redirectToRoute('app_produit_index');
        }*/
        
        return $this->render('login/index.html.twig', [
            'controller_name' => 'LoginController',
        ]);
    }
    #[Route('/logout', name: 'logout')]
    public function logout(SessionInterface $session): Response
    {
        $session->set('login', 0);
        return $this->redirectToRoute('app_login');
    }
    #[Route('/login', name: 'login', methods: ['GET', 'POST'])]
    public function login(Request $request, SessionInterface $session): Response
    {
      /*   if ( $session->get('login') === 1) {
            return $this->redirectToRoute('app_produit_index');
        }
        if ($request->isMethod('POST')) {
            $username = $request->request->get('identifiant');
            $password = $request->request->get('password');

            if ($username === 'admin' && $password === 'admin') { */
                $session->set('login', 1);
                return $this->redirectToRoute('app_produit_index');
            /*} else {
                $session->set('login', 0);
                $this->addFlash('error', 'Identifiant ou mot de passe incorrect');
            }
        
        }

        return $this->render('login/index.html.twig');*/
    }
}
