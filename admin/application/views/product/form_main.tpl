<div class="row">
    <div class="col-sm-6">
        {include file="adminlte/form/input.tpl" attr="product_name" required="1"}
    </div>
    <div class="col-sm-6">
        {include file="adminlte/form/input.tpl" attr="slug"}
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        {include file="adminlte/form/input.tpl" attr="product_sku"}
    </div>
    <div class="col-sm-6">
        {include file="adminlte/form/select.tpl" attr="status" list=$model.listStatus}
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        {include file="adminlte/form/select.tpl" attr="category" list=$model.listCategory}
    </div>
    <div class="col-sm-6">
        <div class="form-group {if isset($model.error.manufacturer)}has-error{/if}">
            <label for="{$model.id.manufacturer}">
                {$model.label.manufacturer}
            </label>
            <select
                    class="form-control"
                    id="{$model.id.manufacturer}"
                    name="{$model.name.manufacturer}">
                {foreach from=$manufacturers item="m" key="k"}
                    <option value="{$k}"
                            {if $k == $model.value.manufacturer}selected="selected"{/if}>{$m.name}</option>
                {/foreach}
            </select>
            {if isset($model.error.manufacturer)}
                <p class="help-block">{' '|implode:$model.error.manufacturer}</p>
            {/if}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        {include file="adminlte/form/textarea.tpl" attr="description_short" class_selector="tinymce-textarea"}
    </div>
    <div class="col-sm-6">
        <div class="form-group {if isset($model.error.tags)}has-error{/if}">
            <label for="{$model.id.tags}">
                {$model.label.tags}
            </label>
            <select
                    multiple="multiple"
                    class="form-control select2-tags"
                    id="{$model.id.tags}"
                    name="{$model.name.tags}[]">
                {foreach from=$model.listAllTags item="m" key="k"}
                    <option value="{$k}"
                            {if in_array($k, $model.value.tags)}selected="selected"{/if}>{$m}</option>
                {/foreach}
            </select>
            {if isset($model.error.tags)}
                <p class="help-block">{' '|implode:$model.error.tags}</p>
            {/if}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        {include file="adminlte/form/textarea.tpl" attr="description_long" class_selector="tinymce-textarea"}
    </div>
</div>