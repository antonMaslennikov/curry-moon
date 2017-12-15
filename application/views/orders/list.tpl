<section id="gkMainbody" style="font-size: 100%;">
    <div id="editcell">
        <table class="adminlist ordersList">
            <thead>
                <tr>
                    <th>Номер заказа</th>
                    <th>Дата заказа</th>
                    <th>Последние изменения</th>
                    <th>Статус заказа</th>
                    <th>Сумма</th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$orders item="o"}
                <tr class="row0">
                    <td align="left">
                        <a href="/ru/orders/{$o.id}" rel="nofollow">{$o.id}</a>
                    </td>
                    <td align="left">{$o.user_basket_date|datefromdb2textdate}</td>
                    <td align="left">{if $o.user_basket_last_change_date != '0000-00-00 00:00:00'}{$o.user_basket_last_change_date|datefromdb2textdate}{else}-{/if}</td>
                    <td align="left">{$o.status}</td>
                    <td align="left">{$o.sum} руб</td>
                </tr>
                {foreachelse}
                <tr>
                    <td colspan="6">Заказов не найдено</td>
                </tr>
                {/foreach}
            </tbody>
        </table>
    </div>
</section>