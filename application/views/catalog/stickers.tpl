<div class="b-catalog_v2 moveSidebarToLeft list_main_stikers">
	<div class="pageTitle table">
		<h1>{if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}</h1>
	</div>
	
	{if !$MobilePageVersion}
		<!-- Фильтр в левом сайдбаре -->
		{include file="catalog/list.sidebar.tpl"}
	{/if}

	<!-- список работ -->
	<div class="catalog_goods_list">
		<div class="list_wrap">
			<ul>
							
				{if $goods.333}
				<li class="m12 phones">
					<a rel="nofollow" class="item" title="{$goods.333.good_name} - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="/catalog/phones/new/">
						<span class="list-img-wrap">
							<img alt="{$goods.333.good_name} - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" src="{$goods.333.picture_path}" />
						</span>
						<span class="bg"></span>
					</a>
					<div class="item">
						<a rel="nofollow"  class="title" title="Наклейка на сматрфон - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="/catalog/phones/iphone-4/new/">Наклейка на смартфон</a>
						<!--noindex--><span class="price">от&nbsp;490&nbsp;руб.</span>
						<div class="garantia">Гарантия 1 год</div>
						<!--/noindex-->
					</div>		
				</li>
				{/if}				
				
				<li class="m12 phones2">
					<a rel="nofollow" class="item" title="{$goods.631.good_name} - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="/catalog/phones/iphone-5-resin/new/">
						<span class="list-img-wrap">
							<img alt="{$goods.631.good_name} - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" src="{$goods.631.picture_path}" />
						</span>
						<span class="bg"></span>
					</a>
					<div class="item">
						<a rel="nofollow"  class="title" title="Объемные наклейки на сматрфон - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="/catalog/phones/iphone-5-resin/new/">Объемные наклейки  на смартфон</a>
						<!--noindex--><span class="price">от&nbsp;590&nbsp;руб.</span>
						<div class="garantia">Гарантия 1 год</div>
						<!--/noindex-->
					</div>		
				</li>
				
				
				{if $goods.270}
				<li class="m12 laptops">
					<a rel="nofollow" class="item" title="{$goods.270.good_name} - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="/catalog/laptops/new/">
						<span class="list-img-wrap">
							<img  alt="{$goods.270.good_name}  - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" src="{$goods.270.picture_path}" />
						</span>
						<span class="bg"></span>
					</a>
					<div class="item">
						<a rel="nofollow"  class="title" title="Наклейка на ноутбук - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="/stickerset/">Наклейка на ноутбук</a>
						<!--noindex--><span class="price">от&nbsp;690&nbsp;руб.</span>
						<div class="garantia">Гарантия 1 год</div><!--/noindex-->
					</div>		
				</li>
				{/if}
				
				{if $goods.511}
				<li class="m12 touchpads">
					<a rel="nofollow" class="item" title="{$goods.511.good_name}  - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="/catalog/touchpads/ipad-air/new/">
						<span class="list-img-wrap">
							<img  alt="{$goods.511.good_name}  - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if} " src="{$goods.511.picture_path}" />
						</span>
						<span class="bg"></span>
					</a>
					<div class="item">
						<a rel="nofollow"  class="title" title="Наклейка на планщет - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="/catalog/touchpads/ipad-air/new/">Наклейка на планшет</a>
						<!--noindex--><span class="price">от&nbsp;590&nbsp;руб.</span>
						<div class="garantia">Гарантия 1 год</div><!--/noindex-->
					</div>		
				</li>
				{/if}
				
				<li class="m12 sticker">
					<a rel="nofollow" class="item" title="{$auto.as_sticker.good_name}  - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="/catalog/auto/auto-simple/">
						<span class="list-img-wrap">
							<img title="{$auto.as_sticker.good_name} - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" alt="{$auto.as_sticker.good_name} - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if} " src="{$auto.as_sticker.picture_path}" />
						</span>
						<span class="bg"></span>
					</a>	
					<div class="item">
						<a rel="nofollow"  class="title" title=" - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="/catalog/stickers/">Стикер</a>
						<!--noindex--><span class="price">от&nbsp;60&nbsp;руб.</span><!--/noindex-->
					</div>		
				</li>
				
				<li class="m12">
					<a rel="nofollow" class="item" title="{$auto.as_oncar_0.good_name}  - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="/catalog/auto/new/">
						<span class="list-img-wrap">
							<img  alt="{$auto.as_oncar_0.good_name}  - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if} " src="{$auto.as_oncar_0.picture_path}" />
						</span>
					</a>
					<div class="item">
						<a rel="nofollow"  class="title" title=" - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="/catalog/auto/new/">Наклейка на авто с защитой</a>
						<!--noindex--><span class="price">от&nbsp;120&nbsp;руб.</span>
						<div class="garantia">
							гарантия 5 лет
						</div>
						<!--/noindex-->
					</div>		
				</li>
				
				<li class="m12 sticker">
					<a rel="nofollow" class="item" title="{$stickerset.good_name}  - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="/stickerset/">
						<span class="list-img-wrap">
							<img alt="{$stickerset.good_name}  - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" src="{$stickerset.picture_path}" />
						</span>
						<span class="bg"></span>
					</a>
					<div class="item">
						<a rel="nofollow"  class="title" title="Стикерсет - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="/stickerset/">Стикерсет из 11 наклеек</a>
						<!--noindex--><span class="price">130&nbsp;руб.</span><!--/noindex-->
					</div>		
				</li>
			
				{if $goods.593}
				<li class="m12 doski">
					<a rel="nofollow" class="item" href="/catalog/boards/snowboard/">
						<span class="list-img-wrap">
							<img title="Наклейка на сноуборд" alt="Наклейка на сноуборд" src="{$goods.593.picture_path}" />
						</span>
					</a>
					<div class="item">
						<a rel="nofollow"  class="title" title="Наклейка на сноуборд - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="/catalog/boards/snowboard/">Наклейка на сноуборд</a>
						<!--noindex--><span class="price">от&nbsp;2945&nbsp;руб.</span>
						<div class="garantia">
							Гарантия 5 лет
						</div>
						<!--/noindex-->
					</div>		
				</li>
				{/if}
				
				{if $goods.592}
				<li class="m12 doski">
					<a rel="nofollow" class="item" href="/catalog/boards/">
						<span class="list-img-wrap">
							<img title="Наклейка на скейтборд" alt="Наклейка на скейтборд" src="{$goods.592.picture_path}" />
						</span>
					</a>
					<div class="item">
						<a rel="nofollow"  class="title" title="Наклейка на скейтборд - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="/catalog/boards/">Наклейка на скейтборд</a>
						<!--noindex--><span class="price">от&nbsp;920&nbsp;руб.</span>
						<div class="garantia">
							Гарантия 5 лет
						</div>
						<!--/noindex-->
					</div>		
				</li>
				{/if}
				
				{if $goods.611}
				<li class="m12 doski">
					<a rel="nofollow" class="item" href="/catalog/boards/ski/">
						<span class="list-img-wrap">
							<img title="Наклейка на лыжи" alt="Наклейка на лыжи" src="{$goods.611.picture_path}" />
						</span>
					</a>
					<div class="item">
						<a rel="nofollow"  class="title" title="Наклейка на лыжи - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="/catalog/boards/ski/">Наклейка на лыжи</a>
						<!--noindex--><span class="price">от&nbsp;2945&nbsp;руб.</span>
						<div class="garantia">
							Гарантия 5 лет
						</div>
						<!--/noindex-->
					</div>		
				</li>
				{/if}
		
				<li class="m12">
					<a rel="nofollow" class="item" href="/catalog/moto/disk/">
						<span class="list-img-wrap">
							<img title="Наклейка на обод колеса - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" alt="Наклейка на обод колеса - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if} " src="/images/_tmp/stickers/4.jpg" />
						</span>
					</a>
					<div class="item">
						<a rel="nofollow"  class="title" title="Наклейка на обод колеса - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="/catalog/moto/disk/">Наклейка на обод колеса</a>
						<!--noindex--><span class="price">790&nbsp;руб.</span>
						<!--/noindex-->
					</div>		
				</li>
				
				<li class="m12">
					<a rel="nofollow" class="item" href="/catalog/moto/">
						<span class="list-img-wrap">
							<img title="Объемные наклейки на бак мотоцикла- {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" alt="Объемные наклейки на бак мотоцикла - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if} " src="/images/_tmp/stickers/5.jpg" />
						</span>
					</a>
					<div class="item">
						<a rel="nofollow"  class="title" title="Объемные наклейки на бак мотоцикла - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="/catalog/moto/">Объемные наклейки на бак мотоцикла</a>
						<!--noindex--><span class="price">750&nbsp;руб.</span>
						<div class="garantia">
							Технология заливки объемной смолой<br/>
							гарантия 1 год
						</div>
						<!--/noindex-->
					</div>		
				</li>
				
				<li class="m12">
					<a rel="nofollow" class="item" href="/catalog/enduro/">
						<span class="list-img-wrap">
							<img title="Наклейка на эндуро мотоциклы - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" alt="Наклейка на эндуро мотоциклы - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if} " src="/images/_tmp/stickers/6.jpg" />
						</span>
					</a>
					<div class="item">
						<a rel="nofollow"  class="title" title="Наклейка на эндуро мотоциклы - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="/catalog/enduro/">Наклейка на эндуро мотоциклы</a>
						<!--noindex--><span class="price">9999&nbsp;руб.</span>
						<div class="garantia">
							включено: дизайн и оклейка<br/>гарантия 1 год
						</div>
						<!--/noindex-->
					</div>		
				</li>
				
				<li class="m12">
					<a rel="nofollow" class="item" href="/dealer/contacts.php">
						<span class="list-img-wrap">
							<img title="Изготовление наклеек на заказ - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" alt="Изготовление наклеек на заказ - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if} " src="/images/_tmp/stickers/16.jpg" />
						</span>
					</a>
					<div class="item">
						<a rel="nofollow"  class="title" title="Изготовление наклеек на заказ- {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="/dealer/contacts.php">Изготовление наклеек на заказ</a>
						<!--noindex--><span class="price">от&nbsp;990&nbsp;руб.</span>
						<!--/noindex-->
					</div>		
				</li>
			</ul>
		</div>
	</div>
</div>
<div style="clear:both"></div>