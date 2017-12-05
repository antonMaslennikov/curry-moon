<div class="row">
    <div class="col-sm-12">

        <!-- form start -->
        <form role="form" method="post" enctype="multipart/form-data">

            <div class="nav-tabs-custom ">

                <ul class="nav nav-tabs">
                    <li class="active"><a href="#main" data-toggle="tab">Основные данные</a></li>
                </ul>

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
                                <div class="form-group {if isset($model.error.menu_id)}has-error{/if}">
                                    <label for="{$model.id.menu_id}">
                                        {$model.label.menu_id} <span class="text-danger">*</span>
                                    </label>
                                    <select
                                            class="form-control"
                                            id="{$model.id.menu_id}"
                                            name="{$model.name.menu_id}">
                                        {foreach from=$model.listMenu item="m" key="k"}
                                            <option value="{$k}"
                                                    {if $k == $model.value.menu_id}selected="selected"{/if}>{$m}</option>
                                        {/foreach}
                                    </select>
                                    {if isset($model.error.menu_id)}
                                        <p class="help-block">{' '|implode:$model.error.menu_id}</p>
                                    {/if}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group {if isset($model.error.title_ru)}has-error{/if}">
                                    <label for="{$model.id.title_ru}">
                                        {$model.label.title_ru} <span class="text-danger">*</span>
                                    </label>
                                    <input
                                            type="text"
                                            class="form-control"
                                            id="{$model.id.title_ru}"
                                            name="{$model.name.title_ru}"
                                            value="{$model.value.title_ru}"
                                            placeholder="Введите {$model.label.title_ru|mb_strtolower}">
                                    {if isset($model.error.title_ru)}
                                        <p class="help-block">{' '|implode:$model.error.title_ru}</p>
                                    {/if}
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group {if isset($model.error.title_en)}has-error{/if}">
                                    <label for="{$model.id.title_en}">
                                        {$model.label.title_en}
                                    </label>
                                    <input
                                            type="text"
                                            class="form-control"
                                            id="{$model.id.title_en}"
                                            name="{$model.name.title_en}"
                                            value="{$model.value.title_en}"
                                            placeholder="Введите {$model.label.title_en|mb_strtolower}">
                                    {if isset($model.error.title_en)}
                                        <p class="help-block">{' '|implode:$model.error.title_en}</p>
                                    {/if}
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group {if isset($model.error.url)}has-error{/if}">
                                    <label for="{$model.id.url}">
                                        {$model.label.url}
                                    </label>
                                    <select
                                            class="form-control"
                                            id="{$model.id.url}"
                                            name="{$model.name.url}">
                                        {foreach from=$model.listRoute item="m" key="k"}
                                            <option value="{$k}"
                                                    {if $k == $model.value.url}selected="selected"{/if}>{$m} ({$k})</option>
                                        {/foreach}
                                    </select>
                                    {if isset($model.error.url)}
                                        <p class="help-block">{' '|implode:$model.error.url}</p>
                                    {/if}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group {if isset($model.error.sort)}has-error{/if}">
                                    <label for="{$model.id.sort}">
                                        {$model.label.sort}
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="{$model.id.sort}"
                                        name="{$model.name.sort}"
                                        value="{$model.value.sort}">
                                    {if isset($model.error.sort)}
                                        <p class="help-block">{' '|implode:$model.error.sort}</p>
                                    {/if}
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group {if isset($model.error.status)}has-error{/if}">
                                    <label for="{$model.id.status}">
                                        {$model.label.status}
                                    </label>
                                    <select
                                            class="form-control"
                                            id="{$model.id.status}"
                                            name="{$model.name.status}">
                                        {foreach from=$model.listStatus item="m" key="k"}
                                            <option value="{$k}"
                                                    {if $k == $model.value.status}selected="selected"{/if}>{$m}</option>
                                        {/foreach}
                                    </select>
                                    {if isset($model.error.status)}
                                        <p class="help-block">{' '|implode:$model.error.status}</p>
                                    {/if}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                    <button type="submit" class="btn btn-info" name="apply">Применить</button>
                    <a href="/admin/menu/item{if $model.value.menu_id}?menu_id={$model.value.menu_id}{/if}" class="btn btn-default">Отмена</a>
                </div>

        </form>
    </div>
</div>

{if $smarty.const.appMode eq 'dev'}
    <pre>{$model|print_r}</pre>
{/if}

