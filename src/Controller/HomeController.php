<?php

namespace App\Controller;

use App\Repository\CalendarRepository;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ReservationRepository $reservationRepository): Response
    {

        $reservations = $reservationRepository->findAll();

        $reservedDates = [];

        foreach($reservations as $reservation) {
            $reservedDates[] = [
                'id' => $reservation->getId(),
                'arrivalDate' => $reservation->getArrivalDate()->format('Y-m-d'),
                'departureDate' => $reservation->getDepartureDate()->format('Y-m-d')
            ];
        }

         $data = json_encode($reservedDates);
        //  var_dump($data);
         
        return $this->render('home/index.html.twig', [
            'reservedDates' => $data
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