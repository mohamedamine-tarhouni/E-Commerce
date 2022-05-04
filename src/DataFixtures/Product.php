<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Produit;
use App\Entity\Categorie;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class Product extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');
        for($i = 1; $i <= 3; $i++)
        {
            $Category = new Categorie;
            $Category->settitre($faker->sentence(3));
            $manager->persist($Category);
        }
        for($u = 1; $u <= 3; $u++)
        {
            $User = new User;
            $User->setemail($faker->sentence(3))
            ->setpassword($faker->sentence(3))
            ->setusername($faker->sentence(3));
            $manager->persist($User);
        }
        for($j = 1; $j <= 3; $j++)
        {
            $Product=new Produit;
            $Product->setcategorie($Category)
            ->setuser($User)
            ->setnom($faker->sentence(3))
            ->setdescription($faker->sentence(3))
            ->setprix(3.25)
            ->setimage($faker->sentence(3));
            $manager->persist($Product);
        }

        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
