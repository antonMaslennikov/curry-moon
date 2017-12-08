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
                                <div class="form-group {if isset($model.error.title)}has-error{/if}">
                                    <label for="{$model.id.title}">
                                        {$model.label.title} <span class="text-danger">*</span>
                                    </label>
                                    <input
                                            type="text"
                                            class="form-control"
                                            id="{$model.id.title}"
                                            name="{$model.name.title}"
                                            value="{$model.value.title}"
                                            placeholder="Введите {$model.label.title|mb_strtolower}">
                                    {if isset($model.error.title)}
                                        <p class="help-block">{' '|implode:$model.error.title}</p>
                                    {/if}
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group {if isset($model.error.slug)}has-error{/if}">
                                    <label for="{$model.id.slug}">
                                        {$model.label.slug} <span class="text-danger">*</span>
                                    </label>
                                    <input
                                            type="text"
                                            class="form-control"
                                            id="{$model.id.slug}"
                                            name="{$model.name.slug}"
                                            value="{$model.value.slug}"
                                            placeholder="Введите {$model.label.slug|mb_strtolower}">
                                    {if isset($model.error.slug)}
                                        <p class="help-block">{' '|implode:$model.error.slug}</p>
                                    {/if}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group {if isset($model.error.publish_date)}has-error{/if}">
                                    <label for="{$model.id.publish_date}">
                                        {$model.label.publish_date} <span class="text-danger">*</span>
                                    </label>
                                    <input
                                            type="text"
                                            class="form-control datepicker"
                                            id="{$model.id.publish_date}"
                                            name="{$model.name.publish_date}"
                                            value="{$model.value.publish_date}">
                                    {if isset($model.error.publish_date)}
                                        <p class="help-block">{' '|implode:$model.error.publish_date}</p>
                                    {/if}
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group {if isset($model.error.status)}has-error{/if}">
                                    <label for="{$model.id.status}">
                                        {$model.label.status}
                                    </label>
                                    <select
                                            class="form-control"
                                            id="{$model.id.status}"
                                            name="{$model.name.status}">
                                        {foreach from=$model.listStatus item="m" key="k"}
                                            <option value="{$k}"
                                                    {if $k == $model.value.status}selected="selected"{/if}>{$m}</option>
                                        {/foreach}
                                    </select>
                                    {if isset($model.error.status)}
                                        <p class="help-block">{' '|implode:$model.error.status}</p>
                                    {/if}
                                </div>
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
                                <div class="form-group {if isset($model.error.is_special)}has-error{/if}">
                                    <label for="{$model.id.is_special}">
                                        {$model.label.is_special}&nbsp;
                                    </label>
                                    <select
                                            class="form-control"
                                            id="{$model.id.is_special}"
                                            name="{$model.name.is_special}">
                                        {foreach from=$model.listSpecial item="m" key="k"}
                                            <option value="{$k}"
                                                    {if $k == $model.value.is_special}selected="selected"{/if}>{$m}</option>
                                        {/foreach}
                                    </select>
                                    {if isset($model.error.is_special)}
                                        <p class="help-block">{' '|implode:$model.error.is_special}</p>
                                    {/if}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group {if isset($model.error.content)}has-error{/if}">
                                    <label for="{$model.id.content}">
                                        {$model.label.content}
                                    </label>
                                    <textarea
                                            type="text"
                                            class="form-control tinymce-textarea"
                                            id="{$model.id.content}"
                                            name="{$model.name.content}"
                                            placeholder="Введите текст">{$model.value.content}</textarea>
                                    {if isset($model.error.content)}
                                        <p class="help-block">{' '|implode:$model.error.content}</p>
                                    {/if}
                                </div>
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
                                <div class="form-group {if isset($model.error.keywords)}has-error{/if}">
                                    <label for="{$model.id.keywords}">
                                        {$model.label.keywords}
                                    </label>
                                    <input
                                            type="text"
                                            class="form-control"
                                            id="{$model.id.keywords}"
                                            name="{$model.name.keywords}"
                                            value="{$model.value.keywords}">
                                    {if isset($model.error.keywords)}
                                        <p class="help-block">{' '|implode:$model.error.keywords}</p>
                                    {/if}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group {if isset($model.error.description)}has-error{/if}">
                                    <label for="{$model.id.description}">
                                        {$model.label.description}
                                    </label>
                                    <textarea
                                            class="form-control"
                                            id="{$model.id.description}"
                                            name="{$model.name.description}">{$model.value.description}</textarea>
                                    {if isset($model.error.description)}
                                        <p class="help-block">{' '|implode:$model.error.description}</p>
                                    {/if}
                                </div>
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

{if $smarty.const.appMode eq 'dev'}
    <pre>{$model|print_r}</pre>
{/if}

{literal}
    <script src="https://cloud.tinymce.com/stable/tinymce.min.js"></script>
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