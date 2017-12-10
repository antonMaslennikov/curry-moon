<div class="col-xs-12">
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Все купоны</h3>
            <div class="box-tools">
                <a href="create" class="btn btn-success btn-sm">Добавить купон</a>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive no-padding">

            <table class="table table-hover">
                <tbody><tr>
                    <th>ID</th>
                    <th>Код купона</th>
                    <th>Процент / сумма</th>
                    <th>Значение</th>
                    <th>Лимит на использование</th>
                    <th>Статус</th>
                    <th></th>
                </tr>
                {foreach from=$coupons item=node}
                <tr>
                    <td>{$node.id}</td>
                    <td>{$node.certification_password}</td>
                    <td>{if $node.certification_type == "percent"}Процент{else}Сумма{/if}</td>
                    <td>{$node.certification_value}</td>
                    <td>{$node.certification_limit}</td>
                    <td>
                        {if $node.certification_enabled}
                            <span class="label label-success">активен</span>
                        {else}
                            <span class="label label-danger">выключен</span>
                        {/if}
                    </td>
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
</div>
{literal}
<script type="text/javascript">
!function ($) {
    $(function() {

        $('table').on('click', '.delete-js',  function(e){

            if (!confirm('Вы действительно желаете удалить купон?')) {

                e.preventDefault();
                return false;
            }

            return true;
        })
    })
}(window.jQuery)
</script>
{/literal}