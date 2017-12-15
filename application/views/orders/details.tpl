<section id="gkMainbody" style="font-size: 100%;">

    <h1 class="orderH1">
        Сведения о заказе
        <a href="/ru/orders/{$order->id}?print=true"><img src="/public/images/printButton.png" alt="Печать"></a>
    </h1>
    
   <div class="spaceStyle">
        <div class="floatright">
            <a href="/ru/orders" rel="nofollow">Перечислить заказы</a>
        </div>
        <div class="clear"></div>
    </div>
    
    <div class="spaceStyle">

        <table width="100%" cellspacing="0" cellpadding="0" border="0">
        <tbody>
        <tr>
            <td class="orders-key">Номер заказа</td>
            <td class="orders-key" align="left">{$order->id}</td>
        </tr>
        <tr>
            <td class="">Дата заказа</td>
            <td align="left">{$order->user_basket_date|datefromdb2textdate}</td>
        </tr>
        <tr>
            <td class="">Статус заказа</td>
            <td align="left">{$order->status_rus}</td>
        </tr>
        <tr>
            <td class="">Последнее обновление</td>
            <td align="left">{if $order->user_basket_last_change_date != '0000-00-00 00:00:00'}{$order->user_basket_last_change_date|datefromdb2textdate}{else}-{/if}</td>
        </tr>
        <tr>
            <td class="">Отгрузки</td>
            <td align="left"><span class="vmCartShipmentLogo"></span>  <span class="vmshipment_name">{$order->user_basket_delivery_type_rus}</span></td>
        </tr>
        <tr>
            <td class="">Способ оплаты</td>
            <td align="left"><span class="vmCartPaymentLogo"><img align="middle" src="{$order->user_basket_payment_type_ico}" alt="cash_rub"></span>  <span class="vmpayment_name">{$order->user_basket_payment_type_rus}</span><br>	</td>
        </tr>
        <tr>
            <td>Заметка покупателя</td>
            <td valign="top" align="left" width="50%">
                {foreach from=$order->logs.user_comment item="c"}
                <p>{$c.result}</p>
                {/foreach}
            </td>
        </tr>
        <tr>
            <td class="orders-key" align="left">{$order->basketSum} руб</td>
        </tr>
        <tr>
            <td colspan="2"> &nbsp;</td>
        </tr>
        <tr>
            <td valign="top"><strong>
                Оплата</strong> <br>
                <table border="0">
                <tbody>
                    <tr><td class="key">Эл.почта</td><td>{$order->user->user_email}</td></tr>
                    <tr><td class="key">Фамилия / Имя</td><td>{$order->address.name}</td></tr>
                    <tr><td class="key">Адрес</td><td>{$order->address.address}</td></tr>
                    <tr><td class="key">Почтовый индекс</td><td>{$order->address.postal_code}</td></tr>
                    <tr><td class="key">Город</td><td>{$order->address.city}</td></tr>
                    <tr><td class="key">Страна</td><td>{$order->address.country}</td></tr>
                </tbody>
                </table>
            </td>
            <td valign="top"><strong>
                Доставить в</strong><br>
                <table border="0">
                <tbody>
                    <tr><td class="key">Фамилия / Имя</td><td>{$order->address.name}</td></tr>
                    <tr><td class="key">Адрес</td><td>{$order->address.name}</td></tr>
                    <tr><td class="key">Почтовый индекс</td><td>a</td></tr>
                    <tr><td class="key">Город</td><td>{$order->address.city}</td></tr>
                    <tr><td class="key">Страна</td><td>{$order->address.country}</td></tr>
                </tbody>
                </table>
            </td>
        </tr>
        </tbody>
        </table>
        
    </div>

    <div class="spaceStyle">
        <div id="ui-tabs">
            <ul id="tabs"><li class="current">Позиции заказа</li></ul>
            <div id="tab-1" class="tabs dyn-tabs" title="Позиции заказа" style="display: block;">
                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                <tbody>
                <tr align="left" class="sectiontableheader">
                    <th align="left" width="5%">Артикул</th>
                    <th align="left" colspan="2" width="49%">Название товара</th>
                    <th align="center" width="10%">Статус товара</th>
                    <th align="right" width="10%">Цена</th>
                    <th align="left" width="5%">Кол-во</th>
                    <th align="right" width="11%">Скидка</th>
                    <th align="right" width="10%">Всего</th>
                </tr>
                {assign var="pp" value="0"}
                {assign var="ds" value="0"}
                {foreach from=$order->basketGoods item="g"}
                <tr valign="top">
                    <td align="left">{$g.product_sku}</td>
                    <td align="left" colspan="2"><a href="/ru/shop/openproduct/{$g.product_id}">{$g.product_name}</a></td>
                    <td align="center">{$order->status_rus}</td>
                    <td align="right" class="priceCol"><span>{$g.price} руб</span><br></td>
                    <td align="right">{$g.quantity}</td>
                    <td align="right" class="priceCol">{$g.discount_sum} руб</td>
                    <td align="right" class="priceCol">{$g.user_basket_good_total_price} руб</td>
                </tr>
                {$pp = $pp + $g.user_basket_good_total_price}
                {$ds = $ds + $g.discount_sum}
                {/foreach}
                <tr class="sectiontableentry1">
                    <td colspan="6" align="right">Итого</td>
                    <td align="right"><span class="priceColor2">{$ds} руб</span></td>
                    <td align="right">{$pp} руб</td>
                </tr>
                <tr>
                    <td align="right" class="pricePad" colspan="6">Стоимость обработки и доставки</td>
                    <td align="right">&nbsp;</td>
                    <td align="right">{$order->user_basket_delivery_cost} руб</td>
                </tr>
                {if $order->user_basket_payment_partical > 0}
                <tr>
                    <td align="right" class="pricePad" colspan="6">Частично оплачено купоном</td>
                    <td align="right">&nbsp;</td>
                    <td align="right">{$order->user_basket_payment_partical} руб</td>
                </tr>
                {/if}
                <tr>
                    <td align="right" class="pricePad" colspan="6">Комиссия</td>
                    <td align="right">&nbsp;</td>
                    <td align="right">0 руб</td>
                </tr>
                <tr>
                    <td align="right" class="pricePad" colspan="6"><strong>Всего</strong></td>
                    <td align="right"><span class="priceColor2">{$ds} руб</span></td>
                    <td align="right"><strong>{$order->basketSum} руб</strong></td>
                </tr>
                </tbody>
                </table>
                <div class="clear"></div>
            </div>
            <div id="tab-2" class="tabs dyn-tabs" title="История заказов">
                <table width="100%" cellspacing="2" cellpadding="4" border="0">
                <tbody><tr align="left" class="sectiontableheader">
                <th align="left">Дата</th>
                <th align="left">Статус заказа</th>
                <th align="left">Комментарий</th>
                </tr>
                <tr valign="top">
                <td align="left">
                13.12.2017 21:34			</td>
                <td align="left">
                В ожидании			</td>
                <td align="left">
                </td>
                </tr>
                </tbody></table>
                <div class="clear"></div>
            </div>
        </div>	 
    </div>
    
    <br clear="all"><br>


</section>