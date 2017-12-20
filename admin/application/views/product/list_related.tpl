<table class="table">
    <thead>
        <tr>
            <th></th>
            <th>Наименование</th>
            <th>Цена</th>
            <th>Кол-во</th>
            <th>Статус</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        {foreach from=$listRelated item="p"}
        <tr>
            <td><img src="{$p.src}" width="50px"/></td>
            <td><a href="/admin/product/update?id={$p.id}" target="_blank">{$p.product_name}</a></td>
            <td>{$p.product_price}</td>
            <td>{$p.quantity}</td>
            <td>
                {if $p.status}
                    <span class="label label-success">Активена</span>
                {else}
                    <span class="label label-danger">Не активена</span>
                {/if}
            </td>
            <td><a href="javascript:void(0)" data-rel="{$p.id}" class="remove-related">Удалить</a></td>
        </tr>
        {/foreach}
    </tbody>
</table>