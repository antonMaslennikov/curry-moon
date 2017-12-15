    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Дерево категорий</h3>
            <div class="box-tools">
                {if empty($tree)}
                    <a href="createTree" class="btn btn-success btn-sm">Создать дерево</a>
                {else}
                    <a href="create" class="btn btn-success btn-sm">Добавить категорию</a>
                {/if}
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive no-padding">

            <table class="table table-hover">
                <tbody><tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Изображение</th>
                    <th>slug</th>
                    <th>Статус</th>
                    <th>Описание</th>
                    <th></th>
                </tr>
                {foreach from=$tree item=node}
                <tr>
                    <td>{$node.id}</td>
                    <td>{str_repeat('&mdash;',$node.level)} {if $node.id == 1}<a href="/admin/product/list">{else}<a href="/admin/product/list?filter[categoryfull]={$node.id}">{/if}{$node.title}</a></td>
                    <td>
                        {if $node.picture_id}
                        <img src="{$node.picture_id|pictureId2path}" style="width: 24px" />
                        {/if}
                    </td>
                    <td>{$node.slug}</td>
                    <td>
                        {if $node.status}
                            <span class="label label-success">Опубликован</span>
                        {else}
                            <span class="label label-danger">Черновик</span>
                        {/if}
                    </td>
                    <td>{$node.description|strip_tags|truncate:30:"...":true}</td>
                    <td>
                        <span class="pull-right">
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