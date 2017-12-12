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
                                {include file="adminlte/form/input.tpl" attr="certification_password" required="1"}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                {include file="adminlte/form/input.tpl" attr="certification_value"}
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
                                {include file="adminlte/form/input.tpl" attr="lifestart" class_selector="datepicker"}
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-sm-6">
                                {include file="adminlte/form/input.tpl" attr="lifetime" class_selector="datepicker"}
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group {if isset($model.error.certification_multi)}has-error{/if}">
                                    <label for="{$model.id.certification_multi}">
                                        {$model.label.certification_multi}
                                    </label>
                                     <select
                                            class="form-control"
                                            id="{$model.id.certification_multi}"
                                            name="{$model.name.certification_multi}">
                                    {foreach from=$model.multi item="m" key="k"}
                                        <option value="{$k}"
                                                {if $k == $model.value.certification_multi}selected="selected"{/if}>{$m}</option>
                                    {/foreach}
                                    </select>
                                    {if isset($model.error.certification_multi)}
                                        <p class="help-block">{' '|implode:$model.error.certification_multi}</p>
                                    {/if}
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-sm-6">
                                {include file="adminlte/form/input.tpl" attr="certification_limit"}
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-sm-6">
                                {include file="adminlte/form/input.tpl" attr="certification_comment"}
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
                    <a href="/admin/coupon" class="btn btn-default">Отмена</a>
                </div>
            </div>

        </form>
    </div>
</div>
{literal}
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