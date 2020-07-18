<!DOCTYPE html>
<html lang="en" dir="{{ LaravelLocalization::getCurrentLocaleDirection() }}">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Sleek Dashboard - Free Bootstrap 4 Admin Dashboard Template and UI Kit. It is very powerful bootstrap admin dashboard, which allows you to build products like admin panels, content management systems and CRMs etc.">

           <title>@yield('title')</title>


    <!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500|Poppins:400,500,600,700|Roboto:400,500" rel="stylesheet" />
    <link href="https://cdn.materialdesignicons.com/4.4.95/css/materialdesignicons.min.css" rel="stylesheet" />


    <!-- PLUGINS CSS STYLE -->
    <link href="{{Dashboard_Assets()}}/plugins/nprogress/nprogress.css" rel="stylesheet" />



    <!-- No Extra plugin used -->



    <link href="{{Dashboard_Assets()}}/plugins/jvectormap/jquery-jvectormap-2.0.3.css" rel="stylesheet" />
    <link href="{{Dashboard_Assets()}}/plugins/data-tables/datatables.bootstrap4.min.css" rel="stylesheet" />


    <link href="{{Dashboard_Assets()}}/plugins/daterangepicker/daterangepicker.css" rel="stylesheet" />
    <link href="{{Dashboard_Assets()}}/plugins/select2/css/select2.min.css" rel="stylesheet" />


    <script src="{{Dashboard_Assets()}}/plugins/jquery/jquery.min.js"></script>

{{-- If Locale Is RTL --}}
{{--    @if(LaravelLocalization::getCurrentLocaleDirection()=='rtl')--}}
    @if(app()->getLocale()=='ar')
    <!-- SLEEK CSS -->
        <link id="sleek-css" rel="stylesheet" href="{{Dashboard_Assets()}}/css/sleek.rtl.css" />
    @else
        <link id="sleek-css" rel="stylesheet" href="{{Dashboard_Assets()}}/css/sleek.css" />
    @endif


    <!-- FAVICON -->
    <link href="{{Dashboard_Assets()}}/img/favicon.png" rel="shortcut icon" />

    <link href="{{Dashboard_Assets()}}/css/drobzone.css" rel="stylesheet" />
    <link href="{{Dashboard_Assets()}}/css/flag.min.css" rel="stylesheet" />
    <link href="{{Dashboard_Assets()}}/plugins/toastr/toastr.min.css" rel="stylesheet" />
    <link href="{{Dashboard_Assets()}}/css/dashboard.css" rel="stylesheet" />

    <!--
      HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries
    -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="{{Dashboard_Assets()}}/plugins/nprogress/nprogress.js"></script>

    <link href="{{Dashboard_Assets()}}/css/main-style.css" rel="stylesheet" />

    @stack('styles')
      <style>
          @media (min-width: 768px) {
              .sidebar-block-non{
                      display: block;
              }
          }
          .sidebar-fixed-offcanvas .sidebar-with-footer, .sidebar-fixed .sidebar-with-footer{
              padding-bottom: 100px!important;
          }
      </style>

</head>


<body class="header-fixed sidebar-fixed sidebar-dark header-light" id="body">

<script>
    NProgress.configure({ showSpinner: false });
    NProgress.start();
</script>



<div id="toaster1"></div>

<div class="wrapper">
