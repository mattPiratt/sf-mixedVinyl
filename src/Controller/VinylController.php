<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String as SFString;

class VinylController extends AbstractController
{
    #[Route('/', name: 'app_vinyl')]
    public function index(): Response
    {
        return new Response('Welcome to your new controller!');
    }

    #[Route('/browse/{slug}')]
    public function browse(string $slug = null): Response
    {
        if ($slug) {
            $title = "Genre: " . SFString\u(str_replace('-', ' ', $slug))->title();
        } else {
            $title = "All Genres";
        }

        return new Response($title);
    }
}
