<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\PortfolioImage;

use App\Models\PortfolioImage;
use App\MoonShine\Resources\PortfolioImage\Pages\PortfolioImageDetailPage;
use App\MoonShine\Resources\PortfolioImage\Pages\PortfolioImageFormPage;
use App\MoonShine\Resources\PortfolioImage\Pages\PortfolioImageIndexPage;
use MoonShine\Contracts\Core\PageContract;
use MoonShine\Laravel\Resources\ModelResource;

/**
 * @extends ModelResource<PortfolioImage, PortfolioImageIndexPage, PortfolioImageFormPage, PortfolioImageDetailPage>
 */
class PortfolioImageResource extends ModelResource
{
    protected string $model = PortfolioImage::class;

    protected string $title = 'Изображения портфолио';

    protected string $column = 'caption';

    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            PortfolioImageIndexPage::class,
            PortfolioImageFormPage::class,
            PortfolioImageDetailPage::class,
        ];
    }

    protected function search(): array
    {
        return [
            'id',
            'portfolio_id',
            'caption',
            'image',
        ];
    }
}
