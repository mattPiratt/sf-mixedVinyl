<?php

namespace App\DataFixtures;

use App\Entity\VinylMix;
use App\Factory\VinylMixFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\ByteString;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        VinylMixFactory::createMany(25);
    }
}
