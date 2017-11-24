{if $goods >0 && $link}
	<div class="pagesGreen clearfix" {if !$page || ($page <2 &&  $rest <= 0)}style="display:none"{/if}>
		{if $page}						
			{if $page > 1}
				<a href="{$link}/{if $SEARCH}?q={$SEARCH}{/if}" rel="nofollow" class="start">В начало</a>
				<a href="{$link}/{if $page > 2}page/{$page-1}/{/if}{if $SEARCH}?q={$SEARCH}{/if}" rel="nofollow" class="prev">←</a>
			{/if}
			{if $page-2 > 0}
				<a href="{$link}/{if $page > 3}page/{$page-2}/{/if}{if $SEARCH}?q={$SEARCH}{/if}" rel="nofollow" class="page">{$page-2}</a>
			{/if}
			{if $page-1 > 0}
				<a href="{$link}/{if $page > 2}page/{$page-1}/{/if}{if $SEARCH}?q={$SEARCH}{/if}" rel="nofollow" class="page">{$page-1}</a>
			{/if}						
			<a href="{$link}/{if $page > 1}page/{$page}/{/if}{if $SEARCH}?q={$SEARCH}{/if}" rel="nofollow" class="page active">{$page}</a>
			{if $rest > 0}
				<a href="{$link}/page/{$page+1}/{if $SEARCH}?q={$SEARCH}{/if}" rel="nofollow" class="page">{$page+1}</a>
				<a href="{$link}/page/{$page+1}/{if $SEARCH}?q={$SEARCH}{/if}" rel="nofollow" class="next">→</a>
			{/if}
		{/if}
	</div>

	{literal}<style>
	#show-more-link.withFont {background:none!important;border:1px solid #00A851;width:751px;max-width:100%;margin-left:5px;display:block;color:#656565!important;}
	.absolut #show-more-link.withFont {margin:0;width: 768px;}
	a.archived-catalog{width: 100%;background:none!important;border:1px solid #00A851;color:#656565!important;}
	</style>{/literal}
	
	{*для такого /tag/enduro/*}
	{if !$page || ($page <2 &&  $rest <= 0)}{literal}<style>.b-catalog_v2 .list_wrap{ margin-bottom: 50px }</style>{/literal}{/if}
{/if}