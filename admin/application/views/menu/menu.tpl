<div class="col-xs-12">
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Меню</h3>
            <div class="box-tools">
                <a href="menu/create" class="btn btn-success btn-sm">Добавить меню</a>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive no-padding">

            <table class="table table-hover">
                <tbody><tr>
                    <th>Название</th>
                    <th>Псевдоним</th>
                    <th>Описание</th>
                    <th>Статус</th>
                    <th></th>
                </tr>
                {foreach from=$list item=item}
                    <tr>
                        <td>{$item.name}</td>
                        <td>{$item.slug}</td>
                        <td>{$item.description}</td>
                        <td>
                            {if $item.status}
                                <span class="label label-success">Опубликован</span>
                            {else}
                                <span class="label label-danger">Черновик</span>
                            {/if}
                        </td>
                        <td>
                        <span class="pull-right">
                            <a href="menu/update?id={$item.id}" class="btn btn-warning btn-xs" title="Изменить меню"><i class="fa fa-fw fa-pencil"></i></a>
                            <a href="menu/delete?id={$item.id}" class="btn btn-danger btn-xs delete-js" title="Удалить меню"><i class="fa fa-fw fa-times"></i></a>
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
    <pre>{$list|print_r}</pre>
{/if}