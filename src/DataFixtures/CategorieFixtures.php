<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CategorieFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');
        for($i=0;$i<10;$i++){
            $categorie = new Categorie;
            $categorie->settitre($faker->sentence(3));
            $manager->persist($categorie);
        }

        // $product = new Product();
        

        $manager->flush();
    }
}
