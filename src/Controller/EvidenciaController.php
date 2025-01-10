<?php

namespace App\Controller;

use App\Entity\Denuncia;
use App\Entity\Evidencia;
use App\Form\EvidenciaType;
use App\Repository\EvidenciaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/evidencia')]
class EvidenciaController extends AbstractController
{
    #[Route('/subir/{id}', name: 'evidencia_subir', methods: ['GET', 'POST'])]
    public function subir(
        Denuncia $denuncia,
        Request $request,
        EntityManagerInterface $entityManager,
        string $uploadDir
    ): Response {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if ($denuncia->getEstado() === 'resuelto') {
            $this->addFlash('error', 'No se pueden agregar evidencias a una denuncia resuelta.');
            return $this->redirectToRoute('denuncia_listar');
        }

        $evidencia = new Evidencia();
        $form = $this->createForm(EvidenciaType::class, $evidencia);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $archivo */
            $archivo = $form->get('archivo')->getData();

            if ($archivo) {
                $nombreArchivo = uniqid() . '.' . $archivo->guessExtension();
                try {
                    $archivo->move($uploadDir, $nombreArchivo);
                    $evidencia->setArchivo($nombreArchivo);
                    $evidencia->setDenuncia($denuncia);

                    $entityManager->persist($evidencia);
                    $entityManager->flush();

                    $this->addFlash('success', 'Evidencia subida correctamente.');
                    return $this->redirectToRoute('denuncia_listar');
                } catch (FileException $e) {
                    $this->addFlash('error', 'Error al subir el archivo.');
                }
            }
        }

        return $this->render('evidencia/subir.html.twig', [
            'form' => $form->createView(),
            'denuncia' => $denuncia,
        ]);
    }

    #[Route('/listar/{id}', name: 'evidencia_listar', methods: ['GET'])]
    public function listar(Denuncia $denuncia): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $evidencias = $denuncia->getEvidencias();

        return $this->render('evidencia/listar.html.twig', [
            'evidencias' => $evidencias,
            'denuncia' => $denuncia,
        ]);
    }

    #[Route('/descargar/{id}', name: 'evidencia_descargar', methods: ['GET'])]
    public function descargar(Evidencia $evidencia, string $uploadDir): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $rutaArchivo = $uploadDir . '/' . $evidencia->getArchivo();
        if (!file_exists($rutaArchivo)) {
            $this->addFlash('error', 'El archivo no existe.');
            return $this->redirectToRoute('denuncia_listar');
        }

        return $this->file($rutaArchivo);
    }

    #[Route('/eliminar/{id}', name: 'evidencia_eliminar', methods: ['POST'])]
    public function eliminar(
        Evidencia $evidencia,
        EntityManagerInterface $entityManager,
        string $uploadDir
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $denuncia = $evidencia->getDenuncia();
        if ($denuncia->getEstado() === 'resuelto') {
            $this->addFlash('error', 'No se pueden eliminar evidencias de una denuncia resuelta.');
            return $this->redirectToRoute('denuncia_listar_todas');
        }

        $rutaArchivo = $uploadDir . '/' . $evidencia->getArchivo();
        if (file_exists($rutaArchivo)) {
            unlink($rutaArchivo);
        }

        $entityManager->remove($evidencia);
        $entityManager->flush();

        $this->addFlash('success', 'Evidencia eliminada correctamente.');
        return $this->redirectToRoute('denuncia_listar_todas');
    }
}
