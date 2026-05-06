<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Review;

use App\Models\Review;
use App\MoonShine\Resources\Review\Pages\ReviewDetailPage;
use App\MoonShine\Resources\Review\Pages\ReviewFormPage;
use App\MoonShine\Resources\Review\Pages\ReviewIndexPage;
use MoonShine\Contracts\Core\PageContract;
use MoonShine\Laravel\Resources\ModelResource;

/**
 * @extends ModelResource<Review, ReviewIndexPage, ReviewFormPage, ReviewDetailPage>
 */
class ReviewResource extends ModelResource
{
    protected string $model = Review::class;

    protected string $title = 'Отзывы';

    protected string $column = 'author_name';

    protected array $with = ['service', 'portfolio'];

    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            ReviewIndexPage::class,
            ReviewFormPage::class,
            ReviewDetailPage::class,
        ];
    }

    protected function search(): array
    {
        return [
            'id',
            'author_name',
            'author_phone',
            'author_email',
            'text',
        ];
    }
}
