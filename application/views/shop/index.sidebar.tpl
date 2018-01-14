{* if $options *}
<div class="catalog-sidebar">
    
    <form action="">
        <div class="option price">
            <span>Цена</span>
            <ul class="option-values">
                <li><label><input type="radio" name="price" value="asc" {if $smarty.get.price == "asc"}checked="checked"{/if} /> по возрастанию</label></li>
                <li><label><input type="radio" name="price" value="desc" {if $smarty.get.price == "desc"}checked="checked"{/if} /> по убыванию</label></li>
            </ul>
        </div>
        {foreach from=$options item="option"}

        <div class="option">
            <span>{$option.name}</span>

            <ul class="option-values">
                {foreach from=$option.value item="v"}
                <li><label><input type="checkbox" name="{$option.slug}[]" value="{$v.slug}" {if in_array({$v.slug}, $smarty.get.{$option.slug})}checked="checked"{/if} /> {$v.value}</label></li>
                {/foreach}
            </ul>
        </div>

        {/foreach}

        <input type="submit" class="addtocart-button" value="Показать" title="Показать">
    </form>
    
</div>
{* /if *}