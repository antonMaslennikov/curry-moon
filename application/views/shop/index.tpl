<section id="gkMainbody" style="font-size: 100%;">

<div class="{if !$products && !$filters}category{else}browse{/if}-view {if $products|count > 0 || $smarty.get|count > 0}shop-with-sidebar{/if}">

    {include file="shop/index.products.tpl"}
   
    {include file="shop/index.subcategorys.tpl"}
    
    {if $parentCategory->description}
    <p class="category_description">
        {$parentCategory->description}
    </p>
    {/if}
    
</div>

</section>