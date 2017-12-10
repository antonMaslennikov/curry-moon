<div class="row">
    <div class="col-sm-12">

        <!-- form start -->
        <form role="form" method="post" enctype="multipart/form-data">

            <div class="nav-tabs-custom ">

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
                                <div class="form-group {if isset($model.error.variable_name)}has-error{/if}">
                                    <label for="{$model.id.variable_name}">
                                        {$model.label.variable_name} <span class="text-danger">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="{$model.id.variable_name}"
                                        name="{$model.name.variable_name}"
                                        value="{$model.value.variable_name}"
                                        placeholder="Введите название, только латинские символы">
                                    {if isset($model.error.variable_name)}
                                        <p class="help-block">{' '|implode:$model.error.variable_name}</p>
                                    {/if}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group {if isset($model.error.variable_value)}has-error{/if}">
                                    <label for="{$model.id.variable_value}">
                                        {$model.label.variable_value}
                                    </label>
                                    <input
                                            type="text"
                                            class="form-control"
                                            id="{$model.id.variable_value}"
                                            name="{$model.name.variable_value}"
                                            value="{$model.value.variable_value}"
                                            placeholder="Введите значение">
                                    {if isset($model.error.variable_value)}
                                        <p class="help-block">{' '|implode:$model.error.variable_value}</p>
                                    {/if}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group {if isset($model.error.variable_description)}has-error{/if}">
                                    <label for="{$model.id.variable_description}">
                                        {$model.label.variable_description}
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="{$model.id.variable_description}"
                                        name="{$model.name.variable_description}"
                                        value="{$model.value.variable_description}"
                                        placeholder="Описание">
                                    {if isset($model.error.variable_description)}
                                        <p class="help-block">{' '|implode:$model.error.variable_description}</p>
                                    {/if}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                    <button type="submit" class="btn btn-info" name="apply">Применить</button>
                    <a href="/admin/settings" class="btn btn-default">Отмена</a>
                </div>
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