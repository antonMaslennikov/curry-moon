    <div class="box">
        <form class="search" method="get" action="{currenturl page=0}">
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
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Изображение</th>
                    <th>Статус</th>
                 {if isset($smarty.get.filter.categoryfull)}
                     <th>Сортировка</th>
                 {/if}
                    <th></th>
                </tr>
                <tr>
                    <th></th>
                    <th><input type="text" name="search[product_name]" class="form-control input-sm" value="{$products.search.product_name}"></th>
                    <th colspan="3">
                        <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-search"></i>&nbsp;Поиск</button>&nbsp;
                        <a href="list" class="btn btn-default btn-sm">Очистить</a>
                    </th>
                </tr>
                </thead>
                <tbody>
                {foreach from=$products.data item=node}
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
                    {if isset($smarty.get.filter.categoryfull)}
                        <td>
                            <input type="hidden" name="filter[categoryfull]" value="{$smarty.get.filter.categoryfull}">
                            <button name="moveTo[{$node.id}]" value="up" class="btn-link btn-xs"><i class="fa fa-arrow-up"></i></button>
                            <button name="moveTo[{$node.id}]" value="down" class="btn-link btn-xs"><i class="fa fa-arrow-down"></i></button>
                            <input name="move[{$node.id}]" type="text" value="{$node.sorting%1000}" style="width: 50px; text-align:center">
                            <button name="moveTo[{$node.id}]" value="value" class="btn-link"><i class="fa fa-check"></i></button>
                        </td>
                    {/if}
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
        <div class="box-footer">
            <div class="row">
                <div class="col-sm-3">Показаны {$products.page.offset+1} - {$products.page.offset + count($products.data)} из {$products.page.itemCount}</div>
                <div class="col-sm-3">
                    <label>Показать <select name="pageSize" class="input-search input-sm">
                            {foreach from=$products.page.pageSizeList item=option}
                                <option {if $option==$products.page.pageSize}selected{/if} value="{$option}">{$option}</option>
                            {/foreach}
                        </select> записей</label>
                </div>
                <div class="col-sm-6">
                    {include file='adminlte/paginator.tpl' pagination=$products.page}
                </div>
            </div>
        </div>
        </form>
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

        $('div.box').on('change', '.input-search', function() {

            $('form.search').submit();
        })
    })
}(window.jQuery)
</script>
{/literal}
{$smarty.get|printr}