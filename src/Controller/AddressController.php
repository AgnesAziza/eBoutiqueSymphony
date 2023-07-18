<?php

namespace App\Controller;

use App\Form\CustomerAddressType;
use App\Entity\CustomerAddress;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddressController extends AbstractController
{
    #[Route('/address/add', name: 'app_address_add')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $address = new CustomerAddress();
    
        $form = $this->createForm(CustomerAddressType::class, $address);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // associer l'adresse à l'utilisateur actuellement connecté
            $address->setUser($this->getUser());
    
            $entityManager->persist($address);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_cart');  
        }
    
        return $this->render('address/index.html.twig', [
            'addressForm' => $form->createView(),
        ]);
    }

    #[Route('/address/edit/{id}', name: 'app_address_edit')]
    public function edit(CustomerAddress $address, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CustomerAddressType::class, $address);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
    
            return $this->redirectToRoute('app_address_list');  // rediriger vers la liste des adresses
        }
    
        return $this->render('address/edit.html.twig', [
            'addressForm' => $form->createView(),
        ]);
    }
    

    #[Route('/address/list', name: 'app_address_list')]
    public function list(): Response
    {
        // Récupère l'utilisateur actuellement connecté
        $user = $this->getUser();

        // Si l'utilisateur n'est pas connecté, le redirige vers la page de connexion
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Récupère les adresses de l'utilisateur
        $addresses = $user->getAddresses();

        // Renvoie la vue avec les adresses de l'utilisateur
        return $this->render('address/list.html.twig', [
            'addresses' => $addresses,
        ]);
    }
    
}
