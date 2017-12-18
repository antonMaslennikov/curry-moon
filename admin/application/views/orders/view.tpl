<div class="row">
    <div class="col-sm-12">

        <div class="nav-tabs-custom ">

            <ul class="nav nav-tabs">
                <li class="active"><a href="#main" data-toggle="tab">Основные данные</a></li>
                <li><a href="#log" data-toggle="tab">История заказа</a></li>
                <li><a href="#comments" data-toggle="tab">Комментарии к заказу</a></li>
            </ul>

            <div class="tab-content">

                <div class="tab-pane active" id="main">

                    <div class="row">
                        <div class="col-sm-12"><h2>Статус заказ: <a href="#">{$order->status_rus}</a></h2></div>
                    </div>

                    <br>

                    <div class="row">
                        <div class="col-sm-4">
                            <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                                <tr>
                                   <th colspan="2">Данные заказчика</th>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td>{$order->user->user_email}</td>
                                </tr>
                                <tr>
                                    <td>Имя</td>
                                    <td>{$order->user->user_name}</td>
                                </tr>
                                <tr>
                                    <td>Телефон</td>
                                    <td>{$order->user->user_phone}</td>
                                </tr>
                                <tr>
                                    <td>Дата регистрации</td>
                                    <td>{$order->user->user_register_date|datefromdb2textdate}</td>
                                </tr>
                            </table>
                            
                            <form role="form" method="post" id="paymentForm">   

                                <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                                    <tr>
                                       <th colspan="2">Оплата</th>
                                    </tr>
                                    <tr>
                                        <td>Тип оплаты</td>
                                        <td>
                                            <span>{$order->user_basket_payment_type_rus}</span>
                                            <select name="payment_type" id="">
                                                {foreach from=$paymentTypes key="k" item="pt"}
                                                <option value="{$k}" {if $k == $order->user_basket_payment_type}selected="selected"{/if}>{$pt.title}</option>
                                                {/foreach}
                                            </select>
                                            
                                            {if $order->user_basket_payment_confirm == "true"}
                                                <span class="label label-success margin">Оплачен полностью</span>
                                            {else}
                                                {if $order->alreadyPayed > 0}
                                                    <span class="label label-warning margin">Оплачен на {$order->alreadyPayed} руб.</span>
                                                {else}
                                                    <span class="label label-danger margin">Не оплачен</span>
                                                {/if}
                                            {/if}
                                        </td>
                                    </tr>
                                    {if $order->user_basket_payment_partical > 0}
                                    <tr>
                                        <td>Оплачено купоном</td>
                                        <td>{$order->user_basket_payment_partical} руб.</td>
                                    </tr>
                                    {/if}
                                    {if $order->user_basket_status != 'delivered' && $order->user_basket_status != 'canceled'}
                                    <tfoot>
                                        <tr>
                                            <th style="border-right: none">
                                                <button class="btn btn-block-sm btn-success" name="save" style="display: none">сохранить</button>
                                            </th>
                                            <th style="text-align: right;border-left: none">
                                                <button class="btn btn-block-sm btn-default toggleForm" data-form_id="paymentForm" style="display: none">отменить</button>
                                                <button class="btn btn-block-sm btn-warning toggleForm" data-form_id="paymentForm"><i class="fa fa-fw fa-pencil"></i>изменить</button>
                                            </th>   
                                        </tr>    
                                    </tfoot>
                                    {/if}
                                </table>
                            
                            </form>
                        </div>

                        <div class="col-sm-4">
                           
                            <form role="form" method="post" id="deliveryForm">
                           
                                <table id="example2" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                                    <tr>
                                       <th colspan="2">Доставка</th>
                                    </tr>
                                    <tr>
                                        <td>Тип доставки</td>
                                        <td>
                                            <span>{$order->user_basket_delivery_type_rus}</span>
                                            <select name="delivery_type" id="">
                                                {foreach from=$deliveryTypes key="k" item="dt"}
                                                <option value="{$k}" {if $k == $order->user_basket_delivery_type}selected="selected"{/if}>{$dt.title}</option>
                                                {/foreach}
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Страна</td>
                                        <td>
                                            <span>{$order->address.country}</span>
                                            <select name="address[country]" id="">
                                                {foreach from=$countries key="k" item="c"}
                                                <option value="{$c.country_id}" {if $c.country_id == $order->address.country}selected="selected"{/if}>{$c.country_name}</option>
                                                {/foreach}
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Город</td>
                                        <td>
                                            <span>{$order->address.city}</span>
                                            <input type="text" name="address[city]" value="{$order->address.city}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Адрес</td>
                                        <td>
                                            <span>{$order->address.address}</span>
                                            <input type="text" name="address[address]" value="{$order->address.address}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Почтовый индекс</td>
                                        <td>
                                            <span>{$order->address.postal_code}</span>
                                            <input type="text" name="address[postal_code]" value="{$order->address.postal_code}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>ФИО</td>
                                        <td>
                                            <span>{$order->address.name}</span>
                                            <input type="text" name="address[name]" value="{$order->address.name}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Телефон</td>
                                        <td>
                                            <span>{$order->address.phone}</span>
                                            <input type="text" name="address[phone]" value="{$order->address.phone}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Стоимость доставки</td>
                                        <td>
                                            <span>{$order->user_basket_delivery_cost} руб.</span>
                                            <input type="text" name="delivery_cost" value="{$order->user_basket_delivery_cost}">
                                        </td>
                                    </tr>
                                    {if $order->user_basket_status != 'delivered' && $order->user_basket_status != 'canceled'}
                                    <tfoot>
                                    <tr>
                                        <th style="border-right: none">
                                           <button class="btn btn-block-sm btn-success" name="save" style="display: none">сохранить</button>
                                        </th>
                                        <th style="text-align: right;border-left: none">
                                            <button class="btn btn-block-sm btn-default toggleForm" data-form_id="deliveryForm" style="display: none">отменить</button>
                                            <button class="btn btn-block-sm btn-warning toggleForm" data-form_id="deliveryForm"><i class="fa fa-fw fa-pencil"></i>изменить</button>
                                        </th>
                                    </tr>    
                                    </tfoot>
                                    {/if}
                                </table>
                            
                            </form>
                            
                        </div>

                        <div class="col-sm-4">
                            
                            <form action="" method="post">
                                
                                <p><b>Операции с заказом</b></p>
                                
                                {if $order->user_basket_status != 'delivered' && $order->user_basket_status != 'prepared' && $order->user_basket_status != 'canceled'}
                                <button type="submit" class="btn btn-block btn-primary" name="ch-status" value="prepared">Заказ подготовлен</button>
                                {/if}
                                
                                {if $order->user_basket_status != 'delivered' && $order->user_basket_status != 'canceled'}
                                <button type="submit" class="btn btn-block btn-success" name="ch-status" value="delivered">Заказ доставлен</button>
                                {/if}
                                
                                {if $order->user_basket_status != 'delivered' && $order->user_basket_status != 'canceled'}
                                <button type="submit" class="btn btn-block btn-danger" name="ch-status" value="canceled" onclick="return confirm('Вы уверены?');">Отменить заказ</button>
                                {/if}
                                
                                {if $order->user_basket_status == 'delivered' || $order->user_basket_status == 'canceled'}
                                <button type="submit" class="btn btn-block btn-success" name="ch-status" value="ordered">Откатить заказ</button>
                                {/if}
                                
                                <br><br>
                                
                                {if $order->user_basket_status != 'canceled' && $order->user_basket_payment_confirm == 'false'}
                                {* <button type="submit" class="btn btn-block btn-info">Оплатить заказ</button> *}
                                <div class="input-group">
                                    <input type="text" class="form-control" name="pay" placeholder="укажите сумму" value="{$order->basketSum - $order->alreadyPayed}">
                                    <div class="input-group-btn">
                                      <button type="submit" class="btn btn-info">Оплатить заказ</button>
                                    </div>
                                </div>
                                {/if}
                                
                                <input type="hidden" name="id" value="{$order->id}">
                                
                            </form>
                            
                        </div>
                    </div>
                
                    <div class="row">
                        <div class="col-sm-12">
                            <form action="" method="post">
                                <table class="table table-bordered table-striped" role="grid" aria-describedby="example1_info">
                                <tr>
                                    <th>#</th>
                                    <th colspan="2">Товар</th>
                                    <th>Количество</th>
                                    <th>Цена</th>
                                    <th>Скидка</th>
                                    <th>Итоговая цена</th>
                                    <th></th>
                                </tr>
                                {foreach from=$order->basketGoods key="k" item="g"}
                                <tr>
                                    <td>{$k}</td>
                                    <td width="100"><a href="/admin/product/update?id={$g.product_id}" target="_blank"><img src="{$g.picture_path}" alt="{$g.product_name}" width="100" /></a></td>
                                    <td><a href="/admin/product/update?id={$g.product_id}" target="_blank">{$g.product_name}</a><br />{$g.product_sku}</td>
                                    <td>
                                        {if $PAGE->reqUrl.2 == 'edit'}
                                            <input type="text" name="pos[{$g.id}][quantity]" size="4" value="{$g.quantity}">
                                        {else}
                                            {$g.quantity}
                                        {/if}
                                    </td>
                                    <td>
                                        {if $PAGE->reqUrl.2 == 'edit'}
                                            <input type="text" name="pos[{$g.id}][price]" size="4" value="{$g.price}">
                                        {else}
                                            {$g.price}
                                        {/if}
                                    </td>
                                    <td>
                                        {if $PAGE->reqUrl.2 == 'edit'}
                                            <input type="text" name="pos[{$g.id}][discount]" size="4"     value="{$g.discount}">
                                        {else}
                                            {$g.discount}
                                        {/if}
                                    </td>
                                    <td>{$g.tprice}</td>
                                    <td style="text-align: right">
                                        {if $order->user_basket_status != 'delivered' && $order->user_basket_status != 'canceled'}
                                        <a href="edit?id={$order->id}" class="btn btn-warning btn-xs" title="Изменить данные"><i class="fa fa-fw fa-pencil"></i></a>
                                        <a href="/admin/orders/view?id={$order->id}&deletepos={$g.id}" class="btn btn-danger btn-xs delete-js" title="Удалить товар"><i class="fa fa-fw fa-times"></i></a>
                                        {/if}
                                    </td>
                                </tr>
                                {/foreach}

                                <tr>
                                    <th style="text-align: right" colspan="7">Подитог</th>
                                    <th>{$order->basketSum + $order->user_basket_payment_partical - $order->user_basket_delivery_cost} руб.</th>
                                </tr>
                                
                                <tr>
                                    <th style="text-align: right" colspan="7">Доставка</th>
                                    <th>{$order->user_basket_delivery_cost} руб.</th>
                                </tr>
                                
                                <tr>
                                    <th style="text-align: right" colspan="7">Оплачено</th>
                                    <th>{$order->alreadyPayed} руб.</th>
                                </tr>
                                
                                {if $order->user_basket_payment_partical > 0}
                                <tr>
                                    <th style="text-align: right" colspan="7">Оплачено купоном</th>
                                    <th>{$order->user_basket_payment_partical} руб.</th>
                                </tr>
                                {/if}
                                
                                <tr>
                                    <th style="text-align: right" colspan="7">Итого к оплате</th>
                                    <th>{$order->basketSum - $order->alreadyPayed} руб.</th>
                                </tr>
                                
                               
                                {if $PAGE->reqUrl.2 == 'edit'}
                                <tfoot>
                                    <tr>
                                        <td colspan="20" style="text-align: right">
                                            <button class="btn btn-block-sm btn-success" name="savepos">сохранить</button>
                                            <a href="view?id={$order->id}"><button class="btn btn-block-sm btn-default margin" data-form_id="deliveryForm" style="">отменить</button></a>
                                            
                                            <input type="hidden" name="editpos" value="1">
                                        </td>
                                    </tr>
                                </tfoot>
                                {/if}
                                </table>
                            </form>
                        </div>
                    </div>
                
                </div>

                <div class="tab-pane" id="log">
                    <table class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                    <tr>
                        <td><b>#</b></td>
                        <td><b>Событие</b></td>
                        <td><b>Результат</b></td>
                        <td><b>Инфо</b></td>
                        <td><b>Дата</b></td>
                    </tr>
                    {foreach $order->flatlog item="l" key="k"}
                    <tr>
                        <td>{$k + 1}</td>
                        <td>{$l.action}</td>
                        <td>{$l.result}</td>
                        <td>{$l.info}</td>
                        <td>{$l.date|datefromdb2textdate}</td>
                    </tr>
                    {/foreach}
                    </table>
                </div>

                
                <div class="tab-pane" id="comments">
                   <div class="row">
                        <div class="col-sm-5">
                        
                            <h4>Добавить комментарий</h4>
                    
                            <form action="" method="post">
                                <textarea name="comment" id="" rows="7" style="width:100%"></textarea>
                                <p>
                                    <select name="to" class="form-control ">
                                        <option value="admin">для админа</option>
                                        <option value="client">для клиента</option>
                                    </select>
                                </p>
                                <p><button type="submit" name="addcomment" class="btn btn-block btn-default btn-sm">Добавить</button></p>
                            </form>
                            
                        </div>
                        
                        <div class="col-sm-7">
                            <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                            {foreach from=$order->comments key="k" item="c"}
                            <tr>
                                <td style="width:130px">
                                    {if $c.action == 'user_comment'}
                                        <b>Клиент</b>
                                    {else}
                                        <b>Администратор</b>
                                    {/if}
                                </td>
                                <td>
                                    <em>{$c.date|datefromdb2textdate}</em><br>
                                    {$c.result}
                                </td>
                                
                            </tr>
                            {/foreach}
                            </table>
                        </div>
                    </div>
                </div>
           
            </div>
       
        </div>
        
        
    </div>
</div>


<style>
    #main .dataTable input, #main .dataTable select {
        display: none;
    }
</style>


<script>

    $('.toggleForm').click(function(){
       
        var fid = $('#' + $(this).data('form_id'));
        
        fid.find('span').toggle();
        fid.find('input, select, button').toggle();
        
        return false;
    });
    
    $('table').on('click', '.delete-js',  function(e){

        if (!confirm('Вы действительно желаете удалить товар из заказа?')) {

            e.preventDefault();
            return false;
        }

        return true;
    })

</script>