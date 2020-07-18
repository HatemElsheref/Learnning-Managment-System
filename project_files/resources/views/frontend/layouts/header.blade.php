<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="VANNO - Premium directory consumer reviews and listings template by Ansonika">
    <meta name="author" content="Ansonika">
    @stack('meta')
        <title>
        @yield('title')
    </title>


    <link rel="shortcut icon" href="{{frontend()}}img/favicon.ico" type="mage/x-icon">
    <link rel="apple-touch-icon" type="image/x-icon" href="{{frontend()}}img/apple-touch-icon-57x57-precomposed.png">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="72x72" href="{{frontend()}}img/apple-touch-icon-72x72-precomposed.png">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="114x114" href="{{frontend()}}img/apple-touch-icon-114x114-precomposed.png">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="144x144" href="{{frontend()}}img/apple-touch-icon-144x144-precomposed.png">

    <!-- GOOGLE WEB FONT -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    <!-- BASE CSS -->
    <link href="{{frontend()}}css/bootstrap.min.css" rel="stylesheet">
    <link href="{{frontend()}}css/style.css" rel="stylesheet">
    <link href="{{frontend()}}css/vendors.css" rel="stylesheet">
    <link rel="stylesheet" href="{{frontend()}}css/wow.css">
    @stack('css_before')

    <!-- YOUR CUSTOM CSS -->
    <link href="{{frontend()}}css/custom.css" rel="stylesheet">

    <!--    <link href="includes/scrollbar.css" rel="stylesheet">-->


    @stack('css_after')
          <style>
              /*@media (min-width: 1200px){*/
              /*    .container{*/
              /*        max-width: 1400px;*/
              /*    }*/
              /*}*/
          </style>
</head>

<body>

