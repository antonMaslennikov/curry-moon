<ul class="sidebar-menu" data-widget="tree">
    {*
    <li class="">
        <a href="/admin/dashboard">
            <i class="fa fa-dashboard"></i>
            <span>Dashboard</span>
        </a>
    </li>
    *}
    <li class="treeview {if $PAGE->reqUrl.1 == "cart"}active{/if}">
        <a href="#">
            <i class="fa fa-fw fa-shopping-cart"></i>
            <span>Заказы</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="/admin/orders/list"><i class="fa fa-list-alt"></i> Список заказов</a></li>
            <li><a href="/admin/orders/create"><i class="fa fa-plus"></i> Добавить заказ</a></li>
        </ul>
    </li>
    <li class="treeview {if $PAGE->reqUrl.1 == "product"}active{/if}">
        <a href="#">
            <i class="fa fa-fw fa-shopping-basket"></i>
            <span>Товары</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="/admin/product/list"><i class="fa fa-list-alt"></i> Список товаров</a></li>
            <li><a href="/admin/product/create"><i class="fa fa-plus"></i> Добавить товар</a></li>
        </ul>
    </li>
    <li class="treeview {if $PAGE->reqUrl.1 == "product_category"}active{/if}">
        <a href="#">
            <i class="fa fa-fw fa-shopping-bag"></i>
            <span>Категории товаров</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="/admin/product_category/list"><i class="fa fa-list-alt"></i> Категории</a></li>
            <li><a href="/admin/product_category/create"><i class="fa fa-plus"></i> Добавить категорию</a></li>
        </ul>
    </li>
    <li class="treeview {if $PAGE->reqUrl.1 == "blog"}active{/if}">
        <a href="#">
            <i class="fa fa-fw fa-folder-open"></i>
            <span>Блоги</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="/admin/blog/list"><i class="fa fa-list-alt"></i> Список</a></li>
            <li><a href="/admin/blog/create"><i class="fa fa-plus"></i> Добавить блог</a></li>
        </ul>
    </li>
    <li class="treeview {if $PAGE->reqUrl.1 == "coupon"}active{/if}">
        <a href="#">
            <i class="fa fa-fw fa-money"></i>
            <span>Купоны</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="/admin/coupon/list"><i class="fa fa-list-alt"></i> Список</a></li>
            <li><a href="/admin/coupon/create"><i class="fa fa-plus"></i> Добавить купон</a></li>
        </ul>
    </li>
</ul>
