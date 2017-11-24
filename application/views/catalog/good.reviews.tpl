{if $page == 1 || $module=="contact_us"}
<div id="mneniya-head">
	
	{if $module=="contact_us"}
	<table width="" class="tableL" border="0" cellpadding="0" cellspacing="0">
			<tbody>
				<tr>
					<td align="center" width="106">			
						<img src="/images/mt/lera.jpg" width="106" height="106" alt="Валерия Матросова" title="Валерия Матросова" style="border:0px;margin:0px;padding:0px;">
					</td>
					<td width="25">&nbsp;</td>
					<td align="left" valign="top" style="padding-top:7px;font-size:16px;line-height: 19px;">	
						С уважением,<br>Валерия Матросова<br>Руководитель проекта 
					</td>
				</tr>
			</tbody>
		</table>
	{/if}
	
	<div class="mp-head-left mp-floatl">
		<span class="mp-title">Средний рейтинг:</span>
		<div class="mp-total-score-wraper">
			<div class="mp-total-score">
				<div style="width:{$avg_stars * 100 / 5}%"></div>
			</div>
			<div class="mp-floatl mp-total-score-text">
				<span class="mp-total-score-val">"<span>{$avg_stars}</span>"</span>
				на основании <span><span>{$total}</span> отзывов</span>
			</div>
			<div class="clear"></div>
		</div>
		<div class="mp-calltoaction">
			{if $module!="contact_us"}
				<span>Успели купить?</span>
				<a class="addformlink big-link" rel="nofollow" href="/contact_us/" title="Оставьте отзыв">Оставьте&nbsp;отзыв</a>
			{/if}
		</div>
	</div>
	<div class="mp-head-right mp-floatl">
		<span class="mp-title">Распределение рейтингов:</span>
		<table class="mp-rating-details">
			<tbody>
				{foreach from=$stars item="s" key="k"}
					{if $k!=0}	
						<tr>
							<td class="mp-range">
								<div class="mp-rating-value">
									<div style="width:{$k * 20}%" ></div>
								</div>
							</td>
							<td class="mp-bar">
								<div title="{if $s > 0}{$s * 100 / $total}{else}0{/if}%" class="mp-strip{$k}" style="width:{if $s > 0}{$s * 100 / $total}{else}0{/if}%"></div>
							</td>
							<td class="mp-count">( {$s} )</td>
						</tr>
					{/if}
				{/foreach}	
			</tbody>
		</table>
	</div>
</div>
{/if}
	
{if $page == 1 || $module=="contact_us"}
<div id="mneniyapro_feeddata">
	
	<div class="mp-head-left">
		<span class="mp-reviews-title">Отзывы о товаре:</span>
	</div>
	
	<!--noindex-->
	<ul class="mp-rating-items">
{/if}

	{foreach from=$reviews item="r"}
	<li class="mp-rating-item">
		<div class="mp-wrap-left">
			{if $r.avatar}{$r.avatar}{else}<img width="50" height="50" src="/images/_tmp/default-avatar.png">{/if}
		{if $USER->meta->mjteam}	
			<div class="mp-editable-rating clearfix" style="border:1px dashed orange">
				<ul data-id="{$r.id}">
					<li><a href="#" class="star1 {if $r.rating==1}on{/if}" data-rat='1' title="Ужасно"></a></li>
					<li><a href="#" class="star2 {if $r.rating==2}on{/if}" data-rat='2' title="Плохо"></a></li>
					<li><a href="#" class="star3 {if $r.rating==3}on{/if}" data-rat='3' title="Нормально"></a></li>
					<li><a href="#" class="star4 {if $r.rating==4}on{/if}" data-rat='4' title="Хорошо"></a></li>
					<li><a href="#" class="star5 {if $r.rating==5}on{/if}" data-rat='5' title="Отлично"></a></li>
				</ul>
			</div>
		{else}
			<div class="mp-rating-value">
				<div style="width:{$r.rating * 20}%"></div>
			</div>
		{/if}	
			<img width="90" height="22" src="/images/_tmp/pokupatel.png" class="mp-rating-prooved-img">
			<div class="mp-rating-head">
				<div class="mp-rating-author-name">{if $r.name}{if $r.user_id > 0&& $r.user_activation == "done"}<a href="/profile/{$r.user_id}/" rel="nofollow">{$r.name}</a>{else}{$r.name}{/if}{/if} {if $r.city}<font class="city">({$r.city})</font>{/if}</div>
				<div class="mp-rating-date">{$r.date}</div>
			</div>
		</div>
		<div class="mp-wrap-right">
			<div class="mp-rating-notes">
				{*<div class="mp-rating-text">Тема - Клавиатура понравилась всем, советую</div>*}
				<div class="mp-rating-pros">
					<div class="mp-plus">
					{if $r.pic1}
						<img width="56" class="left" src="{$r.pic1}">
					{/if}
					{if $r.pic2}
						<img width="56" class="left" src="{$r.pic2}">
					{/if}
					{if $r.pic3}
						<img width="56" class="left" src="{$r.pic3}">
					{/if}
					{$r.text}</div>
				</div>
				{*<div class="mp-rating-cons">
					<div class="mp-minus">минусы -Локализация могла бы быть и получше. Периодически "отваливается", приходится пере-подключать.</div>
				</div>*}
			</div>
			{*<div class="mp-i-recommend">Я рекомендую этот товар другим!</div>*}
		</div>
	</li>
	{/foreach}	

	{if $rest>0 && $count >= $onpage}
	<a class="show-more-reviews" href="/ajax/getTab/?tab=reviews&page={$page+1}" title="Показать еще" rel="nofollow">Показать еще</a>
	{/if}
		
{if $page == 1 || $module=="contact_us"}
	</ul>
	<!--/noindex-->
</div>
{/if}