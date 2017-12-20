<div class="box">
    <form class="search" method="get" action="{currenturl page=0}">
        <div class="box-header">
            <h3 class="box-title">Список</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
                <tbody>
                <tr>
                    <th>#</th>
                    <th>Email</th>
                    <th>IP</th>
                    <th>Дата подписки</th>
                    <th></th>
                </tr>
                {foreach from=$records.data item=record}
                    <tr>
                        <td>{$record.k}</td>
                        <td>{if $record.user_id > 0}<a href="/admin/users/view?id={$record.user_id}">{$record.user_email}</a>{else}{$record.user_email}{/if}</td>
                        <td>{$record.user_ip|long2ip}</td>
                        <td>{$record.time|datefromdb2textdate}</td>
                        <td class="col-sm-1">
                            <span class="pull-right">
                                <a href="/admin/subscribers/delete?id={$record.id}" class="btn btn-danger btn-xs delete-js" title="Удалить запись"><i class="fa fa-fw fa-times"></i></a>
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
                <div class="col-sm-3">Показаны {$records.page.offset+1} - {$records.page.offset + count($records.data)} из {$records.page.itemCount}</div>
                <div class="col-sm-3">
                    <label>Показать <select name="pageSize" class="input-search input-sm">
                    {foreach from=$records.page.pageSizeList item=option}
                            <option {if $option==$records.page.pageSize}selected{/if} value="{$option}">{$option}</option>
                    {/foreach}
                        </select> записей</label>
                </div>
                <div class="col-sm-6">
                    {include file='../adminlte/paginator.tpl' pagination=$records.page}
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

                    if (!confirm('Вы действительно желаете отменить подписку?')) {

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