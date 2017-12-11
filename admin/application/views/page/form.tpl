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
                                {include file="adminlte/form/input.tpl" attr="h1_ru" required="1"}
                            </div>
                            <div class="col-sm-6">
                                {include file="adminlte/form/input.tpl" attr="h1_en"}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                {include file="adminlte/form/input.tpl" attr="slug"}
                            </div>
                            <div class="col-sm-6">
                                {include file="adminlte/form/select.tpl" attr="status" list=$model.listStatus}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                {include file="adminlte/form/textarea.tpl" attr="text_ru" class_selector="tinymce-textarea"}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                {include file="adminlte/form/textarea.tpl" attr="text_en" class_selector="tinymce-textarea"}
                            </div>
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

                </div>

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                    <button type="submit" class="btn btn-info" name="apply">Применить</button>
                    <a href="/admin/page" class="btn btn-default">Отмена</a>
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