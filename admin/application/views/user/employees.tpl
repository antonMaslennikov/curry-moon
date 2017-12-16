<div class="box">
    <div class="box-header">
        <h3 class="box-title">Список</h3>
        <div class="box-tools">
            <a href="list" class="btn btn-warning btn-sm">Все пользователи</a>
            <a href="add_access" class="btn btn-success btn-sm">Добавить сотрудника</a>
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
        {foreach from=$users item=record}
            <tr>
                <td>{$record.user_name}</td>
                <td>{$record.user_email}</td>
                <td>{$record.user_phone}</td>
                <td>{if isset($statusList[$record.meta_value])}{$statusList[$record.meta_value]}{else}<span class="label label-default">не известно</span>{/if}</td>
                <td class="col-sm-1">
                <span class="pull-right">
                    <a href="access?id={$record.id}" class="btn btn-warning btn-xs" title="Изменить права"><i class="fa fa-fw fa-pencil"></i></a>
                </span>
                </td>
            </tr>
        {/foreach}
            </tbody>
        </table>
    </div>
</div>