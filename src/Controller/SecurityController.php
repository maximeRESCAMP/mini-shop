<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/ ', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/register', name: 'app_register')]
    public function register(Request $request, EntityManagerInterface $em): Response
    {
        $user = new User();
        $user->addDeliveryAddress(new Address());
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()&& $form->isValid()) {
            $user = $form->getData();
            $newAdresseLivraison = $user->getDeliveryAddresses()->first();
            $oldAdresseLivraison = $em->getRepository(Address::class)->findOneBy([
                'zipCode' => $newAdresseLivraison->getZipCode(),
                'street' => $newAdresseLivraison->getStreet(),
                'city' => $newAdresseLivraison->getCity(),
                'country' => $newAdresseLivraison->getCountry()
            ]);
            if ($oldAdresseLivraison) {
                $user->removeDeliveryAddress($newAdresseLivraison);
                $user->addDeliveryAddress($oldAdresseLivraison);
            }else{
                $em->persist($newAdresseLivraison);
            }

            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/register.html.twig', [
            'form' => $form,
        ]);
    }


}
