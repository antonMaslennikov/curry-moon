    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Список товаров</h3>
            <div class="box-tools">
                {if empty($tree)}
                    <a href="create" class="btn btn-success btn-sm">Добавить товар</a>
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
                    <th>Статус</th>
                    <th></th>
                </tr>
                {foreach from=$products item=node}
                <tr>
                    <td>{$node.id}</td>
                    <td><a href="update?id={$node.id}" title="Изменить данные">{$node.product_name}</a></td>
                    <td>
                        {if $node.picture_id}
                        <a href="update?id={$node.id}" title="Изменить данные"><img src="{$node.picture_id|pictureId2path}" style="height: 50px" /></a>
                        {/if}
                    </td>
                    <td>
                        {if $node.status}
                            <span class="label label-success">Активен</span>
                        {else}
                            <span class="label label-danger">Не активен</span>
                        {/if}
                    </td>
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
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
{literal}
<script type="text/javascript">
!function ($) {
    $(function() {

        $('table').on('click', '.delete-js',  function(e){

            if (!confirm('Вы действительно желаете удалить товар?')) {

                e.preventDefault();
                return false;
            }

            return true;
        })
    })
}(window.jQuery)
</script>
{/literal}