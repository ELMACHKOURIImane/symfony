<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Product ; 

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i <10; $i++){
         $product = new Product();
         $product->setName("Product number " . $i+1);
         $product->setPrice(10);
         $product->setDescription("this is a description for the '.$i+1.'product");
         $manager->persist($product);
        }

         $manager->flush();
    }
}
