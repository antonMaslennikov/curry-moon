<div class="row">
    <div class="col-sm-12">
        <div class="callout callout-warning">
            <p>Поля, отмеченные <span class="text-danger">*</span>, обязательны для заполнения!</p>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group {if isset($model.error.user_login)}has-error{/if}">
            <label for="{$model.id.user_login}">
                {$model.label.user_login} <span class="text-danger">*</span>
            </label>
            <input
                    type="text"
                    class="form-control"
                    id="{$model.id.user_login}"
                    name="{$model.name.user_login}"
                    value="{$model.value.user_login}"
                    placeholder="Введите {$model.label.user_login|mb_strtolower}">
            {if isset($model.error.user_login)}
                <p class="help-block">{' '|implode:$model.error.user_login}</p>
            {/if}
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group {if isset($model.error.user_name)}has-error{/if}">
            <label for="{$model.id.user_name}">
                {$model.label.user_name} <span class="text-danger">*</span>
            </label>
            <input
                    type="text"
                    class="form-control"
                    id="{$model.id.user_name}"
                    name="{$model.name.user_name}"
                    value="{$model.value.user_name}"
                    placeholder="Введите {$model.label.user_name}">
            {if isset($model.error.user_login)}
                <p class="help-block">{' '|implode:$model.error.user_login}</p>
            {/if}
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group {if isset($model.error.user_show_name)}has-error{/if}">
            <label for="{$model.id.user_show_name}">
                {$model.label.user_show_name}
            </label>
            <select
                    class="form-control"
                    id="{$model.id.user_show_name}"
                    name="{$model.name.user_show_name}">
                {foreach from=$model.show_name_list item="m" key="k"}
                    <option value="{$k}"
                            {if $k == $model.value.user_show_name}selected="selected"{/if}>{$m}</option>
                {/foreach}
            </select>
            {if isset($model.error.user_show_name)}
                <p class="help-block">{' '|implode:$model.error.user_show_name}</p>
            {/if}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group {if isset($model.error.user_phone)}has-error{/if}">
            <label for="{$model.id.user_phone}">
                {$model.label.user_phone}
            </label>
            <input
                    type="text"
                    class="form-control"
                    id="{$model.id.user_phone}"
                    name="{$model.name.user_phone}"
                    value="{$model.value.user_phone}"
                    placeholder="Введите {$model.label.user_phone|mb_strtolower}">
            {if isset($model.error.user_phone)}
                <p class="help-block">{' '|implode:$model.error.user_phone}</p>
            {/if}
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group {if isset($model.error.user_email)}has-error{/if}">
            <label for="{$model.id.user_email}">
                {$model.label.user_email} <span class="text-danger">*</span>
            </label>
            <input
                    type="text"
                    class="form-control"
                    id="{$model.id.user_email}"
                    name="{$model.name.user_email}"
                    value="{$model.value.user_email}"
                    placeholder="Введите {$model.label.user_email|mb_strtolower}">
            {if isset($model.error.user_email)}
                <p class="help-block">{' '|implode:$model.error.user_email}</p>
            {/if}
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group {if isset($model.error.user_show_email)}has-error{/if}">
            <label for="{$model.id.user_show_email}">
                {$model.label.user_show_email}
            </label>
            <select
                    class="form-control"
                    id="{$model.id.user_show_email}"
                    name="{$model.name.user_show_email}">
                {foreach from=$model.show_name_list item="m" key="k"}
                    <option value="{$k}"
                            {if $k == $model.value.user_show_email}selected="selected"{/if}>{$m}</option>
                {/foreach}
            </select>
            {if isset($model.error.user_show_email)}
                <p class="help-block">{' '|implode:$model.error.user_show_email}</p>
            {/if}
        </div>
    </div>
</div>