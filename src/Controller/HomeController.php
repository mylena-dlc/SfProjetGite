<?php

namespace App\Controller;

use App\Repository\PictureRepository;
use App\Repository\CalendarRepository;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{

    /**
     * @var PictureRepository
     */
    private $pictureRepository;

    /**
     * @var ReservationRepository
     */
    private $reservationRepository;
    
    public function __construct(PictureRepository $pictureRepository, ReservationRepository $reservationRepository)
    {
        $this->pictureRepository = $pictureRepository;
        $this->reservationRepository = $reservationRepository;
    }




    #[Route('/', name: 'app_home')]
    public function index(): Response
    {

        $pictures = $this->pictureRepository->findAll();

        $reservations = $this->reservationRepository->findAll();

        $reservedDates = [];

        foreach($reservations as $reservation) {
            $reservedDates[] = [
                'id' => $reservation->getId(),
                'arrivalDate' => $reservation->getArrivalDate()->format('Y-m-d'),
                'departureDate' => $reservation->getDepartureDate()->format('Y-m-d'),
            ];
        }

         $data = json_encode($reservedDates);
         
        return $this->render('home/index.html.twig', [
            'reservedDates' => $data,
            'pictures' => $pictures
        ]);


    //     $events = $calendarRepository->findAll();
    //     $reservations = [];
    //     $backgroundColor = '#000';
        
    //     foreach($events as $event) {
    //         $reservations[] = [
    //             'id' => $event->getId(),
    //             'title' => $event->getTitle(),
    //             'start' => $event->getStart()->format('Y-m-d'),
    //             'end' => $event->getEnd()->format('Y-m-d'),
    //             'description' => $event->getDescription(),
    //             // 'backgroundColor' => $event->getBackgroundColor(),
    //             'backgroundColor' => $backgroundColor,
    //             'borderColor' => $event->getBorderColor(),
    //             // 'textColor' => $event->getTextColor(),
    //             'display' => 'background',
    //             'selectable' => false,
    //         ];
        // }
    //     $data = json_encode($reservations);
    //     return $this->render('home/index.html.twig', compact('data'));

    //     // return $this->render('home/index.html.twig', [
    //     //     'controller_name' => 'HomeController',
    //     // ]);
    // }
}
}