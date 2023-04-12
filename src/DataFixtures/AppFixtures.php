<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Cathegorie;
use App\Entity\Product;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $entityManager): void
    {
        $category = new Cathegorie();
        $category->setName('Computer Peripherals');

        $product = new Product();
        $product->setName('Keyboard');
        $product->setPrice(19.99);
        $product->setDescription('Ergonomic and stylish!');

        // relates this product to the category
        $product->setCategory($category);

        $entityManager->persist($category);
        $entityManager->persist($product);
        $entityManager->flush();
    }
}
