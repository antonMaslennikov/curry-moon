<style>#content{ldelim}width:100%{rdelim}</style>
<div id="goods" class="clr">
	{foreach from=$promo item="p" key="k"}
	<div class="cat-item {if $k % 2 != 0} marg-left_15 {/if}">
		
		<a href="/sale/view/{$p.good_id}/" class="img-link"  title="{$p.good_name}" rel="nofollow"><img src="{$p.picture_path}"  width="480" alt={$p.good_name}"" />	</a>
		<div class="item-details">
			<div class="item-name">
				{if $p.place > 0}
				<a class="orden" href="http://www.maryjane.ru/blog/view/1511/" rel="nofollow">&nbsp;</a>
				{/if}
				<a class="name" href="/sale/view/{$p.good_id}/" rel="nofollow">{$p.good_name}</a>
				<a class="creator" href="http://www.maryjane.ru/profile/{$p.user_id}/" rel="nofollow">{$p.user_login}</a>
			</div>
			
			<div class="b-predzakaz">
				<!--noindex--><p class="b-pred-p">Способ печати шелкография. <br/>
				Уже в продаже!
				<!--{$p.date}--></p><!--/noindex-->
				<!--span class="b-pred-count"><i></i>{$p.preorder}</span-->
			</div>
		</div>
		
	</div>
	{/foreach}
</div>