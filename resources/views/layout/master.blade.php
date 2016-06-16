<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>IAG Limited</title>

    <link href="{{ elixir('css/app.css') }}" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    @if(isset($ajax_csrf_token))
    <meta name="csrf_token" content="{{ csrf_token() }}"/>
    @endif

    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga';);

        ga('create', 'UA-77190861-1', 'auto');
        ga('send', 'pageview');

    </script>

</head>
<body>
@include('layout.partials.nav')

<div class="container-fluid">
    @yield('content')
</div>

<footer class="footer navbar-fixed-bottom">
    <div class="container">
        <p class="text-muted">Copyright &copy;
            2015 {!! (Carbon\Carbon::now()->year == "2015") ? "" : "- ".Carbon\Carbon::now()->year !!} </p>
    </div>
</footer>

<!-- Scripts -->
<script type='text/javascript'>
    (function (d, t) {
        var bh = d.createElement(t), s = d.getElementsByTagName(t)[0];
        bh.type = 'text/javascript';
        bh.src = 'https://www.bugherd.com/sidebarv2.js?apikey=oeuxlakmtiko9czfyxybgw';
        s.parentNode.insertBefore(bh, s);
    })(document, 'script');
</script>
<script src="{{ elixir('js/vendor.js') }}"></script>
<script src="{{ elixir('js/app.js') }}"></script>
</body>
</html>