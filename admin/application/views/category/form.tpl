<div class="row">
    <div class="col-sm-12">

        <!-- form start -->
        <form role="form" method="post" enctype="multipart/form-data">

            <div class="nav-tabs-custom ">

                <ul class="nav nav-tabs">
                    <li class="active"><a href="#main" data-toggle="tab">Основные данные</a></li>
                    <li><a href="#image" data-toggle="tab">Изображение</a></li>
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
                                        placeholder="Введите название">
                                    {if isset($model.error.title)}
                                        <p class="help-block">{' '|implode:$model.error.title}</p>
                                    {/if}
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group {if isset($model.error.slug)}has-error{/if}">
                                    <label for="{$model.id.slug}">
                                        {$model.label.slug}
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="{$model.id.slug}"
                                        name="{$model.name.slug}"
                                        value="{$model.value.slug}"
                                        placeholder="Введите псевдоним">
                                    {if isset($model.error.slug)}
                                        <p class="help-block">{' '|implode:$model.error.slug}</p>
                                    {/if}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                        {if !$model.value.newTree}
                            <div class="col-sm-6">
                                <div class="form-group {if isset($model.error.parent_id)}has-error{/if}">
                                    <label for="{$model.id.parent_id}">
                                        {$model.label.parent_id} <span class="text-danger">*</span>
                                    </label>
                                    <select
                                            class="form-control"
                                            id="{$model.id.parent_id}"
                                            name="{$model.name.parent_id}">
                                        {foreach from=$model.listNode item="m" key="k"}
                                            <option value="{$k}"
                                                    {if $k == $model.value.parent_id}selected="selected"{/if}>{$m}</option>
                                        {/foreach}
                                    </select>
                                    {if isset($model.error.parent_id)}
                                        <p class="help-block">{' '|implode:$model.error.parent_id}</p>
                                    {/if}
                                </div>
                            </div>
                        {/if}
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
                            <div class="col-sm-12">
                                <div class="form-group {if isset($model.error.description)}has-error{/if}">
                                    <label for="{$model.id.description}">
                                        {$model.label.description}
                                    </label>
                                    <textarea
                                            type="text"
                                            class="form-control tinymce-textarea"
                                            id="{$model.id.description}"
                                            name="{$model.name.description}"
                                            placeholder="Введите описание">{$model.value.description}</textarea>
                                {if isset($model.error.description)}
                                    <p class="help-block">{' '|implode:$model.error.description}</p>
                                {/if}
                                </div>
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
                                <div class="form-group {if isset($model.error.meta_keywords)}has-error{/if}">
                                    <label for="{$model.id.meta_keywords}">
                                        {$model.label.meta_keywords}
                                    </label>
                                    <input
                                            type="text"
                                            class="form-control"
                                            id="{$model.id.meta_keywords}"
                                            name="{$model.name.meta_keywords}"
                                            value="{$model.value.meta_keywords}">
                                    {if isset($model.error.meta_keywords)}
                                        <p class="help-block">{' '|implode:$model.error.meta_keywords}</p>
                                    {/if}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group {if isset($model.error.meta_description)}has-error{/if}">
                                    <label for="{$model.id.meta_description}">
                                        {$model.label.meta_description}
                                    </label>
                                    <textarea
                                            class="form-control"
                                            id="{$model.id.meta_description}"
                                            name="{$model.name.meta_description}">{$model.value.meta_description}</textarea>
                                    {if isset($model.error.meta_description)}
                                        <p class="help-block">{' '|implode:$model.error.meta_description}</p>
                                    {/if}
                                </div>
                            </div>
                        </div>

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
            })
        }(window.jQuery)
    </script>
{/literal}