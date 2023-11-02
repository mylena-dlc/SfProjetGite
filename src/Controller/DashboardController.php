<?php

namespace App\Controller;

use App\Repository\GiteRepository;
use App\Repository\PeriodRepository;
use App\Repository\PictureRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardController extends AbstractController
{

    /**
     * @var GiteRepository
     */
    private $giteRepository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var PictureRepository
     */
    private $pictureRepository;

    /**
     * @var PeriodRepository
     */
    private $periodRepository;

    /**
     * @var ReservationRepository
     */
    private $reservationRepository;



    public function __construct(GiteRepository $giteRepository, EntityManagerInterface $em, PictureRepository $pictureRepository, ReservationRepository $reservationRepository, PeriodRepository $periodRepository)
    {
        $this->giteRepository = $giteRepository;
        $this->em = $em;
        $this->pictureRepository = $pictureRepository;
        $this->reservationRepository = $reservationRepository;
    }

    #[Route('admin/dashboard', name: 'app_dashboard')]
    public function index(): Response

    {
        // Recherche des 5 réservations les plus récentes pour l'index du dashboard
        $reservations = $this->reservationRepository->findBy([], ['reservationDate' => 'ASC'], 5);
        return $this->render('dashboard/index.html.twig', [
            'reservations' => $reservations,
        ]);
    }

    // #[Route('/dashboard', name: 'app_dashboard')]
    // public function index(): Response
    // {
    //     return $this->render('dashboard/index.html.twig', [
    //         'controller_name' => 'DashboardController',
    //     ]);
    // }
}
