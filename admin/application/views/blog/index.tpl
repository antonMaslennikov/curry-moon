<div class="box">
    <div class="box-header">
        <h3 class="box-title">Блог</h3>
        <div class="box-tools">
            <a href="/admin/blog/create" class="btn btn-success btn-sm">Добавить запись</a>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body table-responsive no-padding">

        <table class="table table-hover">
            <tbody><tr>
                <th>Категория</th>
                <th>Дата публикации</th>
                <th>Заголовок</th>
                <th>URL</th>
                <th>Статус</th>
                <th></th>
            </tr>
            {foreach from=$post item=record}
                <tr>
                    <td>{if isset($listCategory[$record.category])}{$listCategory[$record.category]}{/if}</td>
                    <td>{$record.publish_date}</td>
                    <td>{$record.title}</td>
                    <td>{$record.slug}</td>
                    <td>
                        {if $record.status}
                            <span class="label label-success">Опубликован</span>
                        {else}
                            <span class="label label-danger">Черновик</span>
                        {/if}
                    </td>
                    <td class="col-sm-1">
                    <span class="pull-right">
                        <a href="/admin/blog/update?id={$record.id}" class="btn btn-warning btn-xs" title="Изменить запись"><i class="fa fa-fw fa-pencil"></i></a>
                        <a href="/admin/blog/delete?id={$record.id}" class="btn btn-danger btn-xs delete-js" title="Удалить запись"><i class="fa fa-fw fa-times"></i></a>
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