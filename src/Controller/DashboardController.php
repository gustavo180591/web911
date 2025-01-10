<?php

namespace App\Controller;

use App\Repository\DenunciaRepository;
use App\Repository\ReporteEstadisticoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dashboard')]
class DashboardController extends AbstractController
{
    #[Route('/', name: 'dashboard_index', methods: ['GET'])]
    public function index(
        DenunciaRepository $denunciaRepository,
        ReporteEstadisticoRepository $reporteEstadisticoRepository
    ): Response {
        // Métricas clave
        $totalDenuncias = $denunciaRepository->count([]);
        $denunciasPorEstado = $denunciaRepository->getCountByEstado();
        $categoriasDenuncias = $denunciaRepository->getCountByCategoria();

        // Denuncias urgentes y sin autoridad
        $denunciasUrgentes = $denunciaRepository->findUrgentes();
        $denunciasSinAutoridad = $denunciaRepository->findWithoutAuthority();

        // Reportes estadísticos recientes
        $reportes = $reporteEstadisticoRepository->findRecent();

        return $this->render('dashboard/index.html.twig', [
            'total_denuncias' => $totalDenuncias,
            'denuncias_por_estado' => $denunciasPorEstado,
            'categorias_denuncias' => $categoriasDenuncias,
            'denuncias_urgentes' => $denunciasUrgentes,
            'denuncias_sin_autoridad' => $denunciasSinAutoridad,
            'reportes' => $reportes,
        ]);
    }

    #[Route('/detalles/{categoria}', name: 'dashboard_detalles_categoria', methods: ['GET'])]
    public function detallesPorCategoria(
        string $categoria,
        DenunciaRepository $denunciaRepository
    ): Response {
        $denuncias = $denunciaRepository->findByCategoria($categoria);

        return $this->render('dashboard/detalles_categoria.html.twig', [
            'categoria' => $categoria,
            'denuncias' => $denuncias,
        ]);
    }
}
