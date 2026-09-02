@props(['items' => []])

@if(!empty($items))
@php
    $itemListElement = [];
    $position = 1;
    foreach ($items as $item) {
        $itemListElement[] = [
            '@type'    => 'ListItem',
            'position' => $position,
            'name'     => $item['name'],
            'item'     => $item['url'] ?? null,
        ];
        $position++;
    }

    $breadcrumbSchema = [
        '@context'        => 'https://schema.org',
        '@type'           => 'BreadcrumbList',
        'itemListElement' => $itemListElement,
    ];
@endphp

    <!-- Breadcrumbs Visual Navigation -->
    <nav aria-label="Breadcrumb" style="padding: 15px 0; font-size: 11px; letter-spacing: 1.5px; text-transform: uppercase;">
        <ol style="list-style: none; display: flex; flex-wrap: wrap; gap: 8px; align-items: center; margin: 0; padding: 0;">
            @foreach($items as $index => $item)
                @if(!$loop->last && !empty($item['url']))
                    <li>
                        <a href="{{ $item['url'] }}" style="color: #94a3b8; text-decoration: none; transition: color 0.3s;" onmouseover="this.style.color='#c4a472'" onmouseout="this.style.color='#94a3b8'">
                            {{ $item['name'] }}
                        </a>
                        <span style="color: #64748b; margin-left: 8px;">/</span>
                    </li>
                @else
                    <li style="color: #c4a472; font-weight: 600;" aria-current="page">
                        {{ $item['name'] }}
                    </li>
                @endif
            @endforeach
        </ol>
    </nav>

    <!-- BreadcrumbList JSON-LD Schema -->
    <script type="application/ld+json">
    {!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>
@endif
