<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use App\Models\Lead;
use App\Models\Page as StaticPage;
use App\Models\Portfolio;
use App\Models\Review;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\TeamMember;
use App\MoonShine\Resources\Lead\LeadResource;
use App\MoonShine\Resources\Page\PageResource;
use App\MoonShine\Resources\Portfolio\PortfolioResource;
use App\MoonShine\Resources\Review\ReviewResource;
use App\MoonShine\Resources\Service\ServiceResource;
use App\MoonShine\Resources\ServiceCategory\ServiceCategoryResource;
use App\MoonShine\Resources\TeamMember\TeamMemberResource;
use MoonShine\Laravel\Pages\Page;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Components\Heading;
use MoonShine\UI\Components\Layout\Column;
use MoonShine\UI\Components\Layout\Flex;
use MoonShine\UI\Components\Layout\Grid;
use MoonShine\UI\Components\Link;
use MoonShine\UI\Components\Metrics\Wrapped\ValueMetric;
#[\MoonShine\MenuManager\Attributes\SkipMenu]

class Dashboard extends Page
{
    /**
     * @return array<string, string>
     */
    public function getBreadcrumbs(): array
    {
        return [
            '#' => $this->getTitle()
        ];
    }

    public function getTitle(): string
    {
        return $this->title ?: 'Информационная панель';
    }

    /**
     * @return list<ComponentContract>
     */
    protected function components(): iterable
	{
		return [
            Heading::make('Обзор проекта', 2),

            Grid::make([
                Column::make(
                    [
                        ValueMetric::make('Услуги')->value(Service::query()->count()),
                    ],
                    colSpan: 4,
                    adaptiveColSpan: 6
                ),
                Column::make(
                    [
                        ValueMetric::make('Портфолио')->value(Portfolio::query()->count()),
                    ],
                    colSpan: 4,
                    adaptiveColSpan: 6
                ),
                Column::make(
                    [
                        ValueMetric::make('Страницы')->value(StaticPage::query()->count()),
                    ],
                    colSpan: 4,
                    adaptiveColSpan: 6
                ),
                Column::make(
                    [
                        ValueMetric::make('Новые лиды')->value(Lead::query()->where('status', 'new')->count()),
                    ],
                    colSpan: 4,
                    adaptiveColSpan: 6
                ),
                Column::make(
                    [
                        ValueMetric::make('Отзывы')->value(Review::query()->count()),
                    ],
                    colSpan: 4,
                    adaptiveColSpan: 6
                ),
                Column::make(
                    [
                        ValueMetric::make('Команда')->value(TeamMember::query()->count()),
                    ],
                    colSpan: 4,
                    adaptiveColSpan: 6
                ),
            ])->class('mb-6'),

            Heading::make('Быстрое создание', 3)->class('mb-4'),

            Flex::make([
                Link::make($this->resourceFormUrl(ServiceCategoryResource::class), 'Добавить категорию')->button(),
                Link::make($this->resourceFormUrl(ServiceResource::class), 'Добавить услугу')->button()->filled(),
                Link::make($this->resourceFormUrl(PortfolioResource::class), 'Добавить портфолио')->button()->filled(),
                Link::make($this->resourceFormUrl(PageResource::class), 'Добавить страницу')->button()->filled(),
            ], justifyAlign: 'start'),
        ];
	}

    private function resourceFormUrl(string $resourceClass): string
    {
        $resource = moonshine()->getResources()->findByClass($resourceClass);

        if ($resource === null || ! method_exists($resource, 'getFormPageUrl')) {
            return '#';
        }

        return $resource->getFormPageUrl();
    }
}
