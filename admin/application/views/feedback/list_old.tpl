<div class="box">
    <div class="box-header">
        <h3 class="box-title">Отправленные сообщения</h3>
        <div class="box-tools">
            <a href="list" class="btn btn-success btn-sm">Новые</a>
        </div>
    </div>
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Дата</th>
                <th>Данные отправителя</th>
                <th>Обработано</th>
                <th>Сообщение</th>
                <th class="col-sm-2"></th>
            </tr>
            </thead>
            <tbody>
            {foreach from=$list.data item=i}
                <tr>
                    <td>{$i.feedback_date|date2ru_format}</td>
                    <td>{$i.feedback_name}<br>
                        {if $i.feedback_user}
                            <a href="admin/user/profile?id={$i.feedback_user}" target="_blank">{$i.feedback_email}</a>
                        {else}
                            {$i.feedback_email}
                        {/if}<br>
                        {$i.feedback_topic|crop_str:20}
                    </td>
                    <td>{$i.feedback_reply_date|date2ru_format}</td>
                    <td>{$i.feedback_reply}</td>
                    <td><span class="pull-right">
                            <a href="view?id={$i.id}" class="btn btn-info btn-xs" title="Просмотреть"><i class="fa fa-fw fa-eye"></i></a>
                            <a href="delete?id={$i.id}" class="btn btn-danger btn-xs delete-js" title="Удалить"><i class="fa fa-fw fa-times"></i></a>
                        </span></td>
                </tr>
            {/foreach}
            </tbody>
        </table>
    </div>
    {if count($list.data)}
    <div class="box-footer">
        <div class="row">
            <div class="col-sm-3">Показаны {$list.page.offset+1} - {$list.page.offset + count($list.data)} из {$list.page.itemCount}</div>
            <div class="col-sm-3">
                <form class="search" method="get" action="{currenturl page=0}">
                    <label>Показать <select name="pageSize" class="input-search input-sm">
                            {foreach from=$list.page.pageSizeList item=option}
                                <option {if $option==$list.page.pageSize}selected{/if} value="{$option}">{$option}</option>
                            {/foreach}
                        </select> записей</label>
                </form>
            </div>
            <div class="col-sm-6">
                {include file='adminlte/paginator.tpl' pagination=$list.page}
            </div>
        </div>
    </div>
    {/if}
</div>
{literal}
    <script type="text/javascript">
        !function ($) {
            $(function() {

                $('table').on('click', '.delete-js',  function(e){

                    if (!confirm('Вы действительно желаете удалить сообщение?')) {

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