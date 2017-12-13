<nav class="navbar navbar-static-top">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
    </a>
    <div class="navbar-custom-menu pull-left">
        {include file="adminlte/menu/system-menu.tpl"}
    </div>
    <!-- Navbar Right Menu -->
    <div class="navbar-custom-menu">

        <ul class="nav navbar-nav">
            {* *}
            <!-- Messages: style can be found in dropdown.less-->
            <li class="dropdown messages-menu">
                {include file="adminlte/menu/messages-menu.tpl"}
            </li>
            {* *}
            {* *
            <!-- Notifications: style can be found in dropdown.less -->
            <li class="dropdown notifications-menu">
                {include file="adminlte/menu/notifications-menu.tpl"}
            </li>
            </li>
            {* *}
            {* *
            <!-- Tasks: style can be found in dropdown.less -->
            <li class="dropdown tasks-menu">
                {include file="adminlte/menu/tasks-menu.tpl"}
            </li>
            {* *}
            <!-- User Account: style can be found in dropdown.less -->
            <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <span class="hidden-xs">Alexander Pierce</span>
                </a>
                <ul class="dropdown-menu">
                    <!-- User image -->
                    <li class="user-header" style="height: auto;">
                        <p>
                            Alexander Pierce - Web Developer
                            <small>Member since Nov. 2012</small>
                        </p>
                    </li>
                    <!-- Menu Body -->
                    <li class="user-body">
                        <div class="row">
                            <div class="col-xs-6 text-center">
                                <a href="#">Followers</a>
                            </div>
                            <div class="col-xs-6 text-center">
                                <a href="#">Sales</a>
                            </div>
                        </div>
                        <!-- /.row -->
                    </li>
                    <!-- Menu Footer-->
                    <li class="user-footer">
                        <div class="pull-left">
                            {* <a href="#" class="btn btn-default btn-flat">Profile</a> *}
                        </div>
                        <div class="pull-right">
                            <a href="#" class="btn btn-default btn-flat">Выход</a>
                        </div>
                    </li>
                </ul>
            </li>
            <!-- Control Sidebar Toggle Button -->
            <li>
                <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
            </li>
        </ul>
    </div>
</nav>