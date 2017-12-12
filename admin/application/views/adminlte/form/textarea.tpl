<div class="form-group {if isset($model.error.$attr)}has-error{/if}">
    <label for="{$model.id.$attr}">
        {$model.label.$attr}
    </label>
                                    <textarea
                                            type="text"
                                            class="form-control {if isset($class_selector)}{$class_selector}{/if}"
                                            id="{$model.id.$attr}"
                                            name="{$model.name.$attr}"
                                            placeholder="Введите {$model.label.$attr}">{$model.value.$attr}</textarea>
    {if isset($model.error.$attr)}
        <p class="help-block">{' '|implode:$model.error.$attr}</p>
    {/if}
</div>