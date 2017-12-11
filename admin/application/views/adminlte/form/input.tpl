<div class="form-group {if isset($model.error.$attr)}has-error{/if}">
    <label for="{$model.id.$attr}">
        {$model.label.$attr} {if isset($required)}<span class="text-danger">*</span>{/if}
    </label>
    <input
            type="text"
            class="form-control {if isset($class_selector)}{$class_selector}{/if}"
            id="{$model.id.$attr}"
            name="{$model.name.$attr}"
            value="{$model.value.$attr}"
            placeholder="{if isset($placeholder)}{$placeholder}{else}Введите {$model.label.$attr}{/if}">
    {if isset($model.error.$attr)}
        <p class="help-block">{' '|implode:$model.error.$attr}</p>
    {/if}
</div>