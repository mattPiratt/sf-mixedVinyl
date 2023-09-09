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
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;

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
        MixRepository $mixRepository,
        Request $request,
    ): Response {
        if ($slug) {
            $genre = "Genre: " . SFString\u(str_replace('-', ' ', $slug))->title();
        } else {
            $genre = "All Genres";
        }

        /** @var VinylMixRepository mixesEntity */
        $mixesEntity = $entityManager->getRepository(VinylMix::class);
        $qb = $mixesEntity->createOrderByVotesQueryBuilder($slug);
        $adapter = new QueryAdapter($qb);
        $pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage(
            $adapter,
            $request->get('page', 1),
            9
        );

        return $this->render('vinyl/browse.html.twig', [
            'genre' => $genre,
            // 'mixes' => $mixRepository->getAll(),
            'pager' => $pagerfanta,
        ]);
    }

    #[Route('/mix/{slug}', name: 'app_individual_mix')]
    public function show(
        string $slug,
        VinylMixRepository $vinylMixRepository,
    ): Response {

        $mix = $vinylMixRepository->findOneBy(['slug' => $slug]);

        if (!$mix) {
            throw $this->createNotFoundException("Mix not found in DB");
        }

        $tracks = [
            ['song' => 'Gangsta\'s Paradise', 'artist' => 'Coolio'],
            ['song' => 'Waterfalls', 'artist' => 'TLC'],
            ['song' => 'Creep', 'artist' => 'Radiohead'],
            ['song' => 'Kiss from a Rose', 'artist' => 'Seal'],
            ['song' => 'On Bended Knee', 'artist' => 'Boyz II Men'],
            ['song' => 'Fantasy', 'artist' => 'Mariah Carey'],
        ];
        return $this->render('mix/show.html.twig', [
            'mix' => $mix,
            'tracks' => $tracks,
        ]);
    }

    #[Route('/mix/{slug}/vote', name: 'vote_action', methods: ['POST'])]
    public function vote(
        VinylMix $vinylMix,
        Request $request,
        EntityManagerInterface $entityManagerInterface,
    ): Response {
        match ($request->request->get('direction', 'up')) {
            "up" => $vinylMix->upVote(),
            "down" => $vinylMix->downVotes()
        };

        $entityManagerInterface->flush();

        $this->addFlash('success', "Vote has been saved!");

        return $this->redirectToRoute('app_individual_mix', ['slug' => $vinylMix->getSlug()]);
    }
}
