<div class="row">
    <div class="col-sm-12">

        <!-- form start -->
        <form role="form" method="post" enctype="multipart/form-data">

            <div class="nav-tabs-custom">

                <ul class="nav nav-tabs">
                    <li class="active"><a href="#main" data-toggle="tab">Основные данные</a></li>
                    <li><a href="#image" data-toggle="tab">Изображения</a></li>
                    <li><a href="#stock" data-toggle="tab">Склад</a></li>
                    <li><a href="#dimensions" data-toggle="tab">Габариты</a></li>
                    {if !$model.value.newRecord}
                    <li><a href="#related" data-toggle="tab">Сопутствующие товары</a></li>
                    {/if}
                    <li><a href="#meta" data-toggle="tab">META данные</a></li>
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
                        {include file="product/form_main.tpl"}
                    </div>
                    <div class="tab-pane" id="image">
                        {include file="product/form_picture.tpl"}
                    </div>

                    <div class="tab-pane" id="dimensions">
                        {include file="product/form_dimensions.tpl"}
                    </div>

                    <div class="tab-pane" id="meta">
                        {include file="product/form_meta.tpl"}
                    </div>
                    
                    <div class="tab-pane" id="stock">
                        {include file="product/form_stock.tpl"}
                    </div>

                    {if !$model.value.newRecord}
                    <div class="tab-pane" id="related">
                        {include file="product/form_related.tpl"}
                    </div>
                    {/if}

                </div>

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                    <button type="submit" class="btn btn-info" name="apply">Применить</button>
                    <a href="/admin/product/list" class="btn btn-default">Отмена</a>
                    {if $product->id > 0}
                    <a href="/ru/shop/openproduct/{$product->id}" class="btn btn-success pull-right">Посмотреть на сайте</a>
                    {/if}
                </div>

            </div>
        </form>

    </div>
</div>


<div class="modal fade" id="product-alt-modal">

    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/admin/product/save_pic_data" id="product-pic-form" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Title и alt изображения</h4>
                </div>
                <div class="modal-body">

                        <div class="row">
                            <div class="col-sm-12">
                               <div class="form-group">
                                   <label for="pic-edit-title">Title</label>
                                   <input type="text" class="form-control" name="title"> 
                               </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                               <div class="form-group">
                                   <label for="pic-edit-alt">Alt</label>
                                       <input type="text" class="form-control" name="alt">
                                </div>
                            </div>
                        </div>

                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Закрыть</button>
                    <button class="btn btn-success pull-right" type="submit">Сохранить</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

{literal}
<script src="/public/packages/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
    !function ($) {
        $(function() {

            {/literal}
            var sourceField = $('#{$model.id.product_name}'),
                    targetField = $('#{$model.id.slug}');
            {literal}

            tinymce.init({
                selector: '.tinymce-textarea',
                menubar: false,
                plugins: "code,link",
                toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link | code"
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