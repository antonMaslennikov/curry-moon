<section id="gkMainbody" style="font-size: 100%;">

<div class="{if !$products}category{else}browse{/if}-view">

    {include file="shop/index.products.tpl"}
   
    {include file="shop/index.subcategorys.tpl"}
    
    {if $parentCategory->description}
    <p class="category_description">
        {$parentCategory->description}
    </p>
    {/if}
    
</div>

</section>