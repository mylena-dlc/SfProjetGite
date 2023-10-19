<?php

namespace App\Controller;

use App\Entity\Gite;
use App\Form\GiteType;
use App\Repository\GiteRepository;
use App\Repository\PictureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GiteController extends AbstractController
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


    public function __construct(GiteRepository $giteRepository, EntityManagerInterface $em, PictureRepository $pictureRepository)
    {
        $this->giteRepository = $giteRepository;
        $this->em = $em;
        $this->pictureRepository = $pictureRepository;
    }


    #[Route('/gite', name: 'app_gite')]
    public function index(): Response
    {
        $gites = $this->giteRepository->findAll();
        return $this->render('gite/index.html.twig', [
            'gites' => $gites,
        ]);
    }

    /**
    * Fonction pour ajouter ou éditer un gîte
    */

    #[Route('/gite/new', name: 'new_gite')]
    #[Route('/gite/{id}/edit', name: 'edit_gite')]

    public function new_edit(Gite $gite = null, Request $request): Response {
    
        if(!$gite) {
            $gite = new Gite();
        }
    
        $form = $this->createForm(GiteType::class, $gite);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $gite = $form->getData(); 
            // prepare en PDO
            $this->em->persist($gite);
            // execute PDO
            $this->em->flush();

            return $this->redirectToRoute('app_gite');
        }

        return $this->render('gite/new.html.twig', [
            'form' => $form,
            'edit' => $gite->getId(),
        ]);
    }  
    
    /**
    * Fonction pour supprimer un gîte
    */

    #[Route('/gite/{id}/delete', name: 'delete_gite')]
    public function delete(Gite $gite) {

        // pour préparé l'objet $gite à supprimer (enlever cet objet de la collection)
        $this->em->remove($gite);
        // flush va faire la requête SQL et concretement supprimer l'objet de la BDD
        $this->em->flush();

        return $this->redirectToRoute('app_gite');
    }

}
