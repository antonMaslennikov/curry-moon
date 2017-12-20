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
                                {include file="adminlte/form/input.tpl" attr="mail_template_name" required="1"}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                {include file="adminlte/form/input.tpl" attr="mail_template_subject" required="1"}
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group {if isset($model.error.mail_template_order)}has-error{/if}">
                                    <label for="{$model.id.mail_template_order}">
                                        {$model.label.mail_template_order}
                                    </label>
                                     <select
                                            class="form-control"
                                            id="{$model.id.mail_template_order}"
                                            name="{$model.name.mail_template_order}">
                                            <option value=""></option>
                                    {foreach from=$model.categorys item="m" key="k"}
                                        <option value="{$k}"
                                                {if $k == $model.value.mail_template_order}selected="selected"{/if}>{$m}</option>
                                    {/foreach}
                                    </select>
                                    {if isset($model.error.mail_template_order)}
                                        <p class="help-block">{' '|implode:$model.error.mail_template_order}</p>
                                    {/if}
                                </div>
                            </div>
                        </div>
                        
                        {if $model.value.mail_template_file}
                        <div class="row">
                            <div class="col-sm-6">
                                
                                <b>Путь до файла:</b> {$tplDir}{$model.value.mail_template_file}
                                
                            </div>
                        </div>
                        {/if}
                        
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                    <button type="submit" class="btn btn-info" name="apply">Применить</button>
                    <a href="/admin/templates" class="btn btn-default">Отмена</a>
                    
                    {if $model.value.id > 0}
                    <a href="/admin/templates/view?id={$model.value.id}" class="btn btn-info pull-right">Просмотреть</a>
                    {/if}
                </div>
            </div>

        </form>
    </div>
</div>
{literal}
    <script type="text/javascript">
        !function ($) {
            $(function() {
                
            })
        }(window.jQuery)
    </script>
{/literal}