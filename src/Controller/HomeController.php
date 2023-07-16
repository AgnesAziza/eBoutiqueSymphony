<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(EntityManagerInterface $em = null): Response
    {
        // If EntityManager was not provided, get it from the container
        if($em === null){
            $em = $this->getDoctrine()->getManager();
        }
        
        // Fetch all the productions and categories
        $products = $em->getRepository(Product::class)->findAll();
        $categories = $em->getRepository(Category::class)->findAll();
        
        // Get current user
        $user = $this->getUser();
        
        // Get user first name or set it as 'Guest' if not logged in
        $firstName = $user ? $user->getFirstName() : 'Guest';
        
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'first_name' => $firstName,
            'products' => $products,
            'categories' => $categories,
        ]);
    }
}
