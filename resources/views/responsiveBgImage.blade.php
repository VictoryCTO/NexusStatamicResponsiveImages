<?php
//load config breakpoints and max widths
$breakpoints = config('statamic.nexus.responsive-images.breakpoints');
$breakpoint_unit = config('statamic.nexus.responsive-images.breakpoint_unit');
$container_max_widths = config('statamic.nexus.responsive-images.container_max_widths');
?>
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
                background-image: url('{{ \VictoryCTO\NexusResponsiveImages\FileUtils::imageUrl( $el['image'], ['w'=>$maxW, 'h'=>$el['height']] ) }}');
            }
            @endforeach
            @if($i>1)
}
            @endif
        @endforeach
    </style>
@endpush
