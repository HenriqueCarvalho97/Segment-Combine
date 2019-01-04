<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{

    private $passwordEncoder;

     public function __construct(UserPasswordEncoderInterface $passwordEncoder)
     {
         $this->passwordEncoder = $passwordEncoder;
     }
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $roles[] = 'ROLE_ADMIN';
        $user = new User();
        $user->setEmail('segment@segmentcombine.pt');
        $user->setRoles($roles);
        $user->setPassword($this->passwordEncoder->encodePassword(
                             $user,
             'segment2018combine!'
                     ));

        $manager->persist($user);
        $manager->flush();
    }
}
