{if $childrenCategorys}
<div class="row">

    {foreach from=$childrenCategorys item="cat"}
    <div class="category floatleft width33 vertical-separator">
        <div class="spacer">
            <a href="{$base}{$cat.slug}" title="{$cat.title}"><img src="{$cat.picture_id|pictureId2path}" alt="silver-rings7"></a>
            <h2 class="catSub"><a href="{$base}{$cat.slug}" title="{$cat.title}">{$cat.title}</a></h2>
        </div>
    </div>
    {/foreach}

    <div class="clear"></div>

</div>
{/if}