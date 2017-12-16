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
                {foreach from=$orders item=node}
                <tr>
                    <td><input type="checkbox" name="orderp[{$node.id}]" style="opacity: 0;"></td>
                    <td><a href="/admin/orders/view?id={$node.id}">{$node.id}</a></td>
                    <td><a href="/admin/users/view?id={$node.user_id}">{$node.user_email}</a></td>
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
                        </span>
                        <span class="pull-right">
                            <a href="/admin/orders/print?id={$node.id}" class="btn btn-info btn-xs" title="Распечатать"><i class="fa fa-fw fa-eye"></i></a>
                        </span>
                    </td>
                </tr>
                {/foreach}
                </tbody>
            </table>
        </div>
        <!-- /.box-body -->
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
    })
}(window.jQuery)
</script>
{/literal}