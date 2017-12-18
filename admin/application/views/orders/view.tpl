<div class="row">
    <div class="col-sm-12">

        <div class="nav-tabs-custom ">

            <ul class="nav nav-tabs">
                <li class="active"><a href="#main" data-toggle="tab">Основные данные</a></li>
                <li><a href="#log" data-toggle="tab">История заказа</a></li>
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
                        </div>

                        <div class="col-sm-4">
                           
                            <form role="form" method="post" id="deliveryForm">
                           
                                <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                                    <tr>
                                       <th colspan="2">Доставка</th>
                                    </tr>
                                    <tr>
                                        <td>Тип доставки</td>
                                        <td>
                                            <span>{$order->user_basket_delivery_type_rus}</span>
                                            <select name="" id="">
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
                                            <select name="" id=""></select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Город</td>
                                        <td>
                                            <span>{$order->address.city}</span>
                                            <input type="text" name="address[]" value="city">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Адрес</td>
                                        <td>
                                            <span>{$order->address.address}</span>
                                            <input type="text" name="address[]" value="{$order->address.address}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Почтовый индекс</td>
                                        <td>
                                            <span>{$order->address.postal_code}</span>
                                            <input type="text" name="address[]" value={$order->address.postal_code}"">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>ФИО</td>
                                        <td>
                                            <span>{$order->address.name}</span>
                                            <input type="text" name="address[]" value="{$order->address.name}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Телефон</td>
                                        <td>
                                            <span>{$order->address.phone}</span>
                                            <input type="text" name="address[]" value="{$order->address.phone}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Стоимость доставки</td>
                                        <td>
                                            <span>{$order->user_basket_delivery_cost} руб.</span>
                                            <input type="text" name="address[]" value="{$order->user_basket_delivery_cost}">
                                        </td>
                                    </tr>
                                    <tfoot>
                                    <tr>
                                        <th style="border-right: none">
                                           <button class="btn btn-block-sm btn-success" style="display: none">сохранить</button>
                                        </th>
                                        <th style="text-align: right;border-left: none">
                                            <button class="btn btn-block-sm btn-default toggleForm" data-form_id="deliveryForm" style="display: none">отменить</button>
                                            <button class="btn btn-block-sm btn-warning toggleForm" data-form_id="deliveryForm"><i class="fa fa-fw fa-pencil"></i>изменить</button>
                                        </th>
                                    </tr>    
                                    </tfoot>
                                </table>
                            
                            </form>
                            
                        </div>

                        <div class="col-sm-4">
                            
                            <form role="form" method="post" id="paymentForm">   

                                <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                                    <tr>
                                       <th colspan="2">Оплата</th>
                                    </tr>
                                    <tr>
                                        <td>Тип оплаты</td>
                                        <td>
                                            <span>{$order->user_basket_payment_type_rus}</span>
                                            <select name="" id="">
                                                {foreach from=$paymentTypes key="k" item="pt"}
                                                <option value="{$k}" {if $k == $order->user_basket_payment_type}selected="selected"{/if}>{$pt.title}</option>
                                                {/foreach}
                                            </select>
                                        </td>
                                    </tr>
                                    <tfoot>
                                        <tr>
                                            <tr>
                                                <th style="border-right: none">
                                                    <button class="btn btn-block-sm btn-success" style="display: none">сохранить</button>
                                                </th>
                                                <th style="text-align: right;border-left: none">
                                                    <button class="btn btn-block-sm btn-default toggleForm" data-form_id="paymentForm" style="display: none">отменить</button>
                                                    <button class="btn btn-block-sm btn-warning toggleForm" data-form_id="paymentForm"><i class="fa fa-fw fa-pencil"></i>изменить</button>
                                                </th>   
                                        </tr>    
                                    </tfoot>
                                </table>
                            
                            </form>
                            
                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="log">
                    <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                    <tr>
                        <td><b>#</b></td>
                        <td><b>Событие</b></td>
                        <td><b>Результат</b></td>
                        <td></td>
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

            </div>

            <div class="box-footer">

            </div>

        </div>
        
    </div>
</div>


<style>
    #main input,#main select {
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

</script>