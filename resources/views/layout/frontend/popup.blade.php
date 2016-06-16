<!DOCTYPE html>
<html dir="ltr" lang="en-US" id="full-page">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=1080" />

    <title>IAG</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/stylesheets/css/application.min.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/stylesheets/css/fonts/fonts.css')}}" />
    <style type="text/css">
        #popup-box, #chart-box {
            display: block !important;
            left: 0px !important;
            top: 0px !important;
            margin-left: 0px !important;
            margin-top: 0px !important;
            transform: scale(1) !important;
        }
        #popup-box .close-btn, #chart-box .close-btn { display: none !important; }
    </style>
</head>

<body>
    @yield('content')
</body>

</html>

