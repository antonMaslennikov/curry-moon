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
                                <div class="form-group {if isset($model.error.certification_password)}has-error{/if}">
                                    <label for="{$model.id.certification_password}">
                                        {$model.label.certification_password} <span class="text-danger">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="{$model.id.certification_password}"
                                        name="{$model.name.certification_password}"
                                        value="{$model.value.certification_password}"
                                        placeholder="Введите код купона">
                                    {if isset($model.error.certification_password)}
                                        <p class="help-block">{' '|implode:$model.error.certification_password}</p>
                                    {/if}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group {if isset($model.error.certification_value)}has-error{/if}">
                                    <label for="{$model.id.certification_value}">
                                        {$model.label.certification_value}
                                    </label>
                                    <input
                                            type="text"
                                            class="form-control"
                                            id="{$model.id.certification_value}"
                                            name="{$model.name.certification_value}"
                                            value="{$model.value.certification_value}"
                                            placeholder="Введите значение">
                                    {if isset($model.error.certification_value)}
                                        <p class="help-block">{' '|implode:$model.error.certification_value}</p>
                                    {/if}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group {if isset($model.error.certification_type)}has-error{/if}">
                                    <label for="{$model.id.certification_type}">
                                        {$model.label.certification_type}
                                    </label>
                                     <select
                                            class="form-control"
                                            id="{$model.id.certification_type}"
                                            name="{$model.name.certification_type}">
                                    {foreach from=$types item="m" key="k"}
                                        <option value="{$k}"
                                                {if $k == $model.value.certification_type}selected="selected"{/if}>{$m.title}</option>
                                    {/foreach}
                                    </select>
                                    {if isset($model.error.certification_type)}
                                        <p class="help-block">{' '|implode:$model.error.certification_type}</p>
                                    {/if}
                                </div>
                            </div>
                        </div>
                        
                         <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group {if isset($model.error.lifestart)}has-error{/if}">
                                    <label for="{$model.id.lifestart}">
                                        {$model.label.lifestart}
                                    </label>
                                    <input
                                            type="text"
                                            class="form-control datepicker"
                                            id="{$model.id.lifestart}"
                                            name="{$model.name.lifestart}"
                                            value="{$model.value.lifestart}">
                                    {if isset($model.error.lifestart)}
                                        <p class="help-block">{' '|implode:$model.error.lifestart}</p>
                                    {/if}
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group {if isset($model.error.lifetime)}has-error{/if}">
                                    <label for="{$model.id.lifetime}">
                                        {$model.label.lifetime}
                                    </label>
                                    <input
                                            type="text"
                                            class="form-control datepicker"
                                            id="{$model.id.lifetime}"
                                            name="{$model.name.lifetime}"
                                            value="{$model.value.lifetime}">
                                    {if isset($model.error.lifetime)}
                                        <p class="help-block">{' '|implode:$model.error.lifetime}</p>
                                    {/if}
                                </div>
                            </div>
                        </div>
                        
                         <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group {if isset($model.error.certification_limit)}has-error{/if}">
                                    <label for="{$model.id.certification_limit}">
                                        {$model.label.certification_limit}
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="{$model.id.certification_limit}"
                                        name="{$model.name.certification_limit}"
                                        value="{$model.value.certification_limit}"
                                        placeholder="Лимит">
                                    {if isset($model.error.certification_limit)}
                                        <p class="help-block">{' '|implode:$model.error.certification_limit}</p>
                                    {/if}
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group {if isset($model.error.certification_comment)}has-error{/if}">
                                    <label for="{$model.id.certification_comment}">
                                        {$model.label.certification_comment}
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="{$model.id.certification_comment}"
                                        name="{$model.name.certification_comment}"
                                        value="{$model.value.certification_comment}"
                                        placeholder="Комментарий">
                                    {if isset($model.error.certification_comment)}
                                        <p class="help-block">{' '|implode:$model.error.certification_comment}</p>
                                    {/if}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group {if isset($model.error.certification_enabled)}has-error{/if}">
                                    <label for="{$model.id.certification_enabled}">
                                        {$model.label.certification_enabled}
                                    </label>
                                    <select
                                            class="form-control"
                                            id="{$model.id.certification_enabled}"
                                            name="{$model.name.certification_enabled}">
                                            {foreach from=$model.listStatus item="m" key="k"}
                                                <option value="{$k}"
                                                        {if $k == $model.value.certification_enabled && $model.value.id > 0}selected="selected"{/if}>{$m}</option>
                                            {/foreach}
                                    </select>
                                    {if isset($model.error.certification_enabled)}
                                        <p class="help-block">{' '|implode:$model.error.certification_enabled}</p>
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
                $('.datepicker').datepicker({
                    autoclose: true,
                    language: 'ru',
                    format:"dd.mm.yyyy"
                });
            })
        }(window.jQuery)
    </script>
{/literal}