<table class="table">
    <thead>
        <tr>
            <th></th>
            <th>Наименование</th>
            <th>Цена</th>
            <th>Кол-во</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        {foreach from=$listRelated item="p"}
        <tr>
            <td><img src="{$p.src}" width="50px"/></td>
            <td>{$p.product_name}</td>
            <td>{$p.product_price}</td>
            <td>{$p.quantity}</td>
            <td><a href="javascript:void(0)" data-rel="{$p.id}" class="remove-related">Удалить</a></td>
        </tr>
        {/foreach}
    </tbody>
</table>