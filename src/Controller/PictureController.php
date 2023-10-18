<?php

namespace App\Controller;

use App\Entity\Picture;
use App\Form\PictureType;
use App\Repository\PictureRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PictureController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var PictureRepository
     */
    private $pictureRepository;

    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    public function __construct(PictureRepository $pictureRepository, EntityManagerInterface $em, CategoryRepository $categoryRepository)
    {
        $this->pictureRepository = $pictureRepository;
        $this->em = $em;
        $this->categoryRepository = $categoryRepository;
    }

    #[Route('/picture', name: 'app_picture')]
    public function index(): Response
    {
        $pictures = $this->pictureRepository->findBy([], []);

        return $this->render('picture/index.html.twig', [
            'pictures' => $pictures,
        ]);
    }

    /**
    * Fonction pour ajouter une photo dans une catégorie d'image
    */

    #[Route('/{category_id}/picture/new', name: 'new_picture')]

    public function new( Request $request, $category_id): Response {
       
        // on crée une nouvelle instance
        $picture = new Picture();
    
        // on cherche l'id de la catégorie
        $category = $this->categoryRepository->find($category_id);

        // on crée un formulaire en utilisant la classe pictureType et associe la picture à ce formulaire
        $form = $this->createForm(PictureType::class, $picture);

        // la méthode handleRequest traite les données du formulaire
        $form->handleRequest($request);

        // on vérifie si le formulaire a été soumis et s'il est valide
        if($form->isSubmitted() && $form->isValid()) {

            $picture = $form->getData(); // récupère les données du formulaire

            $picture->setCategory($category); // on ajoute l'id de la catégorie à picture
            
            // persiste la picture en BDD (enregistrer les données de l'objet picture en BDD, ajouter ou mettre à jour):
            $this->em->persist($picture); // indique à Doctrine de suivre cette instance de picture pour une eventuelle opération de persistance
            $this->em->flush(); // envoie réellement les opérations en BDD
            return $this->redirectToRoute('show_picture' , ['id' => $category_id]); // redirection vers la page
        }

        // affiche la vue pour le formulaire d'ajout ou d'édition
        return $this->render('picture/new.html.twig', [ // fonction render() génère une page HTML à partir du modèle template. l'argument est un tableau associatif qui permet de passer des données au template pour les afficher
            'form' => $form, // transmet le formulaire
            'edit' => $picture->getId(), // transmet l'ID de la picture actuelle (ajout ou edit)
            'pictureId' => $picture->getId() // transmet aussi l'ID de la photo mais sous un autre nom
        ]);

    }   
}
