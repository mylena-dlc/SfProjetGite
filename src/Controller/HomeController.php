<?php

namespace App\Controller;

use App\Repository\ReviewRepository;
use App\Repository\PictureRepository;
use App\Repository\CalendarRepository;
use App\Repository\CategoryRepository;
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

    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * @var ReviewRepository
     */
    private $reviewRepository;
    
    public function __construct(PictureRepository $pictureRepository, ReservationRepository $reservationRepository, CategoryRepository $categoryRepository, ReviewRepository $reviewRepository)
    {
        $this->pictureRepository = $pictureRepository;
        $this->reservationRepository = $reservationRepository;
        $this->categoryRepository = $categoryRepository;
        $this->reviewRepository = $reviewRepository;

    }


    #[Route('/', name: 'app_home')]
    public function index(): Response
    {

        // Affichage de la galerie d'images

        // on récupère toutes les catégories
        $categories = $this->categoryRepository->findAll();

        $categoryFirstPictures = [];

        foreach($categories as $category) {

            $firstPicture = $this->pictureRepository->findOneBy(['category' => $category], ['id' => 'ASC']);
        
            if ($firstPicture) {
                $categoryFirstPictures[$category->getName()] = $firstPicture;
            }
        }

        // Affichage des dates déjà réservées
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

         // Affichage des avis
         $reviews = $this->reviewRepository->findVerifiedReviews();

         // Affichage de la moyenne des notes
         $averageRating = $this->reviewRepository->averageRating();
         
        return $this->render('home/index.html.twig', [
            'reservedDates' => $data,
            'categoryFirstPictures' => $categoryFirstPictures,
            'categories' => $categories,
            'reviews' => $reviews,
            'averageRating' => $averageRating,
        ]);
}

#[Route('/contact', name: 'app_contact')]
public function contact(Request $request): Response
{

        $message = (new \Swift_Message('Nouveau message de contact'))
        ->setFrom($request->request->get('email'))
        ->setTo('mylena.delacote@laposte.net')  // Adresse e-mail de l'administrateur
        ->setBody($request->request->get('message'));

    $mailer->send($message);

    
    
    return $this->render('contact/index.html.twig');

}
}