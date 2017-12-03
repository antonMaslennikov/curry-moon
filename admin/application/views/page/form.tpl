<div class="row">
    <div class="col-sm-12">

        <!-- form start -->
        <form role="form" method="post" enctype="multipart/form-data">

            <div class="nav-tabs-custom ">

                <ul class="nav nav-tabs">
                    <li class="active"><a href="#main" data-toggle="tab">Основные данные</a></li>
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
                                <div class="form-group {if isset($model.error.h1_ru)}has-error{/if}">
                                    <label for="{$model.id.h1_ru}">
                                        {$model.label.h1_ru} <span class="text-danger">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="{$model.id.h1_ru}"
                                        name="{$model.name.h1_ru}"
                                        value="{$model.value.h1_ru}"
                                        placeholder="Введите название">
                                    {if isset($model.error.h1_ru)}
                                        <p class="help-block">{' '|implode:$model.error.h1_ru}</p>
                                    {/if}
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group {if isset($model.error.h1_en)}has-error{/if}">
                                    <label for="{$model.id.h1_en}">
                                        {$model.label.h1_en}
                                    </label>
                                    <input
                                            type="text"
                                            class="form-control"
                                            id="{$model.id.h1_en}"
                                            name="{$model.name.h1_en}"
                                            value="{$model.value.h1_en}"
                                            placeholder="Введите название">
                                    {if isset($model.error.h1_en)}
                                        <p class="help-block">{' '|implode:$model.error.h1_en}</p>
                                    {/if}
                                </div>
                            </div>
                        </div>
                        <div class="row">
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
                                <div class="form-group {if isset($model.error.text_ru)}has-error{/if}">
                                    <label for="{$model.id.text_ru}">
                                        {$model.label.text_ru}
                                    </label>
                                    <textarea
                                            type="text"
                                            class="form-control tinymce-textarea"
                                            id="{$model.id.text_ru}"
                                            name="{$model.name.text_ru}"
                                            placeholder="Введите текст">{$model.value.text_ru}</textarea>
                                    {if isset($model.error.text_ru)}
                                        <p class="help-block">{' '|implode:$model.error.text_ru}</p>
                                    {/if}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group {if isset($model.error.text_en)}has-error{/if}">
                                    <label for="{$model.id.text_en}">
                                        {$model.label.text_en}
                                    </label>
                                    <textarea
                                            type="text"
                                            class="form-control tinymce-textarea"
                                            id="{$model.id.text_en}"
                                            name="{$model.name.text_en}"
                                            placeholder="Введите текст">{$model.value.text_en}</textarea>
                                    {if isset($model.error.text_en)}
                                        <p class="help-block">{' '|implode:$model.error.text_en}</p>
                                    {/if}
                                </div>
                            </div>
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
                    <a href="/admin/page" class="btn btn-default">Отмена</a>
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
                var sourceField = $('#{$model.id.h1_ru}'),
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