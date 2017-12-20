   <div class="box">
        <div class="box-header">
            <h3 class="box-title">Все шаблоны</h3>
            <div class="box-tools">
                <a href="/admin/templates/create" class="btn btn-success btn-sm">Добавить шаблон</a>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive no-padding">
            
            <div class="nav-tabs-custom">

                <ul class="nav nav-tabs">
                    {foreach from=$list key="k" item="cat" name="catforeach"}
                    <li {if $smarty.foreach.catforeach.first}class="active"{/if}><a href="#tab-{$k}" data-toggle="tab">{$cat.title}</a></li>
                    {/foreach}
                </ul>
                
                

                <div class="tab-content">
                    {foreach from=$list key="k" item="cat" name="catforeach2"}
                    <div class="tab-pane {if $smarty.foreach.catforeach2.first}active{/if}" id="tab-{$k}">
                        
                        <table class="table table-hover">
                        <tbody><tr>
                            <th>ID</th>
                            <th>Название</th>
                            <th>Категория</th>
                            <th>Отправок</th>
                            <th></th>
                        </tr>
                        {foreach from=$cat.tpls item=node}
                        <tr>
                            <td>{$node.id}</td>
                            <td>{$node.mail_template_name}</td>
                            <td>{$node.category}</td>
                            <td>{$node.mail_template_send}</td>
                            <td>
                                <span class="pull-right">
                                    <a href="/admin/templates/view?id={$node.id}" class="btn btn-info btn-xs" title="Просмотреть шаблон"><i class="fa fa-fw fa-eye"></i></a>
                                    <a href="/admin/templates/update?id={$node.id}" class="btn btn-warning btn-xs" title="Изменить данные"><i class="fa fa-fw fa-pencil"></i></a>
                                    <a href="/admin/templates/delete?id={$node.id}" class="btn btn-danger btn-xs delete-js" title="Удалить категорию"><i class="fa fa-fw fa-times"></i></a>
                                </span>
                            </td>
                        </tr>
                        {/foreach}
                        </tbody>
                    </table>
                        
                    </div>
                    {/foreach}
                </div>
            </div>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
{literal}
<script type="text/javascript">
!function ($) {
    $(function() {

        $('table').on('click', '.delete-js',  function(e){

            if (!confirm('Вы действительно желаете удалить шаблон?')) {

                e.preventDefault();
                return false;
            }

            return true;
        })
    })
}(window.jQuery)
</script>
{/literal}