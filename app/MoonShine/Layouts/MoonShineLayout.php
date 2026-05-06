<?php

declare(strict_types=1);

namespace App\MoonShine\Layouts;

use MoonShine\Laravel\Layouts\AppLayout;
use MoonShine\ColorManager\Palettes\PurplePalette;
use MoonShine\ColorManager\ColorManager;
use MoonShine\Contracts\ColorManager\ColorManagerContract;
use MoonShine\Contracts\ColorManager\PaletteContract;
use App\MoonShine\Resources\ServiceCategory\ServiceCategoryResource;
use MoonShine\MenuManager\MenuItem;
use App\MoonShine\Resources\Service\ServiceResource;
use App\MoonShine\Resources\Portfolio\PortfolioResource;
use App\MoonShine\Resources\Review\ReviewResource;
use App\MoonShine\Resources\Lead\LeadResource;
use App\MoonShine\Resources\TeamMember\TeamMemberResource;
use App\MoonShine\Resources\Setting\SettingResource;
use App\MoonShine\Resources\Page\PageResource;

final class MoonShineLayout extends AppLayout
{
    /**
     * @var null|class-string<PaletteContract>
     */
    protected ?string $palette = PurplePalette::class;

    protected function assets(): array
    {
        return [
            ...parent::assets(),
        ];
    }

    protected function menu(): array
    {
        return [
            MenuItem::make(ServiceCategoryResource::class, 'Категории услуг'),
            MenuItem::make(ServiceResource::class, 'Услуги'),
            MenuItem::make(PortfolioResource::class, 'Портфолио'),
            MenuItem::make(ReviewResource::class, 'Отзывы'),
            MenuItem::make(LeadResource::class, 'Заявки'),
            MenuItem::make(TeamMemberResource::class, 'Команда'),
            MenuItem::make(SettingResource::class, 'Настройки сайта'),
            MenuItem::make(PageResource::class, 'Страницы'),
            ...parent::menu(),
        ];
    }

    /**
     * @param ColorManager $colorManager
     */
    protected function colors(ColorManagerContract $colorManager): void
    {
        parent::colors($colorManager);

        // $colorManager->primary('#00000');
    }
}
