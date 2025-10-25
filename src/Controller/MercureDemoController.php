<?php
// src/Controller/MercureDemoController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

#[Route('/mercure-demo')]
class MercureDemoController extends AbstractController
{
    public function __construct(
        private HubInterface $hub
    ){}

    /**
     * Main page - explains Mercure and shows links to sender and receiver
     */
    #[Route('/', name: 'mercure_demo_index')]
    public function index(): Response
    {
        return $this->render('mercure_demo/index.html.twig');
    }

    /**
     * Sender page - form to send messages
     */
    #[Route('/sender', name: 'mercure_demo_sender')]
    public function sender(): Response
    {
        return $this->render('mercure_demo/sender.html.twig');
    }

    /**
     * Receiver page - listens for incoming messages
     */
    #[Route('/receiver', name: 'mercure_demo_receiver')]
    public function receiver(): Response
    {
        return $this->render('mercure_demo/receiver.html.twig');
    }

    /**
     * API endpoint to publish a message via Mercure
     */
    #[Route('/api/send-message', name: 'mercure_demo_send_message', methods: ['POST'])]
    public function sendMessage(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $message = $data['message'] ?? 'Hello from Mercure!';
        $username = $data['username'] ?? 'Anonymous';

        // Create an Update object
        // First parameter: topic (channel name) - subscribers listen to this
        // Second parameter: data to send (must be a string, usually JSON)
        $update = new Update(
            'chat-messages', // Topic name
            json_encode([
                'username' => $username,
                'message' => $message,
                'timestamp' => date('H:i:s'),
            ])
        );

        // Publish the update to Mercure hub
        // All subscribers to 'chat-messages' topic will receive this in real-time
        $this->hub->publish($update);

        return $this->json([
            'success' => true,
            'message' => 'Message sent successfully!',
            'data' => [
                'username' => $username,
                'message' => $message,
            ]
        ]);
    }
}

