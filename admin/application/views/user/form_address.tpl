<div class="row">
    <div class="col-sm-4">
        {include file="adminlte/form/input.tpl" attr="user_zip"}
    </div>
    <div class="col-sm-8">
        {include file="adminlte/form/input.tpl" attr="user_address"}
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group {if isset($model.error.user_zip)}has-error{/if}">
            <label for="{$model.id.user_zip}">
                {$model.label.user_zip}
            </label>
            <input
                    type="text"
                    class="form-control"
                    id="{$model.id.user_zip}"
                    name="{$model.name.user_zip}"
                    value="{$model.value.user_zip}"
                    placeholder="Введите {$model.label.user_zip}">
            {if isset($model.error.user_zip)}
                <p class="help-block">{' '|implode:$model.error.user_zip}</p>
            {/if}
        </div>
    </div>
</div>