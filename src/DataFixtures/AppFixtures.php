<?php

namespace App\DataFixtures;

use App\Entity\Brand;
use App\Entity\Command;
use App\Entity\Comment;
use App\Entity\Product;
use App\Entity\Type;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $faker = Factory::create('fr_FR');

        for($i = 1; $i <= 5; $i++)
        {
            $command = new Command();

            $command->setNumberOrder("Command-00" . $i);
            $command->setPrice($faker->numberBetween($min = 10, $max = 500));
            $command->setAdress($faker->text());
            $command->setCity($faker->word);
            $command->setName($faker->name);
            $command->setEmail($faker->email);

            $manager->persist($command);

      
            $type = new Type();

            $type->setName($faker->word);
            $type->setDescription($faker->text());
           

            $manager->persist($type);

            $brand = new Brand();

            $brand->setName($faker->word);
            $brand->setDescription($faker->text());
           

            $manager->persist($brand);


            $product = new Product();

            $product->setName($faker->word);
            $product->setPrice($faker->numberBetween( 10, 500));
            $product->setDescription($faker->text());
            $product->setStock($faker->numberBetween( 5, 20));            
            $product->setType($type);
            $product->setBrand($brand);

            $manager->persist($product);
        }




        $manager->flush();
    }
}
