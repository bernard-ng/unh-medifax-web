<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\User;
use Random\Randomizer;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Class BeforeEntityPersistedListener.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final readonly class BeforeEntityPersistedListener
{
    public function __construct(
        private UserPasswordHasherInterface $hasher,
        private MailerInterface $mailer,
        private LoggerInterface $logger
    ) {
    }

    #[AsEventListener(event: BeforeEntityPersistedEvent::class)]
    public function __invoke(BeforeEntityPersistedEvent $event): void
    {
        $entity = $event->getEntityInstance();

        if (method_exists($entity, "setCreatedAt")) {
            $entity->setCreatedAt(new \DateTimeImmutable());
        }

        if ($entity instanceof User) {
            if ("" === $entity->getPassword()) {
                $password = (string) (new Randomizer())->getInt(0000, 9999);
                $entity->setPassword($this->hasher->hashPassword($entity, $password));
                
               try {
                   $email = (new Email())
                       ->subject('Mot de passe par défault | MadiFax')
                       ->to(new Address($entity->getEmail(), $entity->getFullName()))
                       ->from(new Address('contact@devscas.tech', 'MadiFax'))
                       ->text($password);

                   $this->mailer->send($email);
               } catch (\Throwable $e) {
                   $this->logger->error($e->getMessage(), $e->getTrace());
               }
            }
        }
    }
}
