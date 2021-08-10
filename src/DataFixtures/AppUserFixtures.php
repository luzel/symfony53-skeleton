<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppUserFixtures extends Fixture
{

    private $encoder;
    private $faker;


    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
        $this->faker = \Faker\Factory::create();

    }

    public function load(ObjectManager $manager)
    {

        $user = new User();
        $user->setEmail('john.doe@gmail.com');
        $user->setRoles(['ROLE_USER','ROLE_ADMIN']);

        $password = $this->encoder->encodePassword($user, '1234');
        $user->setPassword($password);

        $manager->persist($user);
        
        for($i=0;$i<100;$i++)
        {
            $user = new User();
            $user->setEmail($this->faker->email);
            $user->setRoles(['ROLE_USER']);

            $password = $this->encoder->encodePassword($user, '1234');
            $user->setPassword($password);

            $manager->persist($user);
        }

        $manager->flush();
    }
}