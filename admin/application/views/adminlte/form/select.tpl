<div class="form-group {if isset($model.error.$attr)}has-error{/if}">
    <label for="{$model.id.$attr}">
        {$model.label.$attr} {if isset($required)}<span class="text-danger">*</span>{/if}
    </label>
    <select
            class="form-control {if isset($class_selector)}{$class_selector}{/if}"
            id="{$model.id.$attr}"
            name="{$model.name.$attr}"
            {if isset($style)}style="{$style}"{/if}
            >
        {foreach from=$list item="m" key="k"}
            <option value="{$k}"
                    {if $k == $model.value.$attr}selected="selected"{/if}>{$m}</option>
        {/foreach}
    </select>
    {if isset($model.error.$attr)}
        <p class="help-block">{' '|implode:$model.error.$attr}</p>
    {/if}
</div>