<div class="row">
    <div class="col-sm-4">
        <div class="form-group {if isset($model.error.picture)}has-error{/if}">
            <label for="{$model.id.picture}">
                {$model.label.picture}
            </label>
            <input
                    type="file"
                    id="{$model.id.picture}"
                    name="{$model.name.picture}[]"
                    multiple
                    accept="image/jpeg,image/png,image/jpg,image/gif"
                    value="">
            {if isset($model.error.picture)}
                <p class="help-block">{' '|implode:$model.error.picture}</p>
            {else}
                <p class="help-block">Одновременно можно загрузить не более 5 изображений</p>
            {/if}
        </div>
    </div>

    {if (!$model.value.newRecord)}

        <div class="col-sm-8">
            <div class="form-group">
                <label>Загруженные изображение</label>
                {if $product->pictures|count > 0}
                    <div class="row pictures" style="padding: 0;">
                        {foreach from=$product->pictures item="p"}
                            <div class="col-sm-4 picture_img">
                                <p class="text-center"><a href="{$p.orig_path}" target="blank">
                                        <img src="{$p.thumb_path}" style="max-height: 150px; margin-right:10px;border:1px solid #ccc;border-radius:3px;{if $p.thumb_id == $model.value.picture_id}border-color:red{/if}">
                                    </a></p>
                                <p class="text-center" style="min-height: 24px">
                                            <span class="img-buttons" {if $p.thumb_id == $model.value.picture_id}style="display:none"{/if}>
                                            <a href="javascript:void(0)" class="btn btn-xs btn-success main-img-js" data-product="{$model.value.id}" data-img="{$p.thumb_id}" title="Сделать главной"><i class="fa fa-fw fa-check"></i></a>
                                            </span>
                                    <a href="javascript:void(0)" class="btn btn-xs btn-danger delete-img-js" data-product="{$model.value.id}" data-img="{$p.thumb_id}" title="Удалить изображение"><i class="fa fa-fw fa-times"></i></a>
                                </p>
                            </div>
                        {/foreach}
                    </div>
                {else}
                    <p class="form-control-static">Нет изображений</p>
                {/if}

            </div>
        </div>
    {/if}
</div>