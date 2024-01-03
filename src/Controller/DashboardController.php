<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\ReservationViewType;
use App\Repository\GiteRepository;
use App\Repository\PeriodRepository;
use App\Repository\PictureRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
    public function index(Request $request): Response

    {
        // Recherche des 5 réservations les plus récentes pour l'index du dashboard
        $reservations = $this->reservationRepository->findBy([], ['reservationDate' => 'ASC'], 5);


        // Créez un formulaire pour le bouton "Enregistrer" unique
        $form = $this->createFormBuilder()
            ->add('save', SubmitType::class, ['label' => 'Enregistrer'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($reservations as $reservation) {
                // Mettez à jour l'état "view" uniquement pour les réservations dont la case à cocher est cochée
                if ($request->request->get('reservation_view_' . $reservation->getId())) {
                    $reservation->setView(true);                        
                    $this->em->flush();
                }
            }     
        
            $this->addFlash('success', 'Les états "vue" des réservations ont été modifiés avec succès.');


            $viewForms[$reservation->getId()] = $form->createView();
        }

        return $this->render('dashboard/index.html.twig', [
            'reservations' => $reservations,
            'form' => $form->createView(), // Ajoutez ce formulaire à la vue

        ]);
    }



    /**
    * Fonction pour afficher les réservations passées
    */

    #[Route('admin/dashboard/previous-reservations', name: 'app_previous_reservations')]
    public function previousReservations(): Response
    {
        $previousReservations = $this->reservationRepository->findPreviousReservations();
    
        return $this->render('dashboard/previous-reservations.html.twig', [
            'previousReservations' => $previousReservations,
        ]);
    }
    


    /**
    * Fonction pour afficher les réservations à venir
    */

    #[Route('admin/dashboard/upcoming-reservations', name: 'app_upcoming_reservations')]
    public function upcomingReservations(): Response
    {
        $upcomingReservations = $this->reservationRepository->findUpcomingReservations();
    
        return $this->render('dashboard/upcoming-reservations.html.twig', [
            'upcomingReservations' => $upcomingReservations,
        ]);
    }

    

  

    /**
    * Fonction pour modifier l'email d'un user
    */
    

    // #[Route(path: '/profil/{id}/update-email', name: 'app_update_email')]
    // public function updateEmail(Request $request): Response
    // {
    //     // Récupérez l'user connecté
    //     $user = $this->getUser();

    //     // S'il n'est pas connecté, le rediriger vers la page de connection
    //     if (!$user) {
    //         return $this->redirectToRoute('app_login');
    //     }

    //     // Créez et gérez le formulaire
    //     $form = $this->createForm(UserEmailType::class, $user);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->persist($user);
    //         $entityManager->flush();

    //         $this->addFlash('success', 'Adresse e-mail mise à jour avec succès.');

    //         return $this->redirectToRoute('app_profil');
    //     }

        
    //     return $this->render('dashboard/profil.html.twig', [
    //         'form' => $form->createView(),

    //         // 'user' => $user,
    //         // 'reservations' => $reservations
    //     ]);    
    // }






}

