<?php

namespace App\Controller;

use App\Provider\ShoutcastDataProvider;
use App\Service\DiscogsService;
use App\Service\GCService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CallbackController extends AbstractController
{
    #[Route('/callback', name: 'callback')]
    public function index(
        ShoutcastDataProvider $provider,
        DiscogsService $discogsService,
        GCService $garbageService
    ): Response {
        $message = 'Successful';

        $garbageService->execute();

        try {
            $songTitle = $provider->retrieve();
            if (!$songTitle) {
                $message = 'No incoming data';
            }
            $result = $discogsService->read($songTitle);
        } catch (\Exception $exception) {
            return $this->json([
                'message' => $exception->getMessage(),
            ], 500);
        }

        return $this->json([
            'message' => $message,
        ]);
    }
}
