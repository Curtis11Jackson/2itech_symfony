<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use App\Form\LoginFormType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/register', name: 'register')]
    public function index(Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();

        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $doctrine->getManager();

            $user = $form->getData();
            $password = $user->getPassword();
            
            $hashedPassword = $passwordHasher->hashPassword($user, $password);
            $user->setPassword($hashedPassword);

            $entityManager->persist($user);

            $entityManager->flush();

            return $this->redirectToRoute('login');

        }

        return $this->render('security/index.html.twig', [
            'controller_name' => 'Register',
            'form' => $form->createView()
        ]);
    }

    #[Route('/login', name: 'login')]
    public function login(Request $request, ManagerRegistry $doctrine): Response
    {
        
        $user = new User();

        $loginForm = $this->createForm(LoginFormType::class, $user);
    
        return $this->render('security/login.html.twig', [
            'controller_name' => 'Register',
            'loginForm' => $loginForm->createView()
        ]);
    }

}
