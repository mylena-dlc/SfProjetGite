<?php

namespace App\Controller;

use App\Repository\CalendarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(CalendarRepository $calendarRepository): Response
    {
        $events = $calendarRepository->findAll();

        $reservations = [];
        
        // dd($events);
        foreach($events as $event) {
            $reservations[] = [
                'id' => $event->getId(),
                'title' => $event->getTitle(),
                'startDate' => $event->getStartDate()->format('Y-m-d H:i:s'),
                'endDate' => $event->getEndDate()->format('Y-m-d H:i:s'),
                'description' => $event->getDescription(),
                'backgroundColor' => $event->getBackgroundColor(),
                'borderColor' => $event->getBorderColor(),
                'textColor' => $event->getTextColor(),
            ];
        }

        $data = json_encode($reservations);
        return $this->render('home/index.html.twig', compact('data'));

        // return $this->render('home/index.html.twig', [
        //     'controller_name' => 'HomeController',
        // ]);
    }
}
