<div class="col-xs-12">
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Список</h3>
            <div class="box-tools">
                <a href="list" class="btn btn-warning btn-sm">Все пользователи</a>
                <a href="create" class="btn btn-success btn-sm">Добавить пользователя</a>
            </div>
        </div>
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
                <tbody>
                <tr>
                    <th>Фамилия имя отчество</th>
                    <th>Email</th>
                    <th>Телефон</th>
                    <th>Статус</th>
                    <th></th>
                </tr>
            {foreach from=$users.data item=record}
                <tr>
                    <td>{$record.user_name}</td>
                    <td>{$record.user_email}</td>
                    <td>{$record.user_phone}</td>
                    <td>{if isset($statusList[$record.user_status])}{$statusList[$record.user_status]}{else}<span class="label label-default">не известно</span>{/if}</td>
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
    </div>
</div>