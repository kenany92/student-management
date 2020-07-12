<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class IndexController extends AbstractController
{
    /**
     * @Route("/index", name="index")
     */
    public function index()
    {
        $auth_checker = $this->get('security.authorization_checker');
        $token = $this->get('security.token_storage')->getToken();
        $user = $token->getUser();
        if($auth_checker && $user != "anon."){
            return $this->render('index/index.html.twig', [
                'controller_name' => 'IndexController',
            ]);
        }
        else{
            return $this->redirectToRoute("app_login");
        }
    }
}
