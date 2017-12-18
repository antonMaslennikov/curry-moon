<div class="row">
    <div class="col-sm-12">

        <!-- form start -->
        <form role="form" method="post" enctype="multipart/form-data">

            <div class="nav-tabs-custom ">

                <ul class="nav nav-tabs">
                    <li class="active"><a href="#main" data-toggle="tab">Основные данные</a></li>
                    <li><a href="#images" data-toggle="tab">Изображения</a></li>
                    <li><a href="#meta" data-toggle="tab">META данные</a></li>
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
                                {include file="adminlte/form/input.tpl" attr="slug" required="1"}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                {include file="adminlte/form/input.tpl" attr="publish_date" required="1" class_selector="datepicker"}
                            </div>
                            <div class="col-sm-6">
                                {include file="adminlte/form/select.tpl" attr="status" list=$model.listStatus}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group {if isset($model.error.tags)}has-error{/if}">
                                    <label for="{$model.id.tags}">
                                        {$model.label.tags}
                                    </label>
                                    <select
                                        multiple="multiple"
                                        class="form-control select2-tags"
                                        id="{$model.id.tags}"
                                        name="{$model.name.tags}[]">
                                    {foreach from=$model.listAllTags item="m" key="k"}
                                        <option value="{$k}"
                                            {if in_array($k, $model.value.tags)}selected="selected"{/if}>{$m}</option>
                                    {/foreach}
                                    </select>
                                    {if isset($model.error.tags)}
                                        <p class="help-block">{' '|implode:$model.error.tags}</p>
                                    {/if}
                                </div>
                            </div>
                            <div class="col-sm-6">
                                {include file="adminlte/form/select.tpl" attr="category" list=$model.listCategory}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                {include file="adminlte/form/textarea.tpl" attr="content" class_selector="tinymce-textarea"}
                            </div>
                        </div>

                    </div>

                    <div class="tab-pane" id="images">

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group {if isset($model.error.image_file)}has-error{/if}">
                                    <label for="{$model.id.image_file}">
                                        {$model.label.image_file}
                                    </label>
                                    <input
                                            type="file"
                                            onchange="readURL(this);"
                                            id="{$model.id.image_file}"
                                            name="{$model.name.image_file}"
                                            value="">
                                    {if isset($model.error.image_file)}
                                        <p class="help-block">{' '|implode:$model.error.image_file}</p>
                                    {/if}
                                </div>
                                <img class="preview-image img-responsive" style="display:none" />
                            </div>

                            {if (!$model.value.newRecord)}

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Загруженное изображение</label>
                                        <p class="form-control-static">
                                            {if $model.value.image>0}
                                                <img src="{$model.value.image|pictureId2path}" style="width: 50px">
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
                                {include file="adminlte/form/input.tpl" attr="keywords"}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                {include file="adminlte/form/textarea.tpl" attr="description"}
                            </div>
                        </div>

                    </div>

                </div>

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                    <button type="submit" class="btn btn-info" name="apply">Применить</button>
                    <a href="/admin/blog/list" class="btn btn-default">Отмена</a>
                </div>

        </form>
    </div>
</div>
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
                menubar: false,
                plugins: "code",
                toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | code"
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


            $('.datepicker').datepicker({
                autoclose: true,
                language: 'ru',
                format:"dd.mm.yyyy"
            });

            $(".select2-tags").select2({
                tags: true
            });
        })
    }(window.jQuery)
</script>
{/literal}