<?php

namespace App\Controller;

use App\Entity\Usuario;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route('/auth')]
class AuthController extends AbstractController
{
    #[Route('/login', name: 'auth_login', methods: ['GET', 'POST'])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('auth/auth_login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/logout', name: 'auth_logout', methods: ['GET'])]
    public function logout(): void
    {
        throw new \LogicException('This method will be intercepted by the logout key on your firewall.');
    }

    #[Route('/forgot-password', name: 'auth_forgot_password', methods: ['GET', 'POST'])]
    public function forgotPassword(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $usuario = $entityManager->getRepository(Usuario::class)->findOneBy(['email' => $email]);

            if (!$usuario) {
                $this->addFlash('error', 'Usuario no encontrado.');
                return $this->redirectToRoute('auth_forgot_password');
            }

            $this->addFlash('success', 'Correo de recuperación enviado.');
        }

        return $this->render('auth/auth_forgot_password.html.twig');
    }

    #[Route('/reset-password/{token}', name: 'auth_reset_password', methods: ['GET', 'POST'])]
    public function resetPassword(
        string $token,
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $usuario = $entityManager->getRepository(Usuario::class)->findOneBy(['resetToken' => $token]);

        if (!$usuario) {
            $this->addFlash('error', 'Token inválido o expirado.');
            return $this->redirectToRoute('auth_forgot_password');
        }

        if ($request->isMethod('POST')) {
            $newPassword = $request->request->get('password');
            $usuario->setPassword($passwordHasher->hashPassword($usuario, $newPassword));
            $usuario->setResetToken(null);
            $entityManager->flush();

            $this->addFlash('success', 'Contraseña actualizada con éxito.');
            return $this->redirectToRoute('auth_login');
        }

        return $this->render('auth/auth_reset_password.html.twig', ['token' => $token]);
    }

    #[Route('/bloqueo', name: 'auth_bloqueo', methods: ['GET'])]
    public function bloqueo(): Response
    {
        return $this->render('auth/auth_bloqueo.html.twig');
    }
}
