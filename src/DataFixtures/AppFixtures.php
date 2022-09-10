<?php

namespace App\DataFixtures;

use App\Entity\Property;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($u = 0; $u < 10; $u++) {
            $user = new User();

            $hash = $this->hasher->hashPassword($user, "password");

            $user->setName($faker->name)
                ->setEmail($faker->email)
                ->setPassword($hash);

            $manager->persist($user);

            for ($i = 0; $i < mt_rand(3, 10); $i++) {
                $property = new Property();
                $property->setPrice($faker->randomFloat(2, 800, 5000))
                    ->setName($faker->sentence(10))
                    ->setCity($faker->city)
                    ->setStreet($faker->streetName)
                    ->setNumberOfRooms($faker->numberBetween(1, 15))
                    ->setDescription($faker->text(500))
                    ->setStatus($faker->randomElement(['ACTIVE', 'DELETED', 'RENTED']))
                    ->setCreator($user);
                $manager->persist($property);
            }
        }

        $manager->flush();
    }
}
