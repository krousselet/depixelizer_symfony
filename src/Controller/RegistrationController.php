<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Service\JWTService;
use App\Service\SendEmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager , JWTService $jwt, SendEmailService $mail): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setEmail($form->get('email')->getData());
            $user->setAgreeTerms($form->get('agreeTerms')->getData());
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            //TOKEN GENERATION
            //HEADER CREATION
            $header = [
                'typ' => 'JWT',
                'alg' => 'HS256'
            ];

            // CONTENT CREATION (PAYLOAD)
            $payload = [
                'user_id' => $user->getId(),
            ];
            //TOKEN GENERATION
            $token = $jwt->encoding($header, $payload, $this->getParameter('app.jwtsecret'));
            //EMAIL SENDING

            $mail->send(
                $_ENV['APP_ADMIN_EMAIL'],
                $user->getEmail(),
                'Activation de votre compte sur le site Depixelizer',
                'register',
                compact('user', 'token')
            );
            return $this->redirectToRoute('app_home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
    #[Route('/verif/{token}', name: 'app_verify_user')]
    public function verifUser($token,
                              JWTService $jwt,
                              UserRepository $userRepository,
                              EntityManagerInterface $em): Response
    {
        // Check token validity and signature
        if ($jwt->isValid($token) && !$jwt->isExpired($token) && $jwt->check($token, $this->getParameter('app.jwtsecret'))) {
            $payload = $jwt->getPayload($token);
            $user = $userRepository->find($payload['user_id']);
            if ($user && !$user->isVerified()) {
                $user->setVerified(true);
                $em->flush();
                $this->addFlash('success', 'L\'utilisateur est vérifié !');
                return $this->redirectToRoute('app_home');
            } else {
                // This block handles the case where the user is already verified or does not exist
                $this->addFlash('danger', 'L\'utilisateur est déjà vérifié ou n\'existe pas.');
                return $this->redirectToRoute('app_login');
            }
        } else {
            // Handles invalid or expired tokens
            $this->addFlash('danger', 'Le token est invalide ou a expiré');
            return $this->redirectToRoute('app_login');
        }

        // Fallback return (this should theoretically never be reached due to the above returns)
        return $this->redirectToRoute('app_login');
    }
}
