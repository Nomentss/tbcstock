<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('admin_assets/css/styles.css') }}">
    <title>Just for fun lesy dada a!!</title>
</head>

<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar-->
        @include('Layout.sidebar')

        <!-- Page content wrapper-->
        <div id="page-content-wrapper">
            <!-- Top navigation-->
            @include('Layout.Topbar')

            <!-- Page content-->
            <div class="container-fluid">

                @yield('contents')
                
            </div>
        </div>
    </div>
    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="{{ asset('admin_assets/js/scripts.js') }}"></script>
</body>
</html>