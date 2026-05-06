<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Service;

use App\Models\Service;
use App\MoonShine\Resources\Service\Pages\ServiceDetailPage;
use App\MoonShine\Resources\Service\Pages\ServiceFormPage;
use App\MoonShine\Resources\Service\Pages\ServiceIndexPage;
use MoonShine\Contracts\Core\PageContract;
use MoonShine\Laravel\Resources\ModelResource;

/**
 * @extends ModelResource<Service, ServiceIndexPage, ServiceFormPage, ServiceDetailPage>
 */
class ServiceResource extends ModelResource
{
    protected string $model = Service::class;

    protected string $title = 'Услуги';

    protected string $column = 'name';

    protected array $with = ['category'];

    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            ServiceIndexPage::class,
            ServiceFormPage::class,
            ServiceDetailPage::class,
        ];
    }

    protected function search(): array
    {
        return [
            'id',
            'name',
            'slug',
            'short_description',
        ];
    }
}
