<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Appointment;
use App\Form\AppointmentType;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class MainController.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
#[Route('', name: 'app_')]
final class MainController extends AbstractController
{
    #[Route('', name: 'index', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $em, MailerInterface $mailer): Response
    {
        $data = new Appointment();
        if ($this->isGranted('ROLE_PATIENT') || $this->isGranted('ROLE_ADMIN')) {
            $data->setPatient($this->getUser());
        }

        $form = $this->createForm(AppointmentType::class, $data);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($data->getPatient() === null) {
                return $this->redirectToRoute("login");
            }

            $em->persist($data);
            $em->flush();


            $email = (new Email())
                ->to($data->getDoctor()->getEmail())
                ->from('appointment@medifax.com')
                ->subject('Nouvel demande de rendez-vous')
                ->text(<<<EMAIL
                    {$data->getPatient()->getFullName()} demande un rendez-vous pour le {$data->getDate()->format('d M Y')}
                    Description : {$data->getDescription()}
                EMAIL);

            $mailer->send($email);
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('index.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/services/{service}', name: 'service_details', methods: ['GET'])]
    public function service(): Response
    {
        return $this->render('service-details.html.twig');
    }

    #[Route('/about', name: 'about', methods: ['GET'])]
    public function about(): Response
    {
        return $this->render('about.html.twig');
    }

    #[Route('/contact', name: 'contact', methods: ['GET', 'POST'])]
    public function contact(): Response
    {
        return $this->render('contact.html.twig');
    }
}
