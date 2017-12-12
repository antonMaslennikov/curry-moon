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

            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Сохранить</button>
                <button type="submit" class="btn btn-info" name="apply">Применить</button>
                <a href="/admin/user/list" class="btn btn-default">Отмена</a>
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

            $(".select2-js").select2({
                theme: "bootstrap"
              //  tags: true
            });

            var element = $(".select2-city").select2({
                theme: "bootstrap",
                tags: true
            });

            $(".select2-js").on('change', function() {

                var country = $(".select2-js").val();

                if (!country) return;

                $.getJSON('/admin/user/city', {data: country}, function(r) {

                    element.html('');

                    var item;
                    for (var i = 0; i < r.length; i++) {

                        item = r[i];
                        console.log(item);
                        element.append("<option value=\"" + item.id + "\">" + item.text + "</option>");
                    }

                    element.trigger('change');
                });

                console.log(country);
            })

        })
    }(window.jQuery)
</script>
{/literal}