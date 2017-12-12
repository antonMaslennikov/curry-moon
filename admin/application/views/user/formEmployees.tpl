<div class="row">
    <div class="col-sm-12">

        <!-- form start -->
        <form role="form" method="post">

            <div class="box box-warning">
                <div class="box-header">
                    <h3 class="box-title">{$PAGE->title}</h3>
                </div>
                <div class="box-body">
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
                        <div class="col-sm-6">
                        {if $model.value.newRecord}
                            {include file="adminlte/form/select.tpl" attr="id" list=$model.userList required="1" class_selector="select2-js"}
                        {else}
                            <div class="form-group">
                                <label for="{$model.id.id}">
                                    {$model.label.id}
                                </label>
                                <p class="form-control-static">{$model.value.login}</p>
                            </div>
                        {/if}
                        </div>
                        <div class="col-sm-6">
                            {include file="adminlte/form/select.tpl" attr="meta_value" list=$model.teamList required="1"}
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                    <button type="submit" class="btn btn-info" name="apply">Применить</button>
                    <a href="/admin/user/employees" class="btn btn-default">Отмена</a>
                </div>
            </div>
        </form>
    </div>
</div>
{literal}
    <script type="text/javascript">
        !function ($) {
            $(function() {

               $(".select2-js").select2({
                    theme: "bootstrap"
                    //  tags: true
               });
            })
        }(window.jQuery)
    </script>
{/literal}