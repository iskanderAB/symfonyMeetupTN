<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

final class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product')]
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    #[Route('/dragon/ball', name: 'app_add_product', methods: ['POST'])]
    public function addProduct(HubInterface $hub): JsonResponse
    {
        $update = new Update(
            topics: 'dragon-ball-topic',
            data: json_encode([
                'message' => '⚡ A new Dragon Ball has been discovered!',
                'power_level' => rand(1000, 9000), // just for fun 😁
                'timestamp' => (new \DateTime())->format('Y-m-d H:i:s'),
            ])
        );

        $hub->publish($update);

        return $this->json([
            'status' => 'success',
            'message' => '🔔 Shenron has been summoned via Mercure!',
        ], Response::HTTP_OK);
    }
}
