<div class="col-xs-12">
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Список</h3>
            <div class="box-tools">
                <a href="create" class="btn btn-success btn-sm">Добавить пользователя</a>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
                <tbody>
                <tr>
                    <th>Логин</th>
                    <th>Фамилия имя отчество</th>
                    <th>Email</th>
                    <th>Телефон</th>
                    <th>Статус</th>
                    <th>Активация</th>
                    <th></th>
                </tr>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                {foreach from=$users.data item=record}
                    <tr>
                        <td>{$record.user_login}</td>
                        <td>{$record.user_name}</td>
                        <td>{$record.user_email}</td>
                        <td>{$record.user_phone}</td>
                        <td>{$record.user_status}</td>
                        <td>{$record.user_activation}</td>
                        <td>
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
                    <label>Показать <select name="pageSize" class="page-size input-sm">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select> записей</label>
                </div>
            </div>
        </div>
    </div>
    <!-- /.box -->
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
{{$users|printr}}