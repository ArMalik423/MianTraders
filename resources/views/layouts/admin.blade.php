<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="assets/images/favicon-32x32.png" type="image/png" />
  <!--plugins-->
  <link href="{{asset('backend_view/assets/plugins/metisMenu/css/metisMenu.min.css')}}" rel="stylesheet" />
  <link href="{{asset('backend_view/assets/plugins/datatable/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet" />
  <!-- Bootstrap CSS -->
  <link href="{{asset('backend_view/assets/css/bootstrap.min.css')}}"  rel="stylesheet" />
  <link href="{{asset('backend_view/assets/css/bootstrap-extended.css')}}"  rel="stylesheet" />
  <link href="{{asset('backend_view/assets/css/style.css')}}" rel="stylesheet" />
  <link href="{{asset('backend_view/assets/css/icons.css')}}" rel="stylesheet" />
  <link href="{{asset('backend_view/assets/css/custom.css')}}" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
  <link rel="stylesheet" href="{{ asset('backend_view/assets/css/toastr.min.css') }}">
  
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

  


  <title>Husnain Traders</title>
</head>

<body>
    <div class="wrapper">
        @include('layouts._partials._header')
        @include('layouts._partials._admin_sidebar')
        @yield('content')
    </div>
  
  <script>var baseURL = <?php echo json_encode(url('/')); ?>  </script>
  <script src="{{asset('backend_view/assets/js/bootstrap.bundle.min.js')}}"></script>
  <!--plugins-->
  <script src="{{asset('backend_view/assets/js/jquery.min.js')}}"></script>
  <script src="{{asset('backend_view/assets/plugins/metisMenu/js/metisMenu.min.js')}}"></script>
  <script src="{{asset('backend_view/assets/plugins/datatable/js/dataTables.bootstrap5.min.js')}}"></script>
  <script src="{{asset('backend_view/assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
  <script src="{{ asset('backend_view/assets/js/custom.js') }}"></script>
  <script src="{{ asset('backend_view/assets/js/toastr.min.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="{{ asset('js/app.js') }}"></script>
  <script>
      $(document).ready(function () {
          $('#example').DataTable();
      });
    </script>

  
</body>
</html>