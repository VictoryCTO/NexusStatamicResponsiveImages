@push('styles')
    <style>
        @php( $i = 0 )
        @foreach($breakpoints as $bp => $minW)
            @php( $i++ )
            @if($i>1)
                @media (min-width: {{ $minW }}{{ $breakpoint_unit }}) {
            @endif
            @foreach($elements as $el)
                {!! $el['element'] !!} {
                    background-image: url('{{ $el['sources'][$bp] }}');
                }
            @endforeach
            @if($i>1)
                }
            @endif
        @endforeach
    </style>
@endpush
