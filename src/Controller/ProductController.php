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

    #[Route('/add/product', name: 'app_add_product', methods: ['POST'])]
    public function addProduct(HubInterface $hub): JsonResponse
    {
        for ($i = 0; $i < 10; ++$i) {
            $update = new Update(
                'my-private-topic',
                json_encode(['status' => 'OutOfStock'])
            );
            $hub->publish($update);
        }

        
        return $this->json(["message" => "message send"], Response::HTTP_OK);
    }
}
