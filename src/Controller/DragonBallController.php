<?php
// src/Controller/DragonBallController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

class DragonBallController extends AbstractController
{
    public function __construct(
        private HubInterface $hub
    ){}


    #[Route('/', name: 'dragon_ball_index')]
    public function index(): Response
    {
        return $this->render('dragon_ball/index.html.twig');
    }

    #[Route('/player1', name: 'dragon_ball_player1')]
    public function player1(): Response
    {
        return $this->render('dragon_ball/player1.html.twig', [
            'character' => 'Goku',
            'playerNumber' => 1
        ]);
    }

    #[Route('/player2', name: 'dragon_ball_player2')]
    public function player2(): Response
    {
        return $this->render('dragon_ball/player2.html.twig', [
            'character' => 'Vegeta',
            'playerNumber' => 2
        ]);
    }

    #[Route('/api/transform', name: 'dragon_ball_transform', methods: ['POST'])]
    public function transform(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $character = $data['character'] ?? 'Goku';
        $newForm = $data['newForm'] ?? 'Base';

        // Character data avec power levels
        $characterData = [
            'Goku' => [
                'Base' => ['power' => 3000, 'color' => '#FF6B1B'],
                'Super Saiyan' => ['power' => 150000, 'color' => '#FFD700'],
                'Super Saiyan 2' => ['power' => 300000, 'color' => '#FFD700'],
                'Super Saiyan 3' => ['power' => 500000, 'color' => '#FFD700'],
                'Ultra Instinct' => ['power' => 999999, 'color' => '#C0C0C0'],
            ],
            'Vegeta' => [
                'Base' => ['power' => 2500, 'color' => '#4169E1'],
                'Super Saiyan' => ['power' => 125000, 'color' => '#FFD700'],
                'Blue Evolution' => ['power' => 800000, 'color' => '#1E90FF'],
            ],
            'Frieza' => [
                'Base' => ['power' => 530000, 'color' => '#9932CC'],
                'Final Form' => ['power' => 1200000, 'color' => '#9932CC'],
                'Golden Frieza' => ['power' => 900000, 'color' => '#FFD700'],
            ],
        ];

        $stats = $characterData[$character][$newForm] ?? ['power' => 100000, 'color' => '#FFFFFF'];

        $update = new Update(
            'dragon-ball-transform',
            json_encode([
                'character' => $character,
                'form' => $newForm,
                'power' => $stats['power'],
                'color' => $stats['color'],
                'timestamp' => date('Y-m-d H:i:s'),
            ])
        );

        $this->hub->publish($update);

        return $this->json([
            'success' => true,
            'message' => "$character transformed to $newForm!",
            'character' => $character,
            'form' => $newForm,
            'power' => $stats['power'],
        ]);
    }

    #[Route('/api/battle', name: 'dragon_ball_battle', methods: ['POST'])]
    public function battle(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $character1 = $data['character1'] ?? 'Goku';
        $power1 = $data['power1'] ?? 50000;
        $character2 = $data['character2'] ?? 'Vegeta';
        $power2 = $data['power2'] ?? 40000;

        $winner = $power1 > $power2 ? $character1 : $character2;
        $winnerPower = max($power1, $power2);

        // Broadcast battle result
        $update = new Update(
            'dragon-ball-battle',
            json_encode([
                'character1' => $character1,
                'power1' => $power1,
                'character2' => $character2,
                'power2' => $power2,
                'winner' => $winner,
                'winnerPower' => $winnerPower,
                'timestamp' => date('Y-m-d H:i:s'),
            ])
        );

        $this->hub->publish($update);

        return $this->json([
            'winner' => $winner,
            'message' => "$winner wins the battle!",
        ]);
    }

    #[Route('/api/characters', name: 'dragon_ball_characters', methods: ['GET'])]
    public function getCharacters(): Response
    {
        $characters = [
            ['name' => 'Goku', 'image' => 'goku.png'],
            ['name' => 'Vegeta', 'image' => 'vegeta.png'],
            ['name' => 'Frieza', 'image' => 'frieza.png'],
        ];

        return $this->json($characters);
    }
}

