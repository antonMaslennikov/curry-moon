{foreach from=$COMMENTS item="c"}
<a class="alpha2 o-sideb-comm" href="{$c.TO_LINK}{if $c.post_slug}{$c.post_slug}{else}{$c.TO}{/if}/#comment{$c.ID}" rel="nofollow">
	<div class="comm-pic"><!--noindex-->{$c.AVATAR}<!--/noindex--></div>
	<div class="whp">        
        <span style="text-decoration:none; color:#999999" href="{$c.TO_LINK}{$c.TO}/#comment{$c.ID}">{$c.TEXT}</span><font style="">{$c.DATE}</font>
    </div>
</a>
{/foreach}