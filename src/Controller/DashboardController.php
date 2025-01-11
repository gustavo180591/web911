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
        // Obtén métricas clave
        $totalDenuncias = $denunciaRepository->count([]);
        $denunciasPorEstado = $denunciaRepository->getCountByEstado();
        $categoriasDenuncias = $denunciaRepository->getCountByCategoria();
        $tendenciasMensuales = $denunciaRepository->getTendenciasMensuales();

        // Denuncias urgentes y sin autoridad
        $denunciasUrgentes = $denunciaRepository->findUrgentes();
        $denunciasSinAutoridad = $denunciaRepository->findWithoutAuthority();

        // Reportes estadísticos recientes
        $reportes = $reporteEstadisticoRepository->findRecent();

        return $this->render('dashboard/dashboard_index.html.twig', [
            'total_denuncias' => $totalDenuncias,
            'denuncias_por_estado' => $denunciasPorEstado,
            'categorias_denuncias' => $categoriasDenuncias,
            'denuncias_urgentes' => $denunciasUrgentes,
            'denuncias_sin_autoridad' => $denunciasSinAutoridad,
            'reportes' => $reportes,
            'tendencias_mensuales' => $tendenciasMensuales,
        ]);
    }

    #[Route('/detalles/{categoria}', name: 'dashboard_detalles_categoria', methods: ['GET'])]
    public function detallesPorCategoria(
        string $categoria,
        DenunciaRepository $denunciaRepository
    ): Response {
        $denuncias = $denunciaRepository->findByCategoria($categoria);
        $tendenciasPorZona = $denunciaRepository->getTendenciasPorZona($categoria);

        return $this->render('dashboard/dashboard_detalles_categoria.html.twig', [
            'categoria' => $categoria,
            'denuncias' => $denuncias,
            'tendencias_por_zona' => $tendenciasPorZona,
        ]);
    }

    #[Route('/estadisticas', name: 'dashboard_estadisticas', methods: ['GET'])]
    public function estadisticas(
        DenunciaRepository $denunciaRepository
    ): Response {
        $denunciasPorFecha = $denunciaRepository->getDenunciasPorFecha();
        $denunciasPorUbicacion = $denunciaRepository->getDenunciasPorUbicacion();

        return $this->render('dashboard/dashboard_estadisticas.html.twig', [
            'denuncias_por_fecha' => $denunciasPorFecha,
            'denuncias_por_ubicacion' => $denunciasPorUbicacion,
        ]);
    }
}
