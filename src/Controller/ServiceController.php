<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class ServiceController.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class ServiceController extends AbstractController
{

    #[Route('/services', name: 'app_service_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('service/index.html.twig');
    }

    #[Route('/service-details', name: 'app_service_show', methods: ['GET'])]
    public function show(Request $request): Response
    {
        return $this->render('service/show.html.twig', [
            'service' => $request->query->getString('service'),
            'image' => $request->query->getString('image')
        ]);
    }
}
