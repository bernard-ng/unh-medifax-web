<?php

declare(strict_types = 1);

namespace App\EventListener;

use App\Entity\User;
use Random\Randomizer;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;

/**
 * Class BeforeEntityUpdatedListener.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final readonly class BeforeEntityUpdatedListener
{

    #[AsEventListener(event: BeforeEntityUpdatedEvent::class)]
    public function __invoke(BeforeEntityUpdatedEvent $event): void
    {
        $entity = $event->getEntityInstance();

        if (method_exists($entity, 'setUpdatedAt')) {
            $entity->setUpdatedAt(new \DateTimeImmutable());
        }
    }
}
