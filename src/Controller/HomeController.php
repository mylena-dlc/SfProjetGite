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

        $backgroundColor = '#000';
        
        foreach($events as $event) {
            $reservations[] = [
                'id' => $event->getId(),
                'title' => $event->getTitle(),
                'start' => $event->getStart()->format('Y-m-d'),
                'end' => $event->getEnd()->format('Y-m-d'),
                
                'description' => $event->getDescription(),
                // 'backgroundColor' => $event->getBackgroundColor(),
                'backgroundColor' => $backgroundColor,
                'borderColor' => $event->getBorderColor(),
                // 'textColor' => $event->getTextColor(),
                'display' => 'background',
                'selectable' => false,

            ];
        }

        $data = json_encode($reservations);
        return $this->render('home/index.html.twig', compact('data'));

        // return $this->render('home/index.html.twig', [
        //     'controller_name' => 'HomeController',
        // ]);
    }
}
