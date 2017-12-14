<table class="table">
    <thead>
        <tr>
            <th></th>
            <th></th>
            <th>Наименование</th>
            <th>Цена</th>
            <th>Кол-во</th>
        </tr>
    </thead>
    <tbody>
        {foreach from=$list item="p"}
        <tr>
            <td><label><input type="checkbox" class="check-related" data-id="{$p.id}" {if $p.related}checked="checked" {/if}> Выбрать</label></td>
            <td><img src="{$p.src}" width="50px"/></td>
            <td>{$p.product_name}</td>
            <td>{$p.product_price}</td>
            <td>{$p.quantity}</td>
        </tr>
        {/foreach}
    </tbody>
</table>