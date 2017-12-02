<div class="box {if $model.errorSummary}box-danger{else}box-primary{/if}">
    <div class="box-header with-border">
        <h3 class="box-title">{$PAGE->title}</h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form role="form" method="post" enctype="multipart/form-data">
        <div class="box-body">
            {if $model.errorSummary}
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <div class="callout callout-danger">
                            <h4>Ошибки!</h4>
                            {$model.errorSummary}
                        </div>
                    </div>
                </div>
            </div>
            {/if}
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group {if isset($model.error.slug)}has-error{/if}">
                        <label for="{$model.id.slug}">
                            {$model.label.slug}
                        </label>
                        <input
                                type="text"
                                class="form-control"
                                id="{$model.id.slug}"
                                name="{$model.name.slug}"
                                value="{$model.value.slug}"
                                placeholder="Введите slug">
                        {if isset($model.error.slug)}
                            <p class="help-block">{' '|implode:$model.error.slug}</p>
                        {/if}
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group {if isset($model.error.title)}has-error{/if}">
                        <label for="{$model.id.title}">
                            {$model.label.title}
                        </label>
                        <input
                                type="text"
                                class="form-control"
                                id="{$model.id.title}"
                                name="{$model.name.title}"
                                value="{$model.value.title}"
                                placeholder="Введите название">
                        {if isset($model.error.title)}
                            <p class="help-block">{' '|implode:$model.error.title}</p>
                        {/if}
                    </div>
                </div>
            </div>
            {if ($model.value.newRecord)}
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group {if isset($model.error.picture)}has-error{/if}">
                        <label for="{$model.id.picture}">
                            {$model.label.picture}
                        </label>
                        <input
                                type="file"
                                id="{$model.id.picture}"
                                name="{$model.name.picture}"
                                value=""
                                placeholder="Введите название">
                        {if isset($model.error.picture)}
                            <p class="help-block">{' '|implode:$model.error.picture}</p>
                        {/if}
                    </div>
                </div>
            </div>
            {else}
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group {if isset($model.error.picture)}has-error{/if}">
                            <label for="{$model.id.picture}">
                                {$model.label.picture}
                            </label>
                            <input
                                    type="file"
                                    id="{$model.id.picture}"
                                    name="{$model.name.picture}"
                                    value=""
                                    placeholder="Введите название">
                            {if isset($model.error.picture)}
                                <p class="help-block">{' '|implode:$model.error.picture}</p>
                            {/if}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Загруженное изображение</label>
                            <p class="form-control-static">
                                {if $model.value.picture_id>0}
                                    <img src="{$model.value.picture_id|pictureId2path}" style="width: 50px">
                                {else}
                                    Нет изображения
                                {/if}
                        </div>
                    </div>
                </div>
            {/if}
            <div class="row">
                <div class="col-sm-12">
                    <div class="checkbox">
                        <label>
                            <input
                                type="checkbox"
                                name="{$model.name.status}"
                                {if $model.value.status}checked{/if}
                                value="true"
                            > {$model.label.status}
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <button type="submit" class="btn btn-primary">{$button}</button>&nbsp;
            {if isset($cancel)}
            <a href="list" class="btn btn-default">{$cancel}</a>
            {/if}
        </div>
    </form>
</div>
{if $smarty.const.appMode eq 'dev'}
    <pre>{$model|print_r}</pre>
{/if}