<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(MailerInterface $mailer, Request $request): Response
    {
        
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $data = $form->getData();

        $email = (new Email())
            ->from($data['email'])
            ->to('delacote.mylena@gmail.com') // Remplacez par l'e-mail de l'administrateur
            ->subject('Nouveau message de contact')
            ->text($data['message']);

        $mailer->send($email);

        $this->addFlash('success', 'Votre message a été envoyé avec succès.');

        return $this->redirectToRoute('app_home');
    }
        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),

        ]);
    }

}
