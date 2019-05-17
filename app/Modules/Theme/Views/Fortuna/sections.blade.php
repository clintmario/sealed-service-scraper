@section('sections')
    @foreach ($sections as $section)
        @include("Fortuna::section-" . $section)
    @endforeach
@stop