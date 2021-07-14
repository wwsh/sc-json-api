<?php

namespace App\Controller;

use App\Service\DiscogsService;
use App\Service\GCService;
use App\Source\Source;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CallbackController extends AbstractController
{
    public function __construct(
        private Source $dataSource
    ) {}

    #[Route('/callback', name: 'callback')]
    public function index(
        DiscogsService $discogsService,
        GCService $garbageService
    ): Response {
        $message = 'Successful';

        $garbageService->execute();

        try {
            $songTitle = $this->dataSource->retrieve();
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
