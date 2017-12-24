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
                                    <a href="#" data-id="{$p.id}" data-title="{$p.title}" data-alt="{$p.alt}" class="btn btn-warning btn-xs edit-alt" title="Изменить alt и title"><i class="fa fa-fw fa-pencil"></i></a>
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


<script>
    
    $('document').ready(function() {
        
        $('.edit-alt').click(function() {

            $('#product-pic-form input[name=title]').val($(this).attr('data-title'));
            $('#product-pic-form input[name=alt]').val($(this).attr('data-alt'));
            $('#product-pic-form input[name=id]').val($(this).attr('data-id'));
            
            $('#product-alt-modal').modal({
                keyboard: false
            });

            return false;
        });
        
        $('#product-pic-form').submit(function(){
            
            f = $(this);
            
            $.post($(this).attr('action'), $(this).serialize(), function() {
                id = f.find('input[name=id]').val();
                alt = f.find('input[name=alt]').val();
                title = f.find('input[name=title]').val();
                $('a[data-id=' + id + ']').attr('data-title', title);
                $('a[data-id=' + id + ']').attr('data-alt', alt);
                
                $('#product-alt-modal').modal('hide');
            });
            
            return false;
            
        });
        
    });
    
</script>