<?php

namespace App\DataFixtures;

use App\Entity\Cathegorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CathegorieFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i <10; $i++){
            $cathegorie = new Cathegorie();
            $cathegorie->setName("Cathegorie number " . $i+1);
            $manager->persist($cathegorie);
           }
        $manager->flush();
    }
}
