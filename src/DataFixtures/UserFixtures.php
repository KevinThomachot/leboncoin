<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{

    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $admin = new User();
        $admin->setUsername('admin');
        $password = $this->encoder->encodePassword($admin, 'admin-password');
        $admin->setPassword($password);
        $admin->setRoles(['ROLE_ADMIN']);
        $this->addReference($admin->getUsername(), $admin);

        $manager->persist($admin);

        for ($i = 1; $i <= 25; $i++) {
            $user = new User();
            $user->setUsername('fake-user' . $i);
            $password = $this->encoder->encodePassword($user, 'fake-password' . $i);
            $user->setPassword($password);

            $this->addReference($user->getUsername(), $user);

            $manager->persist($user);
        }
        $manager->flush($user);
    }
}
