<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BilemoFixtures extends Fixture
{
    public function load( ObjectManager $manager)
    {
        $clients = [];
        for ($i = 0; $i < 5; $i++) {
            $client = (new Client())
                ->setFirstName('John ' . ($i + 1))
                ->setLastName('Doe')
                ->setEmail('client' . ($i + 1) . '@client.com');

            $manager->persist($client);
            $clients[] = $client;
        }

        $hash = password_hash('123456', PASSWORD_BCRYPT);
        for ($i = 0; $i < 5; $i++) {
            $user = (new User())
                ->setEmail('user' . ($i + 1) . '@user.com')
                ->setPassword($hash)
                ->addClient($clients[$i % count($clients)]);

            $manager->persist($user);
        }

        for ($i = 0; $i < 50; $i++) {
            $price = rand(200, 1050);
            $product = (new Product())
                ->setName('Mobile ' . ($i + 1))
                ->setPrice( $price)
                ->setDescription('Mobile Description ' . ($i + 1));

            $manager->persist($product);
        }

        $manager->flush();
    }
}
