<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\User;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/address/', name: 'app_address_')]
final class AddressController extends AbstractController
{
    #[Route('', name: '')]
    public function listAdresses(Request $request, EntityManagerInterface $em, #[CurrentUser] ?User $user): Response
    {
        return $this->render('address/detail.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('add', name: 'add')]
    public function createAddress(Request $request, #[CurrentUser] ?User $user, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(AddressType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $address = $form->getData();
            dump($address);
            $oldAdresseLivraison = $em->getRepository(Address::class)->findOneBy([
                'zipCode' => $address->getZipCode(),
                'street' => $address->getStreet(),
                'city' => $address->getCity(),
                'country' => $address->getCountry()
            ]);

            if (!$oldAdresseLivraison) {
                $em->persist($address);
                $address->addUser($user);
            } else if ($user->getDeliveryAddresses()->contains($oldAdresseLivraison)) {
                $this->addFlash(
                    'danger',
                    'Adresse déja existante!'
                );
                return $this->redirectToRoute('app_address_add');

            } else {
                $oldAdresseLivraison->addUser($user);
            }
            $em->flush();
            $this->addFlash(
                'success',
                'Ajout réussi!'
            );
            return $this->redirectToRoute('app_address_');
        }

        return $this->render('address/add.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('remove/{id}', name: 'remove' , requirements: ['id'=>'\d+'])]
    public function removeAddress(Address $address, Request $request, EntityManagerInterface $em, #[CurrentUser] ?User $user): Response
    {
        $user->removeDeliveryAddress($address);
        $em->persist($user);
        $em->flush();
        $this->addFlash(
            'success',
            'Supression réussi!'
        );
        return $this->redirectToRoute('app_address_');

    }
}
