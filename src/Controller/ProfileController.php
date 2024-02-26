<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Appointment;
use App\Enum\AppointmentStatus;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class ProfileController.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class ProfileController extends AbstractController
{

    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        return $this->render('profile.html.twig', [
            'user' => $this->getUser()
        ]);
    }
    
    #[Route('/profile/appointment/{id}/{action}', name: 'update_appointment')]
    public function update(
        Appointment $appointment,
        string $action,
        EntityManagerInterface $em,
        MailerInterface $mailer
    ): Response {
        if ($appointment->getStatus() === AppointmentStatus::PENDING) {
            match ($action) {
                'yes' => $appointment->setStatus(AppointmentStatus::APPROVED),
                'no' => $appointment->setStatus(AppointmentStatus::CANCELED)
            };
        }
        
        $email = (new Email())
            ->to($appointment->getPatient()->getEmail())
            ->from('appointment@medifax.com');
        
        if ($appointment->getStatus() === AppointmentStatus::APPROVED) {
            $email->subject("Rendez vous confirmé avec le docteur")
                ->text(<<<EMAIL
                Bonjour {$appointment->getPatient()->getFullName()}, votre rendez-vous 
                du {$appointment->getDate()->format('d m Y')} vient d'être confirmé,
                Veuillez vous rendre au cabinet pour plus de détail
                EMAIL);
        } else {
            $email->subject("Docteur Indisponible pour votre rendez-vous")
                ->text(<<<EMAIL
                Bonjour {$appointment->getPatient()->getFullName()}, malheureusement le docteur
                est indisponible à la date du rendez-vous, Veuillez proposer une nouvelle date ou rendez vous 
                au cabinet pour plus de détails.
                EMAIL);
        }

        $em->persist($appointment);
        $em->flush();
        $mailer->send($email);
        return $this->redirectToRoute('app_profile');
    }
}
