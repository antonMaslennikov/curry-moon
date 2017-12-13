<div class="row">
    <div class="col-sm-4">
        {include file="adminlte/form/input.tpl" attr="user_zip"}
    </div>
    <div class="col-sm-8">
        {include file="adminlte/form/input.tpl" attr="user_address"}
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        {include file="adminlte/form/select.tpl" attr="user_country_id" list=$model.countryList class_selector="select2-js" style="width: 100%;"}
    </div>
    <div class="col-sm-6">
        <div class="form-group {if isset($model.error.user_city_id)}has-error{/if}">
            <label for="{$model.id.user_city_id}">
                {$model.label.user_city_id}
            </label>
            <select
                    class="form-control select2-city"
                    id="{$model.id.user_city_id}"
                    style="width:100%"
                    name="{$model.name.user_city_id}">
                {foreach from=$model.cityList item="m" key="k"}
                    <option value="{$k}"
                            {if $k == $model.value.user_city_id}selected="selected"{/if}>{$m}</option>
                {/foreach}
            </select>
            {if isset($model.error.user_city_id)}
                <p class="help-block">{' '|implode:$model.error.user_city_id}</p>
            {/if}
        </div>
    </div>
</div>