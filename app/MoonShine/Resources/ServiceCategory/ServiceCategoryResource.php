<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\ServiceCategory;

use App\Models\ServiceCategory;
use App\MoonShine\Resources\ServiceCategory\Pages\ServiceCategoryDetailPage;
use App\MoonShine\Resources\ServiceCategory\Pages\ServiceCategoryFormPage;
use App\MoonShine\Resources\ServiceCategory\Pages\ServiceCategoryIndexPage;
use MoonShine\Contracts\Core\PageContract;
use MoonShine\Laravel\Resources\ModelResource;

/**
 * @extends ModelResource<ServiceCategory, ServiceCategoryIndexPage, ServiceCategoryFormPage, ServiceCategoryDetailPage>
 */
class ServiceCategoryResource extends ModelResource
{
    protected string $model = ServiceCategory::class;

    protected string $title = 'Категории услуг';

    protected string $column = 'name';

    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            ServiceCategoryIndexPage::class,
            ServiceCategoryFormPage::class,
            ServiceCategoryDetailPage::class,
        ];
    }

    protected function search(): array
    {
        return [
            'id',
            'name',
            'slug',
        ];
    }
}
