<?php

namespace App\Controller;

use App\Repository\GiteRepository;
use App\Repository\PeriodRepository;
use App\Repository\PictureRepository;
use Doctrine\ORM\EntityManagerInterface;
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


    public function __construct(GiteRepository $giteRepository, EntityManagerInterface $em, PictureRepository $pictureRepository, PeriodRepository $periodRepository)
    {
        $this->giteRepository = $giteRepository;
        $this->em = $em;
        $this->pictureRepository = $pictureRepository;
        $this->periodRepository = $periodRepository;
    }

    #[Route('admin/dashboard', name: 'app_dashboard')]
    public function index(): Response

    {

        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
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
