<div class="col-xs-12">

    <form class="box box-info  box-solid order-filter">
        <div class="box-header with-border">
            <h3 class="box-title">Фильтр</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>    
        <div class="box-body">

          <div class="form-group">
              Заказы в статусе: &nbsp;&nbsp;&nbsp;&nbsp;
              {foreach from=$orderStatuses key="k" item="s"}
                  <label><input type="checkbox" name="filters[status][]" value="{$k}" {if in_array($k, $filters.status)}checked="checked"{/if} /> {$s}</label>
              {/foreach}
          </div>
          
          <div class="form-group">
              Тип доставки: &nbsp;&nbsp;&nbsp;&nbsp;
              {foreach from=$deliveryTypes key="k" item="s"}
                  <label><input type="checkbox" name="filters[delivery][]" value="{$k}" {if in_array($k, $filters.delivery)}checked="checked"{/if} /> {$s.title}</label>
              {/foreach}
          </div>

          <div class="form-group">
              Тип оплаты: &nbsp;&nbsp;&nbsp;&nbsp;
              {foreach from=$paymentTypes key="k" item="s"}
                  <label><input type="checkbox" name="filters[payment][]" value="{$k}" {if in_array($k, $filters.payment)}checked="checked"{/if} /> {$s.title}</label>
              {/foreach}
          </div>
          
          <div class="row">
              <div class="col-xs-2 form-group">
                  <label>Дата заказа с:</label>
                  <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" class="form-control datepicker" name="filters[date][start]" value="{$filters.date.start}" style="width: 150px;">
                  </div>
              </div>
              <div class="col-xs-2 form-group">
                  <label>Дата заказа до:</label>
                  <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" class="form-control datepicker" name="filters[date][end]" value="{$filters.date.start}" style="width: 150px;">
                  </div>
              </div>
          </div>
          
        </div>
        <div class="box-footer">
            <button type="submit" class="btn btn-info">Выбрать</button>
        </div>
    </form>
    
</div>

<div class="col-xs-12">
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Все заказы</h3>
            <div class="box-tools">
                {* <a href="/admin/coupon/create" class="btn btn-success btn-sm">Добавить купон</a> *}
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive no-padding">

            <table class="table table-hover table-striped mailbox-messages">
                <tbody><tr>
                    <th width="50"><button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i></button></th>
                    <th>№ заказа</th>
                    <th>Email</th>
                    <th>Способ оплаты</th>
                    <th>Способ доставки</th>
                    <th>Дата заказа</th>
                    <th>Последние изменения</th>
                    <th>Статус</th>
                    <th>Товаров</th>
                    <th>Сумма</th>
                    <th></th>
                </tr>
                {foreach from=$orders.data item=node}
                <tr>
                    <td><input type="checkbox" name="orderp[{$node.id}]" style="opacity: 0;"></td>
                    <td><a href="/admin/orders/view?id={$node.id}">{$node.id}</a></td>
                    <td><a href="/admin/user/profile?id={$node.user_id}">{$node.user_email}</a></td>
                    <td>{$node.user_basket_delivery_type_rus}</td>
                    <td>{$node.user_basket_payment_type_rus}</td>
                    <td>{$node.user_basket_date|datefromdb2textdate}</td>
                    <td>{if $node.user_basket_last_change_date != '0000-00-00 00:00:00'}{$node.user_basket_last_change_date|datefromdb2textdate}{else}-{/if}</td>
                    <td>{$node.status}</td>
                    <td>{$node.countGoods} шт.</td>
                    <td>{$node.sum} руб.</td>
                    <td>
                        <span class="pull-right">
                            <a href="/admin/orders/view?id={$node.id}" class="btn btn-info btn-xs" title="Просмотреть данные"><i class="fa fa-fw fa-eye"></i></a>
                            {*&nbsp;&nbsp;&nbsp;
                            <a href="/ru/orders/{$node.id}?print=true" class="btn btn-info btn-xs" target="_blank" title="Распечатать"><i class="fa fa-fw fa-print"></i></a>*}
                        </span>
                    </td>
                </tr>
                {/foreach}
                </tbody>
            </table>
        </div>
        <!-- /.box-body -->
        {if count($orders.data)}
        <div class="box-footer">
            <div class="row">
                <div class="col-sm-3">Показаны {$orders.page.offset+1} - {$orders.page.offset + count($orders.data)} из {$orders.page.itemCount}</div>
                <div class="col-sm-3">
                    <form class="search" method="get" action="{currenturl page=0}">
                    <label>Показать <select name="pageSize" class="input-search input-sm">
                            {foreach from=$orders.page.pageSizeList item=option}
                                <option {if $option==$orders.page.pageSize}selected{/if} value="{$option}">{$option}</option>
                            {/foreach}
                        </select> записей</label>
                    </form>
                </div>
                <div class="col-sm-6">
                    {include file='adminlte/paginator.tpl' pagination=$orders.page}
                </div>
            </div>
        </div>
        {/if}
    </div>
    <!-- /.box -->
</div>
{literal}
<script type="text/javascript">
!function ($) {
    $(function() {

        $('.mailbox-messages input[type="checkbox"]').iCheck({
          checkboxClass: 'icheckbox_flat-blue',
          radioClass: 'iradio_flat-blue'
        });

        //Enable check and uncheck all functionality
        $(".checkbox-toggle").click(function () {
          var clicks = $(this).data('clicks');
          if (clicks) {
            //Uncheck all checkboxes
            $(".mailbox-messages input[type='checkbox']").iCheck("uncheck");
            $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
          } else {
            //Check all checkboxes
            $(".mailbox-messages input[type='checkbox']").iCheck("check");
            $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
          }
          $(this).data("clicks", !clicks);
        });
        
        $('div.box').on('change', '.input-search', function() {

            $('form.search').submit();
        });
        
        $('.datepicker').datepicker({
            autoclose: true,
            language: 'ru',
            format:"dd.mm.yyyy"
        });
    })
}(window.jQuery)
</script>

<style>
    .order-filter input[type=checkbox] {
        vertical-align: middle;
        margin:0 3px 0 0;
    }
    
    .order-filter label {
        margin-right:20px;
    }
</style>
{/literal}