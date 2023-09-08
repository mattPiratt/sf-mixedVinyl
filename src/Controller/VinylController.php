<?php

namespace App\Controller;

use App\Entity\VinylMix;
use App\Service\MixRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\CacheItem;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String as SFString;
use Symfony\Contracts\Cache\CacheInterface;
use Twig\Environment;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Repository\VinylMixRepository;

class VinylController extends AbstractController
{

    #[Route('/', name: 'app_homepage')]
    public function homepage(Environment $twig): Response
    {
        $tracks = [
            ['song' => 'Gangsta\'s Paradise', 'artist' => 'Coolio'],
            ['song' => 'Waterfalls', 'artist' => 'TLC'],
            ['song' => 'Creep', 'artist' => 'Radiohead'],
            ['song' => 'Kiss from a Rose', 'artist' => 'Seal'],
            ['song' => 'On Bended Knee', 'artist' => 'Boyz II Men'],
            ['song' => 'Fantasy', 'artist' => 'Mariah Carey'],
        ];

        return new Response($twig->render('vinyl/homepage.html.twig', [
            'title' => "PB & Jams",
            'tracks' => $tracks,
        ]));
    }

    #[Route('/browse/{slug?}', name: 'app_browse_genre')]
    public function browse(
        EntityManagerInterface $entityManager,
        string $slug = null,
        MixRepository $mixRepository
    ): Response
    {
        if ($slug) {
            $genre = "Genre: " . SFString\u(str_replace('-', ' ', $slug))->title();
        } else {
            $genre = "All Genres";
        }

        /** @var VinylMixRepository mixesEntity */
        $mixesEntity = $entityManager->getRepository(VinylMix::class);
        $mixes = $mixesEntity->findBy([], ['votes' => 'DESC']);

        return $this->render('vinyl/browse.html.twig', [
            'genre' => $genre,
            // 'mixes' => $mixRepository->getAll(),
            'mixes' => $mixes,
        ]);
    }

}
