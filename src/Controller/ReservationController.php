<?php

namespace App\Controller;

use DateInterval;
use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\GiteRepository;
use App\Repository\UserRepository;
use App\Repository\PeriodRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ReservationController extends AbstractController
{

    /**
     * @var ReservationRepository
     */
    private $reservationRepository;

    /**
     * @var GiteRepository
     */
    private $giteRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var PeriodRepository
     */
    private $periodRepository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    
    public function __construct(ReservationRepository $reservationRepository, GiteRepository $giteRepository, EntityManagerInterface $em, UserRepository $userRepository, PeriodRepository $periodRepository)
    {
        $this->reservationRepository = $reservationRepository;
        $this->giteRepository = $giteRepository;
        $this->userRepository = $userRepository;
        $this->periodRepository = $periodRepository;
        $this->em = $em;
    }
    
    #[Route('/reservation', name: 'app_reservation')]
    public function index(Request $request): Response
    {
        if ($request->isMethod('POST')) {

            // Récupérez les données du formulaire

            $startDate = $request->get('start');
            $endDate = $request->get('end');
            $dateRange = $request->get('start');

            // Extraire les dates de début et de fin
            list($startDate, $endDate) = explode(' to ', $dateRange);

            // Récupérez les dates de début et de fin de la réservation
            $startDate = new \DateTime($startDate);
            $endDate = new \DateTime($endDate);
            
            $numberAdult = $request->get('numberAdult');
            $numberKid = $request->get('numberKid');    
            

            // Vérifiez si les dates sélectionnées chevauchent d'autres réservations en BDD
            $overlappingReservations = $this->reservationRepository->findOverlappingReservations($startDate, $endDate);

            // S'il y a des chevauchements, retournez à la page d'accueil avec une alerte
            if (!empty($overlappingReservations)) {
                $this->addFlash('error', 'Les dates choisies ne sont plus disponibles.');
                return $this->redirectToRoute('app_home');  
            }

            // Vérifiez si les dates de la réservation chevauchent une période avec supplément
            $overlappingPeriods = $this->periodRepository->findOverlappingPerriods($startDate, $endDate);

            // Initialisez le supplément à zéro par défaut
            $supplement = 0;

            if (!empty($overlappingPeriods)) {
                // Ajoutez le supplément au prix de la réservation
                foreach ($overlappingPeriods as $period) {
                    $supplement += $period->getSupplement(); 
                }
            }

            // on recupère l'id du gite
            $gite = $this->giteRepository->find(4);

            // on recherche le prix du forfait ménage
            $cleaningCharge = $gite->getCleaningCharge();
             // on recherche le prix de la nuit
            $nightPrice = $gite->getPrice() + $supplement;

            // on compte le nombre de nuit
            $diff = $startDate->diff($endDate);
            $numberNight = $diff->format('%a');

            // Calculez le prix total de la réservation (avec supplément)
            $totalPrice = ($numberNight * $nightPrice) + $cleaningCharge + $supplement;

            // Stockez les données dans la session
            $session = $request->getSession();
            $session->set('reservation_details', [
                'startDate' => $startDate,
                'endDate' => $endDate,
                'numberAdult' => $numberAdult,
                'numberKid' => $numberKid,
                'numberNight' => $numberNight,
                'nightPrice' => $nightPrice,
                'cleaningCharge' => $cleaningCharge,
                'supplement' => $supplement,
                'totalPrice' => $totalPrice,
            ]);
        }

        return $this->render('reservation/index.html.twig', [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'numberAdult' => $numberAdult,
            'numberKid' => $numberKid,
            'numberNight' => $numberNight,
            'nightPrice' => $nightPrice,
            'cleaningCharge' => $cleaningCharge,
            'supplement' => $supplement,
            'totalPrice' => $totalPrice
        ]);
    }

    

    /**
    * Fonction pour confirmer une réservation
    */

    #[Route('/reservation/new', name: 'new_reservation')]
    public function new(Reservation $reservation = null, Request $request): Response {
    

    // Récupérez les données stockées en session
    $session = $request->getSession();

    $arrivalDate = $session->get('reservation_details')['startDate'];
    $departureDate = $session->get('reservation_details')['endDate'];
    $numberAdult = $session->get('reservation_details')['numberAdult'];
    $numberKid = $session->get('reservation_details')['numberKid'];
    $numberNight = $session->get('reservation_details')['numberNight'];
    $nightPrice = $session->get('reservation_details')['nightPrice'];
    $totalPrice = $session->get('reservation_details')['totalPrice'];

    // Créez une instance de l'entité Reservation et définissez les données initiales
    $reservation = new Reservation();

    $reservation->setArrivalDate($arrivalDate); 
    $reservation->setDepartureDate($departureDate);
    $reservation->setNumberAdult($numberAdult);
    $reservation->setNumberKid($numberKid);
    $reservation->setTotalPrice($totalPrice);

    // on recupère l'id du gite
    $gite = $this->giteRepository->find(4);

    // Associez le gîte à la réservation
    $reservation->setGite($gite);

    // on récupère l'id de l'utilisateur connecté
    $user = $this->getUser();
    $reservation->setUser($user);


   $form = $this->createForm(ReservationType::class, $reservation);

    // Gérez la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $reservation = $form->getData(); 
     
            // prepare en PDO
            $this->em->persist($reservation);
            // execute PDO
            $this->em->flush();

            // Redirigez l'utilisateur vers une page de confirmation ou autre
            return $this->redirectToRoute('confirm_reservation');
        }

        // Affichez le formulaire dans la vue Twig
        return $this->render('reservation/new.html.twig', [
            'form' => $form,
            'arrivalDate' => $arrivalDate->format('d-m-Y'),
            'departureDate' => $departureDate->format('d-m-Y'),
            'numberAdult' => $numberAdult,
            'numberKid' => $numberKid,
            'numberNight' => $numberNight,
            'nightPrice' => $nightPrice,
            'totalPrice' => $totalPrice,
            
        ]);
    }   

    /**
    * Fonction pour afficher la vue de confirmation d'une réservation
    */
    #[Route('/reservation/confirm', name: 'confirm_reservation')]
    public function confirm(): Response {

        $data = [
            'message' => 'La réservation a été enregistrée avec succès.',
        ];
    
        return $this->render('reservation/confirm.html.twig', [
            'data' => $data
        ]);

}
}