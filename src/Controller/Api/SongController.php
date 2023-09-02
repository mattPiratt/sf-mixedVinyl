<?php

namespace App\Controller\Api;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SongController extends AbstractController
{
    #[Route('/api/song/{songId<\d+>}', name: 'app_api_song', methods: ['GET'])]
    public function index(int $songId, LoggerInterface $logger): Response
    {

        // TODO query the database
        $song = [
            'id' => $songId,
            'name' => 'Waterfalls',
            'url' => 'https://symfonycasts.s3.amazonaws.com/sample.mp3',
        ];

        $logger->info('Returninig information of song {song}', [
            'song' => $songId,
        ]);

        return $this->json($song);
    }
}
