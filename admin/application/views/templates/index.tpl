<div class="col-xs-12">
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Все купоны</h3>
            <div class="box-tools">
                <a href="/admin/templates/create" class="btn btn-success btn-sm">Добавить шаблон</a>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive no-padding">

            <table class="table table-hover">
                <tbody><tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Категория</th>
                    <th>Отправок</th>
                    <th></th>
                </tr>
                {foreach from=$list item=node}
                <tr>
                    <td>{$node.id}</td>
                    <td>{$node.mail_template_name}</td>
                    <td>{$node.category}</td>
                    <td>{$node.mail_template_send}</td>
                    <td>
                        <span class="pull-right">
                            <a href="/admin/templates/update?id={$node.id}" class="btn btn-warning btn-xs" title="Изменить данные"><i class="fa fa-fw fa-pencil"></i></a>
                            <a href="/admin/templates/delete?id={$node.id}" class="btn btn-danger btn-xs delete-js" title="Удалить категорию"><i class="fa fa-fw fa-times"></i></a>
                        </span>
                    </td>
                </tr>
                {/foreach}
                </tbody>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
</div>
{literal}
<script type="text/javascript">
!function ($) {
    $(function() {

        $('table').on('click', '.delete-js',  function(e){

            if (!confirm('Вы действительно желаете удалить шаблон?')) {

                e.preventDefault();
                return false;
            }

            return true;
        })
    })
}(window.jQuery)
</script>
{/literal}