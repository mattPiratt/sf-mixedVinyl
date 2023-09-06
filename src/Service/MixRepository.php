<?php

namespace App\Service;

use Symfony\Component\Cache\CacheItem;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Twig\Loader\FilesystemLoader;

class MixRepository
{
    public function __construct(
        private CacheInterface $cache,
        private HttpClientInterface $githubClient,
        private bool $isDebug,
        private FilesystemLoader $fsLoader,
        #[Autowire(service: 'twig.profile')] private $twigProfile,
    ) {
    }

    public function getAll(): array
    {
        return $this->cache->get('mixesFromGithub', function (CacheItem $cacheItem) {
            $cacheItem->expiresAfter($this->isDebug ? 5 : 60);
            $response = $this->githubClient->request(
                'GET',
                '/SymfonyCasts/vinyl-mixes/main/mixes.json'
            );
            return $response->toArray();
        });
    }
}
