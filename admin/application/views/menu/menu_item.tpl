    <div class="nav-tabs-custom ">
        <ul class="nav nav-tabs">

            {foreach from=$menu.menu item=m}
            <li {if $m.id==$menu.menu_id}class="active"{/if}>
                <a href="?menu_id={$m.id}">{$m.name}</a>
            </li>
            {/foreach}
            <li class="pull-right header"><a href="/admin/menu/item/create?menu_id={$menu.menu_id}" class="btn btn-success btn-sm">Добавить пункт меню</a></li>
        </ul>

        <div class="tab-content table-responsive no-padding">
            <table class="table table-hover">
                <tbody><tr>
                    <th>Сортировка</th>
                    <th>Название</th>
                    <th>Адрес</th>
                    <th>Статус</th>
                    <th></th>
                </tr>
                {foreach from=$menu.list item=item}
                    <tr>
                        <td>{$item.sort}</td>
                        <td>{$item.title_ru}</td>
                        <td>{$item.url}</td>
                        <td>
                            {if $item.status}
                                <span class="label label-success">Активно</span>
                            {else}
                                <span class="label label-danger">Не активно</span>
                            {/if}
                        </td>
                        <td>
                        <span class="pull-right">
                            <a href="/admin/menu/item/update?id={$item.id}" class="btn btn-warning btn-xs" title="Изменить меню"><i class="fa fa-fw fa-pencil"></i></a>
                            <a href="/admin/menu/item/delete?id={$item.id}" class="btn btn-danger btn-xs delete-js" title="Удалить меню"><i class="fa fa-fw fa-times"></i></a>
                        </span>
                        </td>
                    </tr>
                {/foreach}
                </tbody>
            </table>
        </div>
    </div>
{literal}
    <script type="text/javascript">
        !function ($) {
            $(function() {

                $('table').on('click', '.delete-js',  function(e){

                    if (!confirm('Вы действительно желаете удалить страницу?')) {

                        e.preventDefault();
                        return false;
                    }

                    return true;
                })
            })
        }(window.jQuery)
    </script>
{/literal}