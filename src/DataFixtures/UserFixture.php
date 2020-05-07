<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }


    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 2; $i++) {
            $user = new User();
            $user->setUsername('User_'. $i);
            if($i == 0)
                $role[] = 'ROLE_ADMIN';
            else
                $role[] = 'ROLE_USER';
            $user->setRoles($role);
            $password = $this->encoder->encodePassword($user, 'pass_1234');
            $user->setPassword($password);
            $user->setEmail('Email_User_'. $i. '@mail.ru');
            $manager->persist($user);
        }
        $manager->flush();
    }
}
