<?php

namespace App\Controller;

use App\Entity\Autoridad;
use App\Entity\Denuncia;
use App\Form\AutoridadType;
use App\Repository\AutoridadRepository;
use App\Repository\DenunciaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/autoridad')]
class AutoridadController extends AbstractController
{
    #[Route('/crear', name: 'autoridad_crear', methods: ['GET', 'POST'])]
    public function crear(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $autoridad = new Autoridad();
        $form = $this->createForm(AutoridadType::class, $autoridad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $autoridad->setActivo(true);
            $entityManager->persist($autoridad);
            $entityManager->flush();

            $this->addFlash('success', 'Autoridad creada correctamente.');
            return $this->redirectToRoute('autoridad_listar');
        }

        return $this->render('autoridad/crear.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/listar', name: 'autoridad_listar', methods: ['GET'])]
    public function listar(AutoridadRepository $autoridadRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('autoridad/listar.html.twig', [
            'autoridades' => $autoridadRepository->findAll(),
        ]);
    }

    #[Route('/editar/{id}', name: 'autoridad_editar', methods: ['GET', 'POST'])]
    public function editar(
        Autoridad $autoridad,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(AutoridadType::class, $autoridad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Datos de la autoridad actualizados correctamente.');
            return $this->redirectToRoute('autoridad_listar');
        }

        return $this->render('autoridad/editar.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/activar/{id}', name: 'autoridad_activar', methods: ['POST'])]
    public function activar(
        Autoridad $autoridad,
        EntityManagerInterface $entityManager
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $autoridad->setActivo(true);
        $entityManager->flush();

        $this->addFlash('success', 'Autoridad activada correctamente.');
        return $this->redirectToRoute('autoridad_listar');
    }

    #[Route('/desactivar/{id}', name: 'autoridad_desactivar', methods: ['POST'])]
    public function desactivar(
        Autoridad $autoridad,
        EntityManagerInterface $entityManager
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $autoridad->setActivo(false);
        $entityManager->flush();

        $this->addFlash('success', 'Autoridad desactivada correctamente.');
        return $this->redirectToRoute('autoridad_listar');
    }

    #[Route('/asociar/{id}', name: 'autoridad_asociar', methods: ['GET', 'POST'])]
    public function asociar(
        Autoridad $autoridad,
        DenunciaRepository $denunciaRepository,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $denunciaId = $request->request->get('denuncia_id');
        $denuncia = $denunciaRepository->find($denunciaId);

        if (!$denuncia) {
            $this->addFlash('error', 'Denuncia no encontrada.');
            return $this->redirectToRoute('autoridad_listar');
        }

        $autoridad->addDenuncia($denuncia);
        $entityManager->flush();

        $this->addFlash('success', 'Denuncia asociada a la autoridad correctamente.');
        return $this->redirectToRoute('autoridad_listar');
    }

    #[Route('/denuncias/{id}', name: 'autoridad_denuncias', methods: ['GET'])]
    public function denuncias(Autoridad $autoridad): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('autoridad/denuncias.html.twig', [
            'autoridad' => $autoridad,
            'denuncias' => $autoridad->getDenuncias(),
        ]);
    }

    #[Route('/eliminar/{id}', name: 'autoridad_eliminar', methods: ['POST'])]
    public function eliminar(
        Autoridad $autoridad,
        EntityManagerInterface $entityManager
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($autoridad->getDenuncias()->count() > 0) {
            $this->addFlash('error', 'No se puede eliminar una autoridad con denuncias activas.');
            return $this->redirectToRoute('autoridad_listar');
        }

        $entityManager->remove($autoridad);
        $entityManager->flush();

        $this->addFlash('success', 'Autoridad eliminada correctamente.');
        return $this->redirectToRoute('autoridad_listar');
    }
}
