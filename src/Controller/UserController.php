<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    private $encoder;

    public function __construct( UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;
    }
    /**
     * @Route("/user", name="user")
     */
    public function index()
    {
        $entityManager = $this->getDoctrine()->getManager();
        
        $user = new User();
        $user->setName('User 5');
        $user->setEmail('testing2@gmail.com');
        $user->setPassword($this->encoder->encodePassword($user, 'test'));
        
        $entityManager->persist($user);
        $entityManager->flush();

        return new Response('Utilizatorul a fost salvat cu succes. ID: '.$user->getId());
    }
}
