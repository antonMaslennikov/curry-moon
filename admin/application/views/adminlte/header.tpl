<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{$PAGE->title_strip_tags}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="/public/packages/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/public/packages/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="/public/packages/ionicons/css/ionicons.min.css">
    <!-- jvectormap -->
    <link rel="stylesheet" href="/public/packages/jvectormap/jquery-jvectormap.css">

{foreach $PAGE->css item="path" name="cssforeach"}
    <link href="{$path}" rel="stylesheet" type="text/css" />
{/foreach}
    <!-- Theme style -->
    <link rel="stylesheet" href="/public/packages/adminlte/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="/public/packages/adminlte/css/skins/_all-skins.min.css">

    <link rel="stylesheet" href="/public/css/admin.css">



    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <!-- jQuery 3 -->
    <![endif]-->
    <script src="/public/packages/jquery/jquery.min.js"></script>


    {foreach $PAGE->js item="path" name="jsforeach"}
        <script type='text/javascript' src="{$path}"></script>
    {/foreach}

    <!-- Google Font -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

    <script type='text/javascript' src="/public/js/admin.js"></script>
    <link rel="stylesheet" href="/public/css/admin.css">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <header class="main-header">

        <!-- Logo -->
        <a href="/" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>A</b></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>A</b>дминка</span>
        </a>

        <!-- Header Navbar: style can be found in header.less -->
        {if !isset($hide_menu)}
            {include file="adminlte/menu/top-navbar.tpl"}
        {/if}
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- sidebar menu: : style can be found in sidebar.less -->
            {if !isset($hide_menu)}
            {include file="adminlte/menu/sidebar-menu.tpl"}
            {/if}
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>{$PAGE->title}</h1>
            {if isset($breadcrumbs) && strlen($breadcrumbs)}
                <ol class="breadcrumb">
                    {$breadcrumbs}
                </ol>
            {/if}
        </section>

        <!-- Main content -->
        <section class="content">



