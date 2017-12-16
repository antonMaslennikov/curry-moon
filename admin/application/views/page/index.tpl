    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Статические страницы</h3>
            <div class="box-tools">
                <a href="page/create" class="btn btn-success btn-sm">Добавить страницу</a>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive no-padding">

            <table class="table table-hover">
                <tbody><tr>
                    <th>Заголовок</th>
                    <th>URL</th>
                    <th>Статус</th>
                    <th></th>
                </tr>
                {foreach from=$static_pages item=static}
                <tr>
                    <td>{$static.h1_ru}</td>
                    <td>{$static.slug}</td>
                    <td>
                        {if $static.status}
                            <span class="label label-success">Опубликован</span>
                        {else}
                            <span class="label label-danger">Черновик</span>
                        {/if}
                    </td>
                    <td>
                        <span class="pull-right">
                            <a href="page/update?id={$static.id}" class="btn btn-warning btn-xs" title="Изменить страницу"><i class="fa fa-fw fa-pencil"></i></a>
                            <a href="page/delete?id={$static.id}" class="btn btn-danger btn-xs delete-js" title="Удалить страницу"><i class="fa fa-fw fa-times"></i></a>
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