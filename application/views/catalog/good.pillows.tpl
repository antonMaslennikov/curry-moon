<div class="good-tpl-pillows">
    <ul>
		<li>Ткань габардин 125 г/кв.м 100% полиэстер</li>
		<li>Долговечное износостойкое и водооталкивающее полотно</li>
		<li>Внутри подушка с наполнителем холлофайбер</li>
		<li>Наволочка закрыта молнией</li>
		<li>Стирка при 40%, глажка с изнанки, не линяет</li>
	</ul>
</div>

<br />

<div class="cat-pillows">
	<a href="/catalog/{$good.user_login}/{$good.good_id}/pillow/" class="pillow-square {if $Style->style_slug == "pillow"}active{/if}">&nbsp;<span>35 x 35</span></a>
	<a href="/catalog/{$good.user_login}/{$good.good_id}/pillow-star/" class="pillow-star {if $Style->style_slug == "pillow-star"}active{/if}">&nbsp;<span>35 x 35</span></a>
	<a href="/catalog/{$good.user_login}/{$good.good_id}/pillow-round/" class="pillow-round {if $Style->style_slug == "pillow-round"}active{/if}">&nbsp;<span>35 x 35</span></a>
	<a href="/catalog/{$good.user_login}/{$good.good_id}/pillow-rectangle/" class="pillow-rect {if $Style->style_slug == "pillow-rectangle"}active{/if}">&nbsp;<span>35 x 47</span></a>
</div>

{*
<!-- Выбор размера -->	
<!--noindex-->		
<div class="select-size-box">
	<div class="error-order" id="size-error">{if $modal}{$L.GOOD_choose_size}{else}<br replaces="{$L.GOOD_choose_size}"/>{/if}</div>
	<input id="good_sizes" type="hidden" name="size" value="">	
</div>
<!--/noindex-->
*}