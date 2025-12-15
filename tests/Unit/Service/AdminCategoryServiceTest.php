<?php

namespace App\Tests\Unit\Service;

use App\Repository\CategoryRepository;
use App\Service\AdminCategoryService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

class AdminCategoryServiceTest extends TestCase
{

    public function testCreateColumn(): void
    {
        $adminCategoryService = self::createAdminCategoryService();
        $this->assertEquals(['category.list.column.name', 'category.list.column.slug', 'category.list.column.action'], $adminCategoryService->createColumn());

    }

    public function createAdminCategoryService(): AdminCategoryService
    {

        $translator = $this->createMock(TranslatorInterface::class);
        $translator->method('trans')->willReturnCallback(fn($key) => $key);

        return new AdminCategoryService(
            $this->createMock(CategoryRepository::class),
            $this->createMock(EntityManagerInterface::class),
            $translator
        );
    }

}
