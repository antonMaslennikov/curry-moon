<div class="row">
    <div class="col-sm-12">

        <!-- form start -->
        <form role="form" method="post" enctype="multipart/form-data">

            <div class="nav-tabs-custom">

                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab1" data-toggle="tab">Основные данные</a></li>
                    <li><a href="#tab2" data-toggle="tab">Изображения</a></li>
                    <li><a href="#tab5" data-toggle="tab">Склад</a></li>
                    <li><a href="#tab3" data-toggle="tab">Габариты</a></li>
                    <li><a href="#tab4" data-toggle="tab">META данные</a></li>
                </ul>

                <div class="tab-content">

                    <div class="tab-pane active" id="tab1">

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
                            <div class="col-sm-6">
                                <div class="form-group {if isset($model.error.product_name)}has-error{/if}">
                                    <label for="{$model.id.product_name}">
                                        {$model.label.product_name}
                                    </label>
                                    <input
                                            type="text"
                                            class="form-control"
                                            id="{$model.id.product_name}"
                                            name="{$model.name.product_name}"
                                            value="{$model.value.product_name}"
                                            placeholder="Введите название">
                                    {if isset($model.error.product_name)}
                                        <p class="help-block">{' '|implode:$model.error.product_name}</p>
                                    {/if}
                                </div>
                            </div>
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
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group {if isset($model.error.product_sku)}has-error{/if}">
                                    <label for="{$model.id.product_sku}">
                                        {$model.label.product_sku}
                                    </label>
                                    <input
                                            type="text"
                                            class="form-control"
                                            id="{$model.id.product_sku}"
                                            name="{$model.name.product_sku}"
                                            value="{$model.value.product_sku}"
                                            placeholder="Введите артикул">
                                    {if isset($model.error.product_sku)}
                                        <p class="help-block">{' '|implode:$model.error.product_sku}</p>
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

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group {if isset($model.error.category)}has-error{/if}">
                                    <label for="{$model.id.category}">
                                        {$model.label.category}
                                    </label>
                                    <select
                                            class="form-control"
                                            id="{$model.id.category}"
                                            name="{$model.name.category}">
                                        {foreach from=$model.listCategory item="m" key="k"}
                                            <option value="{$k}"
                                                    {if $k == $model.value.category}selected="selected"{/if}>{$m}</option>
                                        {/foreach}
                                    </select>
                                    {if isset($model.error.category)}
                                        <p class="help-block">{' '|implode:$model.error.category}</p>
                                    {/if}
                                </div>
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
                                <div class="form-group {if isset($model.error.description_short)}has-error{/if}">
                                    <label for="{$model.id.description_short}">
                                        {$model.label.description_short}
                                    </label>
                                    <textarea
                                            class="form-control"
                                            id="{$model.id.description_short}"
                                            name="{$model.name.description_short}">{$model.value.description_short}</textarea>
                                    {if isset($model.error.description_short)}
                                        <p class="help-block">{' '|implode:$model.error.description_short}</p>
                                    {/if}
                                </div>
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
                                <div class="form-group {if isset($model.error.description_long)}has-error{/if}">
                                    <label for="{$model.id.description_long}">
                                        {$model.label.description_long}
                                    </label>
                                    <textarea
                                            class="form-control tinymce-textarea"
                                            id="{$model.id.description_long}"
                                            name="{$model.name.description_long}">{$model.value.description_long}</textarea>
                                    {if isset($model.error.description_short)}
                                        <p class="help-block">{' '|implode:$model.error.description_long}</p>
                                    {/if}
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="tab-pane" id="tab2">

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
                    </div>

                    <div class="tab-pane" id="tab3">

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group {if isset($model.error.product_width)}has-error{/if}">
                                    <label for="{$model.id.product_width}">
                                        {$model.label.product_weight}
                                    </label>
                                    <input
                                            type="text"
                                            class="form-control"
                                            id="{$model.id.product_width}"
                                            name="{$model.name.product_width}"
                                            value="{$model.value.product_width}"
                                            placeholder="см">
                                    {if isset($model.error.product_width)}
                                        <p class="help-block">{' '|implode:$model.error.product_width}</p>
                                    {/if}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group {if isset($model.error.product_height)}has-error{/if}">
                                    <label for="{$model.id.product_height}">
                                        {$model.label.product_height}
                                    </label>
                                    <input
                                            type="text"
                                            class="form-control"
                                            id="{$model.id.product_height}"
                                            name="{$model.name.product_height}"
                                            value="{$model.value.product_height}"
                                            placeholder="см">
                                    {if isset($model.error.product_height)}
                                        <p class="help-block">{' '|implode:$model.error.product_height}</p>
                                    {/if}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group {if isset($model.error.product_length)}has-error{/if}">
                                    <label for="{$model.id.product_length}">
                                        {$model.label.product_length}
                                    </label>
                                    <input
                                            type="text"
                                            class="form-control"
                                            id="{$model.id.product_length}"
                                            name="{$model.name.product_length}"
                                            value="{$model.value.product_length}"
                                            placeholder="см">
                                    {if isset($model.error.product_length)}
                                        <p class="help-block">{' '|implode:$model.error.product_length}</p>
                                    {/if}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group {if isset($model.error.product_weight)}has-error{/if}">
                                    <label for="{$model.id.product_weight}">
                                        {$model.label.product_weight}
                                    </label>
                                    <input
                                            type="text"
                                            class="form-control"
                                            id="{$model.id.product_weight}"
                                            name="{$model.name.product_weight}"
                                            value="{$model.value.product_weight}"
                                            placeholder="гр">
                                    {if isset($model.error.product_weight)}
                                        <p class="help-block">{' '|implode:$model.error.product_weight}</p>
                                    {/if}
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="tab-pane" id="tab4">

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group {if isset($model.error.meta_keywords)}has-error{/if}">
                                    <label for="{$model.id.meta_keywords}">
                                        {$model.label.meta_keywords}
                                    </label>
                                    <input
                                            type="text"
                                            class="form-control"
                                            id="{$model.id.meta_keywords}"
                                            name="{$model.name.meta_keywords}"
                                            value="{$model.value.meta_keywords}">
                                    {if isset($model.error.meta_keywords)}
                                        <p class="help-block">{' '|implode:$model.error.meta_keywords}</p>
                                    {/if}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group {if isset($model.error.meta_description)}has-error{/if}">
                                    <label for="{$model.id.meta_description}">
                                        {$model.label.meta_description}
                                    </label>
                                    <textarea
                                        class="form-control"
                                        id="{$model.id.meta_description}"
                                        name="{$model.name.meta_description}">{$model.value.meta_description}</textarea>
                                {if isset($model.error.meta_description)}
                                    <p class="help-block">{' '|implode:$model.error.meta_description}</p>
                                {/if}
                                </div>
                            </div>
                        </div>

                    </div>
                    
                    <div class="tab-pane" id="tab5">

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group {if isset($model.error.quantity)}has-error{/if}">
                                    <label for="{$model.id.quantity}">
                                        {$model.label.quantity}
                                    </label>
                                    <input
                                            type="text"
                                            class="form-control"
                                            id="{$model.id.quantity}"
                                            name="{$model.name.quantity}"
                                            value="{$model.value.quantity}">
                                    {if isset($model.error.quantity)}
                                        <p class="help-block">{' '|implode:$model.error.quantity}</p>
                                    {/if}
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group {if isset($model.error.product_price)}has-error{/if}">
                                    <label for="{$model.id.product_price}">
                                        {$model.label.product_price}
                                    </label>
                                    <input
                                            type="text"
                                            class="form-control"
                                            id="{$model.id.product_price}"
                                            name="{$model.name.product_price}"
                                            value="{$model.value.product_price}">
                                    {if isset($model.error.product_price)}
                                        <p class="help-block">{' '|implode:$model.error.product_price}</p>
                                    {/if}
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group {if isset($model.error.product_discount)}has-error{/if}">
                                    <label for="{$model.id.product_discount}">
                                        {$model.label.product_discount}
                                    </label>
                                    <input
                                            type="text"
                                            class="form-control"
                                            id="{$model.id.product_discount}"
                                            name="{$model.name.product_discount}"
                                            value="{$model.value.product_discount}">
                                    {if isset($model.error.product_discount)}
                                        <p class="help-block">{' '|implode:$model.error.product_discount}</p>
                                    {/if}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                    <button type="submit" class="btn btn-info" name="apply">Применить</button>
                </div>

            </div>
        </form>

    </div>
</div>

{if $smarty.const.appMode eq 'dev'}
    <pre>{$model|print_r}</pre>
{/if}

{literal}
<script src="https://cloud.tinymce.com/stable/tinymce.min.js"></script>
<script type="text/javascript">
    !function ($) {
        $(function() {

            {/literal}
            var sourceField = $('#{$model.id.product_name}'),
                    targetField = $('#{$model.id.slug}');
            {literal}

            tinymce.init({
                selector: '.tinymce-textarea',
                menubar: false
            });

            var timer,
                    updateUrl = "/admin/api/transliterate",
                    editable = targetField.val().length == 0,
                    value = sourceField.val();

            if (targetField.val().length !== 0) {
                $.get(updateUrl, {data: sourceField.val()}, function (r) {
                    editable = targetField.val() == r;
                });
            }

            sourceField.on('keyup blur copy paste cut start', function () {
                clearTimeout(timer);

                if (editable && value != sourceField.val()) {
                    timer = setTimeout(function () {
                        value = sourceField.val();
                        targetField.attr('disabled', 'disabled');
                        $.get(updateUrl, {data: sourceField.val()}, function (r) {
                            targetField.val(r).removeAttr('disabled');
                        });
                    }, 300);
                }
            });

            targetField.on('change', function () {
                editable = $(this).val().length == 0;
            });

            $(".select2-tags").select2({
                tags: true
            });

            $('.main-img-js').on('click', function() {

                var img_id = $(this).data('img'),
                    product_id = $(this).data('product'),
                    actionUrl = "/admin/product/mainImage?",
                    link = $(this);

                $.get(actionUrl, {"product": product_id, "image": img_id }, function (r) {

                    link.parents('div.pictures').find('div.picture_img img').css({"border":'none'});
                    link.parents('div.pictures').find('span.img-buttons').show();

                    link.parents('div.picture_img').find('img').css({"border":"1px solid red"});
                    link.parents('div.picture_img').find('span.img-buttons').hide();
                });


            });

            $('.delete-img-js').on('click', function() {

                var img_id = $(this).data('img'),
                    product_id = $(this).data('product'),
                    actionUrl = "/admin/product/deleteImage?",
                    link = $(this);

                if (!confirm("Вы действительно желаете удалить изображение?")) return false;

                $.get(actionUrl, {"product": product_id, "image": img_id }, function (r) {

                    link.parents('div.picture_img').remove();
                });


            })
        })
    }(window.jQuery)
</script>
{/literal}