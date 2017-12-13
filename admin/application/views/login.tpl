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

    {foreach $PAGE->css item="path" name="cssforeach"}
        <link href="{$path}" rel="stylesheet" type="text/css" />
    {/foreach}
    <!-- Theme style -->
    <link rel="stylesheet" href="/public/packages/adminlte/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="/public/packages/adminlte/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="/public/plugins/iCheck/square/blue.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <!-- jQuery 3 -->
    <script src="/public/packages/jquery/jquery.min.js"></script>
    <![endif]-->

    {foreach $PAGE->js item="path" name="jsforeach"}
        <script type='text/javascript' src="{$path}"></script>
    {/foreach}

    <!-- Google Font -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

    <style>
        ul {
            list-style: none;
            padding: 0;
        }
    </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="/admin/dashboard.html">{siteName}</a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        {if $PAGE->tpl}
            {include file=$PAGE->tpl}
        {else}
            Не указан шаблон страницы
        {/if}
    </div>
</div>
<!-- /.login-box -->
</body>
</html>
<script src="/public/packages/jquery/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="/public/packages/bootstrap/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="/public/plugins/iCheck/icheck.min.js"></script>
<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    });
</script>
