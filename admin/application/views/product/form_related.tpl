<div id="related_product">
<div class="row">
    <div class="col-sm-12">
        <input type="hidden" id="product-id" value="{$model.value.id}">
        <a href="javascript:void(0)" id="add-related-button" style="border-bottom: 1px dashed">Добавить<i class="fa fa-long-arrow-right"></i></a>
    </div>
</div>
<div class="row">
    <div class="col-sm-12" id="listRelated">
        {include file="product/list_related.tpl" listRelated=$model.listRelated}
    </div>
</div>
<div class="modal fade" id="product-related-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Выберите товар</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-3"><label style="line-height: 34px;" for="search-product-related">Поиск товара:</label></div>
                    <div class="col-sm-9"><input class="form-control" id="search-product-related" /></div>
                </div>
                <div class="row">
                    <div class="col-sm-12 search">

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
</div>
{literal}
<script type="text/javascript">
    !function ($) {
        $(function() {

            function getData() {

                $.get(
                    '/admin/product/list_filter',
                    { search: $('#search-product-related').val(), id: $('#product-id').val() },
                    function( data ) {

                        $('#product-related-modal .modal-body .search').html( data );
                });
            }


            function updateRelated() {

                $.get(
                    '/admin/product/list_related',
                    {id: $('#product-id').val() },
                    function( data ) {

                        $('#listRelated').html( data );
                    }
                );
            }


            function removeRelated(id) {

                $.get(
                    '/admin/product/set_related',
                    { related: id, id: $('#product-id').val() , action:0},
                    function( data ) {

                        updateRelated();
                    });
            }


            function setRelated(obj) {

                $.get(
                        '/admin/product/set_related',
                        { related: obj.data('id'), id: $('#product-id').val() , action:obj.is(':checked')?1:0},
                        function( data ) {

                            updateRelated();
                        });
            }


            $('body').on('click', '#add-related-button', function() {

                $('#product-related-modal').modal({
                    keyboard: false
                });
            })

            $('#product-related-modal').on('shown.bs.modal', function () {

                $('#search-product-related').val('');
                getData();
            });

            $('body').on('keyup paste', '#search-product-related', function() {

                getData();
            })

            $('body').on('change', '.check-related', function() {

                setRelated($(this));
            })

            $('body').on('click', '.remove-related', function() {

                removeRelated($(this).data('rel'));
            })
        });
    }(window.jQuery)
</script>
{/literal}