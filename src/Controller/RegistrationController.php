<?php

// Déclare le namespace pour ce contrôleur. Il est dans le dossier `src/Controller`.
namespace App\Controller;

// Utilise les classes nécessaires pour le contrôleur.
use App\Entity\User;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;

// Définit la classe `RegistrationController` qui hérite de `AbstractController`.
class RegistrationController extends AbstractController
{
// Annonce une nouvelle méthode publique `register()` qui est mappée à la route `/registration`.
#[Route('/registration', name: 'app_registration')]
public function register(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
{
    // Crée une nouvelle instance de la classe `User`.
    $user = new User();

    // Crée le formulaire d'inscription avec cette instance d'utilisateur.
    $form = $this->createForm(RegistrationFormType::class, $user);

    // Traite la requête HTTP entrante avec la méthode `handleRequest()` du formulaire.
    $form->handleRequest($request);

    // Vérifie si le formulaire a été soumis et est valide.
    if ($form->isSubmitted() && $form->isValid()) {
        // Si oui, il hache le mot de passe de l'utilisateur avec la méthode `hashPassword()`.
        $user->setPassword(
            $passwordHasher->hashPassword(
                $user,
                $form->get('Password')->getData() // Récupère les données du mot de passe du formulaire.
            )
        );

        // Obtient le gestionnaire d'entité Doctrine à partir du contrôleur.
        //$entityManager = $this->getDoctrine()->getManager();

        // Persiste l'instance d'utilisateur dans la base de données.
        $entityManager->persist($user);

        // Flushe (envoie à la base de données) tous les changements que vous avez effectués sur vos entités.
        $entityManager->flush();

        // Redirige l'utilisateur vers la page de connexion après une inscription réussie.
        return $this->redirectToRoute('app_login');
    }

    // Rend le template d'inscription avec le formulaire d'inscription.
    return $this->render('registration/index.html.twig', [
        'registrationForm' => $form->createView(),
    ]);
}

}
