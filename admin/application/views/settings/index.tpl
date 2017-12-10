<div class="col-xs-12">
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Список настроек</h3>
            <div class="box-tools">
                <a href="/admin/settings/create" class="btn btn-success btn-sm">Добавить настройку</a>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive no-padding">

            <form action="" method="post">
           
            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </div>
           
            <table class="table table-hover">
                <tbody><tr>
                    <th>ID</th>
                    <th>Имя</th>
                    <th>Значение</th>
                    <th>Описание</th>
                    <th></th>
                </tr>
                {foreach from=$settings item=node}
                <tr>
                    <td>{$node.id}</td>
                    <td>{$node.variable_name}</td>
                    <td>
                        <input type="text" name="values[{$node.id}]" value="{$node.variable_value}" class="form-control">
                    </td>
                    <td>{$node.variable_description|truncate:130:"...":true}</td>
                    <td>
                        <span class="pull-right">
                            <a href="update?id={$node.id}" class="btn btn-warning btn-xs" title="Изменить данные"><i class="fa fa-fw fa-pencil"></i></a>
                            <a href="delete?id={$node.id}" class="btn btn-danger btn-xs delete-js" title="Удалить товар"><i class="fa fa-fw fa-times"></i></a>
                        </span>
                    </td>
                </tr>
                {/foreach}
                </tbody>
            </table>
            
            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </div>
            
            </form>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
</div>
{literal}
<style>
    .table-responsive table tr td {
        vertical-align: middle;
    }
</style>
<script type="text/javascript">
!function ($) {
    $(function() {

        $('table').on('click', '.delete-js',  function(e){

            if (!confirm('Вы действительно желаете удалить настройку?')) {

                e.preventDefault();
                return false;
            }

            return true;
        })
    })
}(window.jQuery)
</script>
{/literal}