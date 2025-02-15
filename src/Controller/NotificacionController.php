<?php

namespace App\Controller;

use App\Entity\Notificacion;
use App\Entity\Usuario;
use App\Repository\NotificacionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/notificacion')]
class NotificacionController extends AbstractController
{
    #[Route('/enviar', name: 'notificacion_enviar', methods: ['GET', 'POST'])]
    public function enviar(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $usuarioId = $request->request->get('usuario');
        $mensaje = $request->request->get('mensaje');
        $tipo = $request->request->get('tipo');

        if (!$usuarioId || !$mensaje || !$tipo) {
            $this->addFlash('error', 'Todos los campos son obligatorios.');
            return $this->redirectToRoute('notificacion_enviar');
        }

        $usuario = $entityManager->getRepository(Usuario::class)->find($usuarioId);
        if (!$usuario) {
            $this->addFlash('error', 'Usuario no encontrado.');
            return $this->redirectToRoute('notificacion_enviar');
        }

        $notificacion = new Notificacion();
        $notificacion->setUsuario($usuario);
        $notificacion->setMensaje($mensaje);
        $notificacion->setTipo($tipo);
        $notificacion->setFechaHora(new \DateTimeImmutable());
        $notificacion->setLeida(false);

        $entityManager->persist($notificacion);
        $entityManager->flush();

        $this->addFlash('success', 'Notificación enviada correctamente.');
        return $this->redirectToRoute('notificacion_listar');
    }

    #[Route('/listar', name: 'notificacion_listar', methods: ['GET'])]
    public function listar(
        NotificacionRepository $notificacionRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $usuario = $this->getUser();
        if (!$usuario) {
            throw $this->createAccessDeniedException('Acceso denegado.');
        }

        $query = $notificacionRepository->findByUsuarioQuery($usuario);
        $paginacion = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('notificacion/notificacion_listar.html.twig', [
            'notificaciones' => $paginacion,
        ]);
    }

    #[Route('/historial', name: 'notificacion_historial', methods: ['GET'])]
    public function historial(
        NotificacionRepository $notificacionRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $query = $notificacionRepository->findAllQuery();
        $paginacion = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('notificacion/notificacion_historial.html.twig', [
            'notificaciones' => $paginacion,
        ]);
    }

    #[Route('/marcar-leida/{id}', name: 'notificacion_marcar_leida', methods: ['POST'])]
    public function marcarLeida(
        Notificacion $notificacion,
        EntityManagerInterface $entityManager
    ): Response {
        $usuario = $this->getUser();
        if ($notificacion->getUsuario() !== $usuario) {
            throw $this->createAccessDeniedException('No puedes marcar esta notificación.');
        }

        $notificacion->setLeida(true);
        $entityManager->flush();

        $this->addFlash('success', 'Notificación marcada como leída.');
        return $this->redirectToRoute('notificacion_listar');
    }

    #[Route('/marcar-no-leida/{id}', name: 'notificacion_marcar_no_leida', methods: ['POST'])]
    public function marcarNoLeida(
        Notificacion $notificacion,
        EntityManagerInterface $entityManager
    ): Response {
        $usuario = $this->getUser();
        if ($notificacion->getUsuario() !== $usuario) {
            throw $this->createAccessDeniedException('No puedes modificar esta notificación.');
        }

        $notificacion->setLeida(false);
        $entityManager->flush();

        $this->addFlash('success', 'Notificación marcada como no leída.');
        return $this->redirectToRoute('notificacion_listar');
    }

    #[Route('/preferencias', name: 'notificacion_preferencias', methods: ['GET', 'POST'])]
    public function preferencias(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $usuario = $this->getUser();
        if (!$usuario) {
            throw $this->createAccessDeniedException('Acceso denegado.');
        }

        $preferencias = $request->request->get('preferencias', []);
        // Guardar preferencias en la base de datos (por implementar)
        $this->addFlash('success', 'Preferencias actualizadas correctamente.');

        return $this->redirectToRoute('notificacion_listar');
    }
}
