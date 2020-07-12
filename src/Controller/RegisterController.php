<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\User;

class RegisterController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passEncoder)
    {
        $form = $this->createFormBuilder()
        ->add("username")
        ->add("password", RepeatedType::class, [
             'type' => PasswordType::class,
             'required' => true,
             'first_options' =>['label' => 'Password'],
             'second_options' => ['label' => 'Confirm Password']
        ])
        ->add("register", SubmitType::class, [
            'attr' => [
                'class' => "btn btn-success"
            ]
        ])
        ->getForm();


        $form->handleRequest($request);
        if($form->isSubmitted()){
            $data = $form->getData();
            $user = new User();
            $user->setUsername($data['username']);
            $user->setPassword(
                $passEncoder->encodePassword($user, $data['password'])
            );

            $en = $this->getDoctrine()->getManager();
            $en->persist($user);
            $en->flush();

            return $this->redirect($this->generateUrl('login'));
        }


        return $this->render('register/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
