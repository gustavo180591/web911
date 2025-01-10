<?php

namespace App\Controller;

use App\Entity\Usuario;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/auth')]
class AuthController extends AbstractController
{
    private const MAX_LOGIN_ATTEMPTS = 5;

    #[Route('/login', name: 'auth_login', methods: ['GET', 'POST'])]
    public function login(
        Request $request,
        AuthenticationUtils $authenticationUtils,
        EntityManagerInterface $entityManager
    ): Response {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        if ($request->isMethod('POST')) {
            $usuario = $entityManager->getRepository(Usuario::class)
                ->findOneBy(['email' => $request->get('email')]);

            if ($usuario) {
                if ($usuario->getFailedAttempts() >= self::MAX_LOGIN_ATTEMPTS) {
                    return new JsonResponse([
                        'error' => 'Tu cuenta está bloqueada debido a múltiples intentos fallidos.',
                    ], Response::HTTP_FORBIDDEN);
                }
            }
        }

        return $this->render('auth/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/logout', name: 'auth_logout', methods: ['POST'])]
    public function logout(): void
    {
        throw new \LogicException('El logout será configurado por el firewall de Symfony.');
    }

    #[Route('/forgot-password', name: 'auth_forgot_password', methods: ['GET', 'POST'])]
    public function forgotPassword(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $usuario = $entityManager->getRepository(Usuario::class)
                ->findOneBy(['email' => $email]);

            if (!$usuario) {
                return new JsonResponse(['error' => 'Usuario no encontrado.'], Response::HTTP_NOT_FOUND);
            }

            // Generar token y enviarlo por correo (pendiente de implementación)
            $this->addFlash('success', 'Se ha enviado un enlace de recuperación de contraseña a tu email.');
        }

        return $this->render('auth/forgot_password.html.twig');
    }

    #[Route('/reset-password/{token}', name: 'auth_reset_password', methods: ['GET', 'POST'])]
    public function resetPassword(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        string $token
    ): Response {
        $usuario = $entityManager->getRepository(Usuario::class)
            ->findOneBy(['resetToken' => $token]);

        if (!$usuario) {
            return new JsonResponse(['error' => 'Token inválido o expirado.'], Response::HTTP_BAD_REQUEST);
        }

        if ($request->isMethod('POST')) {
            $newPassword = $request->request->get('password');
            $hashedPassword = $passwordHasher->hashPassword($usuario, $newPassword);

            $usuario->setPassword($hashedPassword);
            $usuario->setResetToken(null); // Eliminar el token tras el uso
            $entityManager->flush();

            $this->addFlash('success', 'Contraseña restablecida correctamente.');
            return $this->redirectToRoute('auth_login');
        }

        return $this->render('auth/reset_password.html.twig', [
            'token' => $token,
        ]);
    }

    #[Route('/record-login-attempt', name: 'auth_record_login_attempt', methods: ['POST'])]
    public function recordLoginAttempt(
        Request $request,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $email = $request->request->get('email');
        $usuario = $entityManager->getRepository(Usuario::class)->findOneBy(['email' => $email]);

        if ($usuario) {
            $usuario->setFailedAttempts($usuario->getFailedAttempts() + 1);
            if ($usuario->getFailedAttempts() >= self::MAX_LOGIN_ATTEMPTS) {
                $usuario->setLocked(true);
            }
            $entityManager->flush();
        }

        return new JsonResponse(['status' => 'Intento registrado.'], Response::HTTP_OK);
    }
}
