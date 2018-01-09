{if $options}
<div class="catalog-sidebar">
    
    <form action="">
        {foreach from=$options item="option"}

        <div class="option">
            <span>{$option.name}</span>

            <ul class="option-values">
                {foreach from=$option.value item="v"}
                <li><input type="checkbox" name="{$option.slug}[]" value="{$v.slug}" {if in_array({$v.slug}, $smarty.get.{$option.slug})}checked="checked"{/if} /> {$v.value}</li>
                {/foreach}
            </ul>
        </div>

        {/foreach}

        <input type="submit" class="addtocart-button" value="Показать" title="Показать">
    </form>
    
</div>
{/if}