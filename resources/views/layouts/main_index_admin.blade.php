<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../favicon.ico">
    <link rel="icon" type="image/png" href="../favicon.ico">
    <link rel="stylesheet" href="../assets/css/app.css">
    <title>
        @yield('title')
    </title>
    <style>
        .bg-gradient-primary {
            background: var(--secondary-color) !important;
        }

        body {
            display: flex;
            margin: 0;
            padding: 0;
        }

        .sidebar {
            width: 250px;
            background-color: var(--sidebar-bg-color);
            color: var(--sidebar-text-color);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            padding-top: 20px;
            text-align: center;
        }

        main {
            margin-left: 250px;
            padding: 20px;
            flex: 1;
            height: 100vh;
            overflow-y: auto;
        }

        .sidebar h2 {
            padding: 0 20px;
        }

        .sidebar a {
            color: var(--sidebar-text-color);
            display: block;
            padding: 10px 20px;
            text-decoration: none;
        }

        .sidebar a:hover {
            background-color: var(--sidebar-hover-bg-color);
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>

<body>
    @include('partials.index.sidebar')
    <main>
        @yield('main_index')
        {{-- @include('partials.modals.logout_modal') --}}
        @stack('scripts')
        {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
        </script> --}}
        <script src="https://kit.fontawesome.com/7f649d7da1.js" crossorigin="anonymous"></script>
    </main>
</body>

</html>
