@extends("layout.frontend.master")

@section("content")
    <script type="text/javascript">
        var event_to_track = 'click_comparison_page';
    </script>
    <div class="show-popup">
        <h2>Full Car Specifications</h2>
        @include('layout.frontend.partials.popup-compare')
    </div>
@stop



