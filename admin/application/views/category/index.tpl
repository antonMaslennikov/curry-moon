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
                    <td>{$node.picture}</td>
                    <td>{$node.status}</td>
                    <td></td>
                </tr>
                {/foreach}
                </tbody>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
</div>
{if $smarty.const.appMode eq 'dev'}
    <pre>{$tree|print_r}</pre>
{/if}