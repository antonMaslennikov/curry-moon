<div class="row">
    <div class="col-sm-6">
        {include file="adminlte/form/input.tpl" attr="quantity"}
    </div>
    <div class="col-sm-6">
        {if $product->quantity_reserved > 0}
        <a href="" style="position: relative;top: 30px;">В резерве: {$product->quantity_reserved} шт.</a>
        {/if}
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        {include file="adminlte/form/input.tpl" attr="product_price"}
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        {include file="adminlte/form/input.tpl" attr="product_discount"}
    </div>
</div>