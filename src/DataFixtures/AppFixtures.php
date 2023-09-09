<?php

namespace App\DataFixtures;

use App\Entity\VinylMix;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\ByteString;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $amount = 25;
        for ($i = 0; $i < $amount; $i++) {
            $entity = new VinylMix();

            $genres = ['pop', 'rock', 'heavy-metal'];
            $entity
                ->setTitle("Random title nr {$i}")
                ->setDescription(ByteString::fromRandom(99, "abcdefg ")->toString())
                ->setGenre($genres[array_rand($genres)])
                ->setTrackCount(rand(5, 15))
                ->setVotes(rand(-50, 50));
            $manager->persist($entity);
        }

        $manager->flush();
    }
}
