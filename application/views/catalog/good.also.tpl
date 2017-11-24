<div class="maiki-a also">
	{if $good.good_status != 'deny' && ($good.good_visible == "modify" || ($good.good_status == "new" && $good.good_visible == "true")) || $picsInTurn}
	<div class="redZebra">
		<div class="abut">
			{if $good.good_visible == "modify"}
				Вы перезагрузили исходник для этой работы. <a href="/faq/group/25/view/189/">Почему я вижу старые картинки</a>&nbsp;&nbsp;<span class="help"><a class="help red thickbox" rel="nofollow" href="/faq/189/?height=500&width=600">?</a></span>
			{elseif $good.good_status == "new" && $good.good_visible == "true"}
				Работа находится на Худсовете <span class="help"><a class="help red thickbox" rel="nofollow" href="/faq/189/?height=500&width=600">?</a></span>
			{elseif $picsInTurn}
				Изображения для Вашей работы будут созданы в ближайшее время
			{/if}
		</div>
	</div>
	{/if}
		
	{if $USER->id == $good.user_id && $good.good_visible == 'modify'}
	<div class="redZebra">
		<div class="abut">Вы перезагрузили исходник для этой работы. <a href="/faq/group/25/view/189/" style="color:red">Почему я вижу старые картинки</a>&nbsp;&nbsp;<span class="help"><a class="help red thickbox" rel="nofollow" href="/faq/189/?height=500&width=600">?</a></span>
		</div>
	</div>
	{/if}
	{if $MobilePageVersion}<!--noindex--><div class="zagolovok"><br replaces="{$L.GOOD_also}:"/></div><!--/noindex-->{/if}
	<ul>
		{foreach from=$also item="a"}
			<li class="{$a.sex} {$a.category}">
				<a rel="nofollow" class="img" title="" href="{$a.link}" style="text-align:center">
					<img title="{$good.good_name}, {$a.style_name}" alt="{$good.good_name}, {$a.style_name}" src="{$a.path}">
				</a>
				{* <div class="info">
					<a rel="nofollow" class="link" title="{$a.style_name}" href="/catalog/category,{$a.category};color,{$a.color};size,{$a.size}{if $a.sex == 'female'}/female{/if}/">{$a.style_name}</a>
					<span class="price">&nbsp;&mdash;&nbsp;{if $a.price == 0}от 120{else}{$a.price}{/if} р.</span>
				</div>	*}	
			</li>
		{/foreach}
	</ul>
</div>