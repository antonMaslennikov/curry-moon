<div class="col-xs-12">
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
                    <th></th>
                    <th>Дата публикации</th>
                    <th>Заголовок</th>
                    <th>URL</th>
                    <th>Статус</th>
                    <th></th>
                </tr>
                {foreach from=$post item=record}
                    <tr>
                        <td>{if $record.is_special}<span class="label label-primary">Акция</span>{/if}</td>
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
                        <td>
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
{if $smarty.const.appMode eq 'dev'}
    <pre>{$post|print_r}</pre>
{/if}