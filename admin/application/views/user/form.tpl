<div class="row">
    <div class="col-sm-12">

        <!-- form start -->
        <form role="form" method="post" enctype="multipart/form-data">

            <div class="nav-tabs-custom ">

                <ul class="nav nav-tabs">
                    <li class="active"><a href="#main" data-toggle="tab">Основные данные</a></li>
                    <li><a href="#address" data-toggle="tab">Адрес</a></li>
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

                        {include file="user/form_main.tpl"}
                    </div>

                    <div class="tab-pane" id="address">

                        {include file="user/form_address.tpl"}
                    </div>

                </div>
            </div>
        </form>
    </div>
</div>
{literal}
<script type="text/javascript">
    !function ($) {
        $(function() {

            $('.datepicker').datepicker({
                autoclose: true,
                language: 'ru',
                format:"dd.mm.yyyy"
            });

            /*$(".select2-tags").select2({
                tags: true
            });*/
        })
    }(window.jQuery)
</script>
{/literal}