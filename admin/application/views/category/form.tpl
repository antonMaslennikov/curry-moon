<div class="row">
    <div class="col-sm-12">

        <!-- form start -->
        <form role="form" method="post" enctype="multipart/form-data">

            <div class="nav-tabs-custom ">

                <ul class="nav nav-tabs">
                    <li class="active"><a href="#main" data-toggle="tab">Основные данные</a></li>
                    <li><a href="#image" data-toggle="tab">Изображение</a></li>
                    <li><a href="#meta" data-toggle="tab">META данные</a></li>
                    <li><a href="#fields" data-toggle="tab">Дополнительные характеристики</a></li>
                </ul>

                <div class="tab-content">

                    <div class="tab-pane active" id="main">

                    {if $model.errorSummary}
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="callout callout-danger">
                                    <h4>Ошибки!</h4>
                                    {$model.errorSummary}
                                </div>
                            </div>
                        </div>
                    {/if}

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="callout callout-warning">
                                    <p>Поля, отмеченные <span class="text-danger">*</span>, обязательны для заполнения!</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                {include file="adminlte/form/input.tpl" attr="title" required="1"}
                            </div>
                            <div class="col-sm-6">
                                {include file="adminlte/form/input.tpl" attr="slug"}
                            </div>
                        </div>

                        <div class="row">
                        {if !$model.value.newTree}
                            <div class="col-sm-6">
                                {include file="adminlte/form/select.tpl" attr="parent_id" list=$model.listNode required="1"}
                            </div>
                        {/if}
                            <div class="col-sm-6">
                                {include file="adminlte/form/select.tpl" attr="status" list=$model.listStatus}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                {include file="adminlte/form/textarea.tpl" attr="description" class_selector="tinymce-textarea"}
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="image">

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group {if isset($model.error.picture)}has-error{/if}">
                                    <label for="{$model.id.picture}">
                                        {$model.label.picture}
                                    </label>
                                    <input
                                            type="file"
                                            id="{$model.id.picture}"
                                            name="{$model.name.picture}"
                                            value="">
                                    {if isset($model.error.picture)}
                                        <p class="help-block">{' '|implode:$model.error.picture}</p>
                                    {/if}
                                </div>
                            </div>

                            {if (!$model.value.newRecord)}

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Загруженное изображение</label>
                                        <p class="form-control-static">
                                            {if $model.value.picture_id>0}
                                                <img src="{$model.value.picture_id|pictureId2path}" style="width: 50px">
                                            {else}
                                                Нет изображения
                                            {/if}
                                    </div>
                                </div>
                            {/if}
                        </div>

                    </div>

                    <div class="tab-pane" id="meta">

                        <div class="row">
                            <div class="col-sm-6">
                                {include file="adminlte/form/input.tpl" attr="meta_keywords"}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                {include file="adminlte/form/textarea.tpl" attr="meta_description"}
                            </div>
                        </div>

                    </div>

                    <div class="tab-pane" id="fields">
                       
                       <p>
                           <a href="/admin/product_category/field_save?category={$category->id}" data-toggle="modal" data-target="#category-field-modal" class="edit-field" style="border-bottom: 1px dashed">Добавить <i class="fa fa-long-arrow-right"></i></a>
                       </p>
                       
                       <br />
                       
                       <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                           <tr>
                               <th>Название</th>
                               <th>Slug</th>
                               <th>Возможные значения</th>
                               <th></th>
                           </tr>
                           {foreach from=$category->getAdditionFields() item="af"}
                           <tr>
                                <td>{$af.name}</td>
                                <td style="color:#ccc">{$af.slug}</td>
                                <td>{$af.value}</td>
                                <td>
                                    <span class="pull-right">
                                        <a href="/admin/product_category/field_save?id={$af.id}&category={$category->id}" data-toggle="modal" data-target="#category-field-modal-{$af.id}" class="btn btn-warning btn-xs " title="Изменить данные"><i class="fa fa-fw fa-pencil"></i></a>
                                        <a href="field_delete?id={$af.id}" class="btn btn-danger btn-xs delete-js" title="Удалить поле"><i class="fa fa-fw fa-times"></i></a>
                                    </span>
                                </td>
                           </tr>
                           {foreachelse}
                           <tr>
                               <td colspan="10">Отсутствуют</td>
                           </tr>
                           {/foreach}
                       </table>
                       
                    </div>
                </div>

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                    <button type="submit" class="btn btn-info" name="apply">Применить</button>
                    <a href="list" class="btn btn-default">Отмена</a>
                </div>

        </form>
    </div>
</div>


<div class="modal fade" id="category-field-modal">
    <div class="modal-dialog">
        <div class="modal-content"></div>
    </div>
</div>

{foreach from=$category->getAdditionFields() item="af"}
<div class="modal fade" id="category-field-modal-{$af.id}">
    <div class="modal-dialog">
        <div class="modal-content"></div>
    </div>
</div>
{/foreach}

{literal}
    <script src="/public/packages/tinymce/tinymce.min.js"></script>
    <script type="text/javascript">
        !function ($) {
            $(function() {

                {/literal}
                var sourceField = $('#{$model.id.title}'),
                    targetField = $('#{$model.id.slug}');
                {literal}

                tinymce.init({
                    selector: '.tinymce-textarea',
                    menubar: false
                });

                var timer,
                    updateUrl = "/admin/api/transliterate",
                    editable = targetField.val().length == 0,
                    value = sourceField.val();

                if (targetField.val().length !== 0) {
                    $.get(updateUrl, {data: sourceField.val()}, function (r) {
                        editable = targetField.val() == r;
                    });
                }

                sourceField.on('keyup blur copy paste cut start', function () {
                    clearTimeout(timer);

                    if (editable && value != sourceField.val()) {
                        timer = setTimeout(function () {
                            value = sourceField.val();
                            targetField.attr('disabled', 'disabled');
                            $.get(updateUrl, {data: sourceField.val()}, function (r) {
                                targetField.val(r).removeAttr('disabled');
                            });
                        }, 300);
                    }
                });

                targetField.on('change', function () {
                    editable = $(this).val().length == 0;
                });
                 
             
                 /*
                $('.edit-field').click(function() {

                    $('#category-field-form input[name=data\\\[name\\\]]').val($(this).attr('data-name'));
                    $('#category-field-form input[name=data\\\[slug\\\]]').val($(this).attr('data-slug'));
                    $('#category-field-form input[name=data\\\[value\\\]]').val($(this).attr('data-value'));
                    $('#category-field-form input[name=id]').val($(this).attr('data-id'));
                    
                    $('#category-field-modal').modal({
                        keyboard: false
                    });

                    return false;
                });*/
                /*
                $('#category-field-form').submit(function(){

                    f = $(this);

                    $.post($(this).attr('action'), $(this).serialize(), function() {
                        id = f.find('input[name=id]').val();
                        $('a[data-id=' + id + ']').attr('data-name', f.find('input[name=name]').val());
                        $('a[data-id=' + id + ']').attr('data-slug', f.find('input[name=slug]').val());
                        $('a[data-id=' + id + ']').attr('data-value', f.find('input[name=value]').val());

                        $('#category-field-modal').modal('hide');
                    });

                    location.reload();
                    
                    return false;

                });*/
            })
        }(window.jQuery)
    </script>
{/literal}