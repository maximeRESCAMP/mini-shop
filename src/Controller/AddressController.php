<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\User;
use App\Exception\AddressAlreadyExistsException;
use App\Form\AddressType;
use App\Service\AddressService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/address', name: 'app_address_')]
final class AddressController extends AbstractController
{
    public function __construct(private readonly AddressService $addressService)
    {
    }

    #[Route('', name: 'list', methods: ['GET'])]
    public function index(#[CurrentUser] ?User $user): Response
    {
        return $this->render('address/index.html.twig', [
            'addresses' => $this->addressService->findByUser($user)
        ]);
    }

    #[Route('/add', name: 'add', methods: ['GET', 'POST'])]
    public function create(Request $request, #[CurrentUser] ?User $user): Response
    {
        $form = $this->createForm(AddressType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $address = $form->getData();
            try {
                $this->addressService->ensureUniqueForUser($address, $user);
                $address = $this->addressService->assignUser($address, $user);
                $this->addressService->save($address);
                $this->addFlash(
                    'success',
                    'Ajout réussi!'
                );
                return $this->redirectToRoute('app_address_list');
            } catch (AddressAlreadyExistsException $th) {
                $form->addError(new FormError($th->getMessage()));
            }

        }
        return $this->render('address/add.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/update/{id}', name: 'update', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(Address $address, #[CurrentUser] ?User $user, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(AddressType::class, $address);

        try {
            $isExist = $this->addressService->userOwnsAddress($address, $user);
            if (!$isExist) {
                throw $this->createAccessDeniedException();
            }
        }catch (AddressAlreadyExistsException $th) {
            return $this->render('address/add.html.twig', [
                'form' => $form,
            ]);
        }

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $addressForm = $form->getData();
            try {
                $this->addressService->ensureUniqueForUser($addressForm, $user);
                $isExist = $this->addressService->userOwnsAddress($addressForm, $user);
                if (!$isExist) {
                    throw $this->createAccessDeniedException();
                } else {
                    $this->addressService->save($addressForm);
                }

                $this->addFlash(
                    'success',
                    'Modification réussi!'
                );
                return $this->redirectToRoute('app_address_list');

            } catch (AddressAlreadyExistsException $th) {
                $form->addError(new FormError($th->getMessage()));

            }

        }
        return $this->render('address/add.html.twig', [
            'form' => $form,
        ]);

    }

    #[Route('/delete/{id}', name: 'delete', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function delete(Address $address, #[CurrentUser] ?User $user): Response
    {
        $isExist = $this->addressService->userOwnsAddress($address, $user);
        if (!$isExist) {
            throw $this->createAccessDeniedException();
        } else {
            $address = $this->addressService->dissociateAddressFromUser($user, $address);
            $this->addressService->save($address);
            $this->addFlash(
                'success',
                'Supression réussi!'
            );
        }

        return $this->redirectToRoute('app_address_list');
    }


}
