<?php
//load config breakpoints and max widths
$breakpoints = config('statamic.nexus.responsive-images.breakpoints');
$breakpoint_unit = config('statamic.nexus.responsive-images.breakpoint_unit');
$container_max_widths = config('statamic.nexus.responsive-images.container_max_widths');


//parse the data
arsort($container_max_widths, SORT_NUMERIC); //sort this array in reverse order maintaining the indexes
$breakpoint_max_widths = [];
foreach($breakpoints as $bp=>$minW) {
    //handle min-width 0
    if($minW<1) $breakpoint_max_widths[$bp] = min($container_max_widths);
    //otherwise get the largest max container width possible for this breakpoint
    else {
        foreach($container_max_widths as $maxW) {
            if($maxW<=$minW) {
                $breakpoint_max_widths[$bp] = $maxW;
                break;
            }
        }
    }
}

//sanitize and parse passed element values (aka make sure the passed values will not mess up calculations below)
array_walk($elements, function(&$value, $elements) {
    //enforce required elements
    foreach(['element','image','width','height'] as $key) {
        if(!is_array($value) || !array_key_exists($key, $value) || empty($value[$key])) dd('You have a malformed responsive images array', $value, $elements);
    }

    //enforce numeric dimensions
    $value['width'] = str_replace('px', '', $value['width'] );
    $value['height'] = str_replace('px', '', $value['height'] );
    if(!is_numeric($value['width']) || !is_numeric($value['height'])) dd('Height and width must be numeric',$value);

    //calculate ratio
    $value['ratio'] = $value['height'] / $value['width'] ;
});
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
