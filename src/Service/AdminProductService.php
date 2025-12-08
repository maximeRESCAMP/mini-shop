<?php

namespace App\Service;

use App\Entity\Product;
use App\Exception\Admin\Product\CannotDeleteProductException;
use App\Exception\Admin\Product\CannotSaveProductException;
use App\Exception\NotGoodExtension;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Contracts\Translation\TranslatorInterface;

readonly class AdminProductService
{

    public function __construct(private EntityManagerInterface $em, private TranslatorInterface $translator)
    {
    }

    /**
     * @throws CannotSaveProductException
     */
    public function save(Product $product): void
    {
        try {
            $this->em->persist($product);
            $this->em->flush();
        } catch (\Throwable $exception) {
            throw new CannotSaveProductException($this->translator->trans(CannotSaveProductException::$messageQuerry));
        }
    }

    /**
     * @throws NotGoodExtension
     */
    public function downloadPicture(Product $product, UploadedFile $file): void
    {
        $someNewFilename = $product->getSlug() . '.jpg';
        if (!in_array($file->guessExtension(), ['jpg', 'jpeg', 'png', 'gif'])) {
            throw new NotGoodExtension($this->translator->trans(NotGoodExtension::$messgeNotGoodExt));
        }
        $directory = 'D:/Projet/miniShop/assets/pictures/';
        $file->move($directory, $someNewFilename);
        $product->setPicture($someNewFilename);
    }


    /**
     * @throws CannotDeleteProductException
     */
    public function remove(Product $product): void
    {
        try {
            $this->em->remove($product);
            $this->em->flush();
        } catch (\Throwable $exception) {
            throw new CannotDeleteProductException($this->translator->trans(CannotDeleteProductException::$messageDelete));
        }
    }

}
