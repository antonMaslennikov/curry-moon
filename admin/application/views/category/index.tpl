<div class="col-xs-12">
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Дерево категорий</h3>
            <div class="box-tools">
                {if empty($tree)}
                    <a href="createTree" class="btn btn-success btn-sm">Создать дерево</a>
                {/if}
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive no-padding">

            <table class="table table-hover">
                <tbody><tr>
                    <th>ID</th>
                    <th>slug</th>
                    <th>Название</th>
                    <th>Изображение</th>
                    <th>Статус</th>
                    <th></th>
                </tr>
                {foreach from=$tree item=node}
                <tr>
                    <td>{$node.id}</td>
                    <td>{$node.slug}</td>
                    <td>{$node.title}</td>
                    <td>{if $node.picture_id}
                        <img src="{$node.picture_id}" style="width: 24px" />
                        {/if}
                    </td>
                    <td>{$node.status}</td>
                    <td>
                        <span class="pull-right">
                            {if $node.parent_id}
                            <a href="move?id={$node.id}" class="btn btn-default btn-xs" title="Изменить  расположение"><i class="fa fa-fw fa-arrows-alt"></i></a>
                            {/if}
                            <a href="create?id={$node.id}" class="btn btn-default btn-xs" title="Добавить категорию"><i class="fa fa-fw fa-plus"></i></a>
                            <a href="update?id={$node.id}" class="btn btn-warning btn-xs" title="Изменить данные"><i class="fa fa-fw fa-pencil"></i></a>
                            <a href="delete?id={$node.id}" class="btn btn-danger btn-xs delete-js" title="Удалить категорию"><i class="fa fa-fw fa-times"></i></a>
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

            if (!confirm('Вы действительно желаете удалить категорию?')) {

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
    <pre>{$tree|print_r}</pre>
{/if}