<div class="row">
    <div class="col-sm-12">

        <!-- form start -->
        <form role="form" method="post" enctype="multipart/form-data">

            <div class="nav-tabs-custom ">

                <ul class="nav nav-tabs">
                    <li class="active"><a href="#main" data-toggle="tab">Основные данные</a></li>
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
                                <div class="form-group {if isset($model.error.name)}has-error{/if}">
                                    <label for="{$model.id.name}">
                                        {$model.label.name} <span class="text-danger">*</span>
                                    </label>
                                    <input
                                            type="text"
                                            class="form-control"
                                            id="{$model.id.name}"
                                            name="{$model.name.name}"
                                            value="{$model.value.name}"
                                            placeholder="Введите название">
                                    {if isset($model.error.name)}
                                        <p class="help-block">{' '|implode:$model.error.name}</p>
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
                            <div class="col-sm-6">
                                <div class="form-group {if isset($model.error.description)}has-error{/if}">
                                    <label for="{$model.id.description}">
                                        {$model.label.description}
                                    </label>
                                    <textarea
                                            type="text"
                                            class="form-control tinymce-textarea"
                                            id="{$model.id.description}"
                                            name="{$model.name.description}"
                                            rows="5"
                                            placeholder="Введите описание">{$model.value.description}</textarea>
                                    {if isset($model.error.description)}
                                        <p class="help-block">{' '|implode:$model.error.description}</p>
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
                    </div>
                </div>

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                    <button type="submit" class="btn btn-info" name="apply">Применить</button>
                    <a href="/admin/menu" class="btn btn-default">Отмена</a>
                </div>

        </form>
    </div>
</div>

{if $smarty.const.appMode eq 'dev'}
    <pre>{$model|print_r}</pre>
{/if}

{literal}
<script type="text/javascript">
    !function ($) {
        $(function() {

            {/literal}
            var sourceField = $('#{$model.id.name}'),
                    targetField = $('#{$model.id.slug}');
            {literal}

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