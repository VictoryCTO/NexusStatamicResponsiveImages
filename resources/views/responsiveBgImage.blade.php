@push('styles')
    <style>
        @php( $i = 0 )
        @foreach($breakpoints as $bp => $minW)
            @php( $i++ )
            @php( $maxW = $breakpoint_max_widths[$bp] )
            @if($i>1)
                @media (min-width: {{ $minW }}{{ $breakpoint_unit }}) {
            @endif
            @foreach($elements as $el)
                @php( $maxH = $el['ratio'] * $maxW )
                    {!! $el['element'] !!} {
                background-image: url('{{ \VictoryCTO\NexusResponsiveImages\FileUtils::imageUrl( $el['image'], ['w'=>min($el['width'], $maxW ), 'h'=>min($el['height'], $maxW )] ) }}');
            }
            @endforeach
            @if($i>1)
}
            @endif
        @endforeach
    </style>
@endpush
