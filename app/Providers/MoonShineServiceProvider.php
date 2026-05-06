<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use MoonShine\Contracts\Core\DependencyInjection\CoreContract;
use MoonShine\Laravel\DependencyInjection\MoonShine;
use MoonShine\Laravel\DependencyInjection\MoonShineConfigurator;
use App\MoonShine\Resources\MoonShineUser\MoonShineUserResource;
use App\MoonShine\Resources\MoonShineUserRole\MoonShineUserRoleResource;
use App\MoonShine\Resources\ServiceCategory\ServiceCategoryResource;
use App\MoonShine\Resources\Service\ServiceResource;
use App\MoonShine\Resources\Portfolio\PortfolioResource;
use App\MoonShine\Resources\PortfolioImage\PortfolioImageResource;
use App\MoonShine\Resources\Review\ReviewResource;
use App\MoonShine\Resources\Lead\LeadResource;
use App\MoonShine\Resources\TeamMember\TeamMemberResource;
use App\MoonShine\Resources\Setting\SettingResource;
use App\MoonShine\Resources\Page\PageResource;

class MoonShineServiceProvider extends ServiceProvider
{
    /**
     * @param  CoreContract<MoonShineConfigurator>  $core
     */
    public function boot(CoreContract $core): void
    {
        $core
            ->resources([
                MoonShineUserResource::class,
                MoonShineUserRoleResource::class,
                ServiceCategoryResource::class,
                ServiceResource::class,
                PortfolioResource::class,
                PortfolioImageResource::class,
                ReviewResource::class,
                LeadResource::class,
                TeamMemberResource::class,
                SettingResource::class,
                PageResource::class,
            ])
            ->pages([
                ...$core->getConfig()->getPages(),
            ])
        ;
    }
}
