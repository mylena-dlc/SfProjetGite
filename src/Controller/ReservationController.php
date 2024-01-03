<?php

namespace App\Controller;

use DateInterval;
use Stripe\Stripe;
use App\Entity\Reservation;
use Stripe\Checkout\Session;
use App\Form\ReservationType;
use App\Service\DompdfService;
use App\Repository\GiteRepository;
use App\Repository\UserRepository;
use App\Repository\PeriodRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
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

    // on récupère l'id de l'utilisateur connecté et son email
    $user = $this->getUser();
    $email = $user->getEmail();

    $reservation->setUser($user);
    $reservation->setEmail($email);


   $form = $this->createForm(ReservationType::class, $reservation);

    // Gérez la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $reservation = $form->getData(); 

            $paymentMethod = $form->get('paymentMethod')->getData();

            // prepare en PDO
            $this->em->persist($reservation);
            // execute PDO
            $this->em->flush();


        // En fonction de $paymentMethod, redirigez l'utilisateur vers la page de paiement appropriée
        if ($paymentMethod === 'stripe') {
            return $this->redirectToRoute('paiement', ['id' => $reservation->getId()]);
        } elseif ($paymentMethod === 'paypal') {
            return $this->redirectToRoute('page_paiement_paypal');
        }
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
    * Fonction de paiement
    */

    #[Route('/reservation/{id}/paiement', name: 'paiement')]
    public function paiement(Request $request, Reservation $reservation): Response {

    // Récupérez les détails de la réservation
    $totalPrice = $reservation->getTotalPrice() * 100; // Convertissez le prix en centimes

    // Configurez Stripe
    Stripe::setApiKey('sk_test_51OL5DQAbkgcVVGZDKN6MS8EVZOGCJSGM5gXA53MGhMwtUV2RYQGjoaR7HHs5i7OFXuKBTlbIamEk0Hkb7DDDZg5P00IXHHv8tj');

    
    // Créez une session de paiement avec Stripe Checkout
    $session = Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => 'eur',
                'product_data' => [
                    'name' => 'Réservation de gîte',
                ],
                'unit_amount' => $totalPrice,
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'success_url' => $this->generateUrl('confirm_reservation', [], UrlGeneratorInterface::ABSOLUTE_URL),
        'cancel_url' => $this->generateUrl('new_reservation', ['id' => $reservation->getId()], UrlGeneratorInterface::ABSOLUTE_URL),
    ]);

    // Redirigez l'utilisateur vers la page de paiement de Stripe
    return $this->redirect($session->url);
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

    /**
    * Fonction pour afficher les détails d'une réservation
    */

    #[Route('/reservation/{id}', name: 'show_reservation')]
    public function show(int $id): Response
    {
    // Récupérez la réservation depuis la base de données
    $reservation = $this->reservationRepository->find($id);

    // Passez les données de la réservation à la vue
    return $this->render('reservation/show.html.twig', [
        'reservation' => $reservation,
    ]);
}


    /**
    * Fonction pour télécharger la facture d'une réservation
    */

    #[Route('/reservation/{id}/show/download-invoice', name: 'download_invoice')]
    public function downloadInvoice(DompdfService $dompdfService, int $id): Response
    {
        // Récupérez les informations nécessaires pour la facture
        $reservation = $this->reservationRepository->find($id);
        $gite = $this->giteRepository->find(4);

         // Obtenez les dates de début et de fin de la réservation
        $startDate = $reservation->getArrivalDate();
        $endDate = $reservation->getDepartureDate();

        // Calcule du nombre de nuit
        $diff = $startDate->diff($endDate);
        $numberNight = $diff->format('%a');

        // Calcul du prix sans le forfait de ménage
        $cleaningCharge = $gite->getcleaningCharge();
        $priceHt = $reservation->getTotalPrice() - $cleaningCharge;


        // Générez le HTML pour la facture
        $html = $this->renderView('reservation/invoice.html.twig', [
            'reservation' => $reservation, 
            'numberNight' => $numberNight,
            'gite' => $gite,
            'priceHt' => $priceHt,
            
        ]);

        // Générez le PDF à partir du HTML
        $pdfContent = $dompdfService->generatePdf($html);

        // Créez une réponse de téléchargement
        $response = new Response($pdfContent);
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment;filename=facture.pdf');

        return $response;
    }



}