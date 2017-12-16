<div class="box">
    <form class="search" method="get" action="{currenturl page=0}">
        <div class="box-header">
            <h3 class="box-title">Список</h3>
            <div class="box-tools">
                <a href="employees" class="btn btn-warning btn-sm">Сотрудники</a>
                <a href="create" class="btn btn-success btn-sm">Добавить пользователя</a>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
                <tbody>
                <tr>
                    <th>Фамилия имя отчество</th>
                    <th>Email</th>
                    <th>Телефон</th>
                    <th>Статус</th>
                    <th>Активация</th>
                    <th></th>
                </tr>
                <tr>
                    <th><input type="text" name="search[user_name]" class="form-control input-sm" value="{$users.search.user_name}"></th>
                    <th><input type="text" name="search[user_email]" class="form-control input-sm" value="{$users.search.user_email}"></th>
                    <th><input type="text" name="search[user_phone]" class="form-control input-sm" value="{$users.search.user_phone}"></th>
                    <th colspan="3">
                        <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-search"></i>&nbsp;Поиск</button>&nbsp;
                        <a href="list" class="btn btn-default btn-sm">Очистить</a>
                    </th>
                </tr>
                {foreach from=$users.data item=record}
                    <tr>
                        <td>{$record.user_name}</td>
                        <td>{$record.user_email}</td>
                        <td>{$record.user_phone}</td>
                        <td>{if isset($statusList[$record.user_status])}{$statusList[$record.user_status]}{else}<span class="label label-default">не известно</span>{/if}</td>
                        <td>{if isset($activationList[$record.user_activation])}{$activationList[$record.user_activation]}{else}<span class="label label-default">не известно</span>{/if}</td>
                        <td class="col-sm-1">
                        <span class="pull-right">
                            <a href="update?id={$record.id}" class="btn btn-warning btn-xs" title="Изменить запись"><i class="fa fa-fw fa-pencil"></i></a>
                            <a href="delete?id={$record.id}" class="btn btn-danger btn-xs delete-js" title="Удалить запись"><i class="fa fa-fw fa-times"></i></a>
                        </span>
                        </td>
                    </tr>
                {/foreach}
                </tbody>
            </table>
        </div>

        <!-- /.box-body -->
        <div class="box-footer">
            <div class="row">
                <div class="col-sm-3">Показаны {$users.page.offset+1} - {$users.page.offset + count($users.data)} из {$users.page.itemCount}</div>
                <div class="col-sm-3">
                    <label>Показать <select name="pageSize" class="input-search input-sm">
                    {foreach from=$users.page.pageSizeList item=option}
                            <option {if $option==$users.page.pageSize}selected{/if} value="{$option}">{$option}</option>
                    {/foreach}
                        </select> записей</label>
                </div>
                <div class="col-sm-6">
                    {include file='../adminlte/paginator.tpl' pagination=$users.page}
                </div>
            </div>
        </div>
    </form>
</div>
<!-- /.box -->
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

                $('div.box').on('change', '.input-search', function() {

                    $('form.search').submit();
                })
            })
        }(window.jQuery)
    </script>
{/literal}