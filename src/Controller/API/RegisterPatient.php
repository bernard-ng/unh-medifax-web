<?php

declare(strict_types=1);

namespace App\Controller\API;

use App\Entity\Patient;
use App\Security\EmailVerifier;
use App\Repository\UserRepository;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Class RegisterPatient.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
#[AsController]
final class RegisterPatient extends AbstractController
{
    public function __construct(
        private readonly UserPasswordHasherInterface $hasher,
        private readonly EmailVerifier $emailVerifier,
        private readonly UserRepository $repository
    ) {
    }

    public function __invoke(Patient $patient): Patient
    {
        $password = $patient->getPassword();

        $patient->setPassword($this->hasher->hashPassword($patient, $password));
        $this->repository->add($patient);

        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $patient,
            (new TemplatedEmail())
                ->from(new Address('contact@devscast.tech', 'Medifax'))
                ->to($patient->getEmail())
                ->subject('Please Confirm your Email')
                ->htmlTemplate('registration/confirmation_email.html.twig')
        );

        return $patient;
    }
}