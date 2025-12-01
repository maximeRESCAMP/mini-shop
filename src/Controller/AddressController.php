<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\User;
use App\Exception\Address\AddressAlreadyExistsException;
use App\Exception\Address\AddressUserNotFoundException;
use App\Exception\Address\CannotQueryAddressException;
use App\Exception\Address\CannotSaveAddressException;
use App\Form\AddressType;
use App\Security\AddressVoter;
use App\Service\AddressService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

    /**
     * @throws AddressUserNotFoundException
     */
    #[Route('', name: 'list', methods: ['GET'])]
    public function index(#[CurrentUser] User $user): Response
    {
        return $this->render('address/index.html.twig', [
            'addresses' => $this->addressService->findByUser($user),
            'nom_col' => ['Pays','Code Postal','Ville','Rue','Action'],
        ]);
    }

    /**
     * @throws AddressAlreadyExistsException
     * @throws CannotQueryAddressException
     * @throws CannotSaveAddressException
     */
    #[Route('/add', name: 'add', methods: ['GET', 'POST'])]
    public function create(Request $request, #[CurrentUser] User $user): Response
    {
        $form = $this->createForm(AddressType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $address = $form->getData();
            $this->addressService->uniqueForUser($address, $user);
            $this->addressService->assignUser($address, $user);
            $this->addFlash(
                'success',
                'Ajout de l\'adreese réussi!'
            );
            return $this->redirectToRoute('app_address_list');
        }
        return $this->render('address/form.html.twig', [
            'form' => $form,
            'is_update' => false,
        ]);
    }


    /**
     * @throws AddressAlreadyExistsException
     * @throws CannotQueryAddressException|CannotSaveAddressException
     */
    #[Route('/update/{id}', name: 'update', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(Address $address, #[CurrentUser] ?User $user, Request $request): Response
    {
        $this->denyAccessUnlessGranted(AddressVoter::EDIT, $address);
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $addressForm = $form->getData();
            $this->addressService->uniqueForUser($addressForm, $user);
            $this->addressService->save($address);
            $this->addFlash(
                'success',
                'Modification de l\'adresse réussi!'
            );
            return $this->redirectToRoute('app_address_list');
        }
        return $this->render('address/form.html.twig', [
            'form' => $form,
            'is_update' => true,
        ]);

    }

    /**
     * @throws CannotSaveAddressException
     */
    #[Route('/delete/{id}', name: 'delete', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function delete(Address $address, #[CurrentUser] ?User $user): Response
    {
        $this->denyAccessUnlessGranted(AddressVoter::DELETE, $address);
        $this->addressService->dissociateAddressFromUser($address);
        $this->addFlash(
            'success',
            'Supression de l\'adresse réussi!'
        );

        return $this->redirectToRoute('app_address_list');
    }


}
