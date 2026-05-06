<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Portfolio;

use App\Models\Portfolio;
use App\MoonShine\Resources\Portfolio\Pages\PortfolioDetailPage;
use App\MoonShine\Resources\Portfolio\Pages\PortfolioFormPage;
use App\MoonShine\Resources\Portfolio\Pages\PortfolioIndexPage;
use MoonShine\Contracts\Core\PageContract;
use MoonShine\Laravel\Resources\ModelResource;

/**
 * @extends ModelResource<Portfolio, PortfolioIndexPage, PortfolioFormPage, PortfolioDetailPage>
 */
class PortfolioResource extends ModelResource
{
    protected string $model = Portfolio::class;

    protected string $title = 'Портфолио';

    protected string $column = 'title';

    protected array $with = ['service'];

    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            PortfolioIndexPage::class,
            PortfolioFormPage::class,
            PortfolioDetailPage::class,
        ];
    }

    protected function search(): array
    {
        return [
            'id',
            'title',
            'slug',
            'client_name',
            'location',
        ];
    }
}
