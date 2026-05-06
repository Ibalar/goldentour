<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'ConstructionBusiness',
    'name' => \App\Models\Setting::get('company_name', 'Золотой Тур'),
    'url' => url('/'),
    'telephone' => \App\Models\Setting::get('company_phone'),
    'email' => \App\Models\Setting::get('company_email'),
    'address' => [
        '@type' => 'PostalAddress',
        'streetAddress' => \App\Models\Setting::get('company_address'),
        'addressLocality' => 'Минск',
        'addressCountry' => 'BY',
    ],
    'openingHoursSpecification' => [
        '@type' => 'OpeningHoursSpecification',
        'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
        'opens' => '09:00',
        'closes' => '18:00',
    ],
    'priceRange' => '$$',
    'aggregateRating' => [
        '@type' => 'AggregateRating',
        'ratingValue' => '4.8',
        'reviewCount' => (string) \App\Models\Review::published()->count(),
    ],
], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT) !!}
</script>
