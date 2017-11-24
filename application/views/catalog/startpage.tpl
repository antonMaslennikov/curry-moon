<div class="b-catalog_v2 moveSidebarToLeft list_main_stikers {if $MobilePageVersion && $PAGE->url=='/catalog/male/'}twoAbreastOnlyImg{/if}">

	<div class="pageTitle table">
		<h1>{if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}</h1>
	</div>
	
	{if $MobilePageVersion  && $PAGE->url=='/catalog/stickers/'}
		<span class="FAKEselectbox " style="margin:0 0 5px 44px">
			<div class="select">
				<div class="text"></div>
				<b class="trigger"><i class="arrow"></i></b>
			</div>
			<div class="dropdown">
				<ul>
					<li class="selected">{$L.LIST_menu_collections}</li>
					{foreach from=$TAGS item="t"}
					<li class="aInside"><a href="/tag/{$t.slug}/">{$t.name}</a></li>
					{/foreach}
				</ul>
			</div>
		</span>
	{else}
		<!-- Фильтр в левом сайдбаре -->
		{include file="catalog/list.sidebar.tpl"}
	{/if}

	<div class="catalog_goods_list">		
		<div class="list_wrap">		
			
			{if $PAGE->url == "/catalog/"}
			
				{* if $MobilePageVersion}
				<ul class="trjapki">
					<li class="m12">
						<a rel="nofollow" class="item" href="/customize/">
							<span class="list-img-wrap">
								<img title="Конструктор - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" alt="Конструктор  - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if} " src="/images/_tmp/stickers/constr.jpg" height="" width=""/>
							</span>
						</a>
						<div class="item">
							<a rel="nofollow"  class="title" title="Конструктор - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="/customize/">Конструктор</a>
							<!--noindex--><span class="price">от&nbsp;350&nbsp;руб.</span>
							<div class="garantia">
								Гарантия 1 год
							</div>
							<!--/noindex-->
						</div>
				    </li>
				</ul>
				{/if *}
				
			{/if}
			
			{if $wear}
				
				{foreach from=$wear item="sex" key="sexkey"}
				
					{if $PAGE->url == "/catalog/" || $PAGE->url == "/catalog/futbolki/" || $PAGE->url == "/catalog/sweatshirts/" || $PAGE->url == "/catalog/tolstovki/"}<h4>{if $sexkey == "male"}Мужские{elseif $sexkey == "female"}Женские{elseif $sexkey == "kids"}Детские{/if}</h4><br>{/if}
					
					<a name="{$sexkey}"></a>
					
					<ul class="trjapki">
						{foreach from=$sex item="s" key="k"}
						<li class="m12 {$s.cat_slug}_{$s.style_sex}">
							<a rel="nofollow" class="item" title="{$goods.333.good_name} - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="/catalog/{$s.cat_slug}/{$s.style_slug}/">
								<span class="list-img-wrap">
									<img title="{$s.style_name}, {$s.color_name} - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" alt="{$s.style_name}, {$s.color_name} - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" src="{$s.picture_path}" width=""/>
								</span>
								{if $s.cat_slug=='futbolki_bez_birki'}<span class="bg"></span>{/if}
							</a>
							<div class="item">
								<a rel="nofollow"  class="title" title="{$s.style_name} - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="/catalog/{$s.cat_slug}/{$s.style_slug}/">
									{if $s.cat_slug == "futbolki"}
										Футболки {if $sexkey == "male"}мужские{elseif $sexkey == "male"}женские{elseif $sexkey == "kids"}детские{/if}<br>хлопок
									{elseif $s.cat_slug == "sweatshirts"}
										Свитшоты {if $sexkey == "male"}мужские{elseif $sexkey == "female"}женские{elseif $sexkey == "kids"}детские{/if}<br>хлопок
									{elseif $s.cat_slug == "tolstovki"}
										Толстовки {if $sexkey == "male"}мужские{elseif $sexkey == "female"}женские{elseif $sexkey == "kids"}детские{/if}<br>хлопок
									{elseif $s.cat_slug == "mayki-alkogolichki"}
										Майка борцовка<br>хлопок
									{else}
										{$s.style_name}
									{/if}
								</a>
								<!--noindex-->
								<span class="price {if $s.discount > 0}gold{/if}">
									{if !$s.price}
										от 990&nbsp;руб.
									{else}
										{if $s.discount > 0}
											<s>{$s.old_price}</s> {$s.price}&nbsp;руб.
										{else}
											{$s.price}&nbsp;руб.
										{/if}
									{/if}
								</span>
								<div class="garantia">Гарантия 1 год</div>
								<!--/noindex-->
							</div>		
						</li>
						{/foreach}
						
						{*Конструктор*}
						<li class="m12">
							<a rel="nofollow" class="item" href="/customize/filter/{if $filters.category}{$filters.category}-{/if}{$sexkey}/">
								<span class="list-img-wrap">
									<img title="Конструктор - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" alt="Конструктор  - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if} " src="/images/_tmp/stickers/constr.jpg" height="" width=""/>
								</span>
							</a>
							<div class="item">
								<a rel="nofollow"  class="title" title="Конструктор - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="/customize/filter/{if $filters.category}{$filters.category}-{/if}{$sexkey}/">Конструктор</a>
							</div>
					    </li>
					</ul>
					
				{/foreach}
			
			{/if}
				
			{if $PAGE->url == "/catalog/"}
				<h4>Чехлы</h4><br>
		
				<ul>				
					{*айфон 6 чехлы*}
					<li class="m12 sticker">
						<a rel="nofollow" class="item" href="/catalog/cases/">
							<span class="list-img-wrap">
								<img title="{if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" alt="{if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" src="http://ic4.maryjane.ru/J/catalog/2014/11/28/pingvin_s_elkoy_case-iphone-6-plus_dffa.jpeg" width="" height="">
							</span>
							<span class="bg"></span>
						</a>
						<div class="item">
							<a rel="nofollow"  class="title" title="Чехлы iPhone {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="/catalog/cases/">Чехлы iPhone</a>
							<!--noindex--><span class="price">1090&nbsp;руб.</span>
							<div class="garantia">
								Гарантия 5 лет
							</div>
							<!--/noindex-->
						</div>
				    </li>
				    
				    {*Конструктор*}
					<li class="m12">
						<a rel="nofollow" class="item" href="/stickermize/cases/">
							<span class="list-img-wrap">
								<img title="Конструктор - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" alt="Конструктор  - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if} " src="/images/_tmp/stickers/constr.jpg" height="" width=""/>
							</span>
						</a>
						<div class="item">
							<a rel="nofollow"  class="title" title="Конструктор - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="/customize/">Конструктор</a>
						</div>
				    </li>
				</ul>
			{/if}
				
			{if $cases}
			
				<ul>
					{foreach from=$cases item="c"}
					
						<li class="m12 cases">
							<a rel="nofollow" class="item" title="{if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="/catalog/cases/{$c.style_slug}/">
								<span class="list-img-wrap">
									<img alt="{if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" src="{$c.picture_path}" />
								</span>
								<span class="bg"></span>
							</a>
							<div class="item">
								<a rel="nofollow"  class="title" title="{if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="/catalog/cases/{$c.style_slug}/">{$c.style_name}</a>
								<!--noindex--><span class="price {if $c.discount > 0}gold{/if}">{if $c.discount > 0}<s>{$c.old_price}</s> {$c.price}{else}{$c.price}{/if}&nbsp;руб.</span>
								<div class="garantia">Гарантия 1 год</div>
								<!--/noindex-->
							</div>		
						</li>
					
					{/foreach}
					
					{*Конструктор*}
					<li class="m12">
						<a rel="nofollow" class="item" href="/stickermize/cases/">
							<span class="list-img-wrap">
								<img title="Конструктор - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" alt="Конструктор  - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if} " src="/images/_tmp/stickers/constr.jpg" height="" width=""/>
							</span>
						</a>
						<div class="item">
							<a rel="nofollow"  class="title" title="Конструктор - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="/customize/">Конструктор</a>
						</div>
				    </li>
				</ul>
			
			{/if}
			
			{if $vinil}
			
				{if $PAGE->url == "/catalog/" || $PAGE->url == "/catalog/male/" || $PAGE->url == "/catalog/female/" || $PAGE->url == "/catalog/kids/"}<h4>Наклейки</h4><br>{/if}
			
				<ul>
					{if $vinil.333}
					<li class="m12 phones">
						<a rel="nofollow" class="item" title="{$vinil.333.good_name} - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="/catalog/phones/new/">
							<span class="list-img-wrap">
								<img alt="{$vinil.333.good_name} - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" src="{$vinil.333.picture_path}" />
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
			
					{if $vinil.631}
					<li class="m12 phones2">
						<a rel="nofollow" class="item" title="{$vinil.631.good_name} - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="/catalog/phones/iphone-5-resin/new/">
							<span class="list-img-wrap">
								<img alt="{$vinil.631.good_name} - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" src="{$vinil.631.picture_path}" />
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
					{/if}
				
					{if $vinil.818}
					<li class="m12 laptops">
						<a rel="nofollow" class="item" title="{$vinil.818.good_name} - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="/catalog/laptops/new/">
							<span class="list-img-wrap">
								<img  alt="{$vinil.818.good_name}  - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" src="{$vinil.818.picture_path}" />
							</span>
							<span class="bg"></span>
						</a>
						<div class="item">
							<a rel="nofollow"  class="title" title="Наклейка на ноутбук - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="/catalog/laptops/new/">Наклейка на ноутбук</a>
							<!--noindex--><span class="price">от&nbsp;690&nbsp;руб.</span>
							<div class="garantia">Гарантия 1 год</div><!--/noindex-->
						</div>		
					</li>
					{/if}
				
					{if $vinil.511}
					<li class="m12 touchpads">
						<a rel="nofollow" class="item" title="{$vinil.511.good_name}  - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="/catalog/touchpads/ipad-air/new/">
							<span class="list-img-wrap">
								<img  alt="{$vinil.511.good_name}  - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if} " src="{$vinil.511.picture_path}" />
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
					
					{if $auto}
						<li class="m12">
							<a rel="nofollow" class="item" title="{$auto.as_oncar_0.good_name}  - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="/catalog/auto/new/">
								<span class="list-img-wrap">
									<img alt="{$auto.as_oncar_0.good_name}  - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" src="{if $auto.as_oncar_0}{$auto.as_oncar_0.picture_path}{elseif $auto.as_oncar_1}{$auto.as_oncar_1.picture_path}{elseif $auto.as_oncar_2}{$auto.as_oncar_2.picture_path}{elseif $auto.as_oncar_3}{$auto.as_oncar_3.picture_path}{elseif $auto.as_oncar_4}{$auto.as_oncar_4.picture_path}{/if}" />
								</span>
							</a>
							<div class="item">
								<a rel="nofollow" class="title" title="{if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="/catalog/auto/new/">Наклейка на авто</a>
								<!--noindex--><span class="price">от&nbsp;55&nbsp;руб.</span>
								<div class="garantia">
									гарантия 5 лет
								</div>
								<!--/noindex-->
							</div>		
						</li>
					{/if}
						
					<li class="m12 sticker notmobileTransform">
						<a rel="nofollow" class="item" title="{$stickerset.good_name}  - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="/stickerset/">
							<span class="list-img-wrap">
								<img alt="{$stickerset.good_name}  - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" src="/images/stickersets.png" />
							</span>
							<span class="bg"></span>
						</a>
						<div class="item">
							<a rel="nofollow"  class="title" title="Стикерсет - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="/stickerset/">Стикерсет из 11 наклеек</a>
							<!--noindex--><span class="price">230&nbsp;руб.</span><!--/noindex-->
						</div>		
					</li>
					
					<li class="m12">
						<a rel="nofollow" class="item" href="/customize/filter/stickers/">
							<span class="list-img-wrap">
								<img title="Изготовление наклеек на заказ - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" alt="Изготовление наклеек на заказ - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if} " src="/images/_tmp/stickers/16.jpg" />
							</span>
						</a>
						<div class="item">
							<a rel="nofollow"  class="title" title="Изготовление наклеек на заказ- {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="/customize/filter/stickers/">Изготовление наклеек на заказ</a>
							<!--noindex--><span class="price">от&nbsp;990&nbsp;руб.</span>
							<!--/noindex-->
						</div>		
					</li>
				</ul>
				
				{if $PAGE->url == "/catalog/stickers/"}
				<div class="catalog-stickers-player">
					<iframe src="https://player.vimeo.com/video/234216113" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
				</div>
				{/if}
				
			{/if}
				
				
			{if $PAGE->url == "/catalog/" || $PAGE->url == "/catalog/home/" || $PAGE->url == "/catalog/male/" || $PAGE->url == "/catalog/female/" || $PAGE->url == "/catalog/kids/"}
			
				{if $PAGE->url == "/catalog/" || $PAGE->url == "/catalog/male/" || $PAGE->url == "/catalog/female/" || $PAGE->url == "/catalog/kids/"}<h4>Для дома</h4><br>{/if}
			
				<ul>
				
					{if $goods.758}
					<li class="m12 pillows">
						<a rel="nofollow" class="item" title="{$goods.758.good_name}  - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="/catalog/pillows/">
							<span class="list-img-wrap">
								<img  alt="{$goods.758.good_name}  - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if} " src="{$goods.758.picture_path}" />
							</span>
							<span class="bg"></span>
						</a>
						<div class="item">
							<a rel="nofollow"  class="title" title="Подушка - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="/catalog/pillows/">Подушка</a>
							<!--noindex--><span class="price">от&nbsp;690&nbsp;руб.</span>
							<div class="garantia">Гарантия 1 год</div><!--/noindex-->
						</div>		
					</li>
					{/if}
					
					{if $goods.754}
					<li class="m12 textile">
						<a rel="nofollow" class="item" title="{$goods.754.good_name}  - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="/catalog/textile/">
							<span class="list-img-wrap">
								<img  alt="{$goods.754.good_name}  - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if} " src="{$goods.754.picture_path}" />
							</span>
							<span class="bg"></span>
						</a>
						<div class="item">
							<a rel="nofollow"  class="title" title="Подушка - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="/catalog/textile/">Ткань</a>
							<!--noindex--><span class="price">от&nbsp;1155&nbsp;руб.</span>
							<div class="garantia">Гарантия 1 год</div><!--/noindex-->
						</div>		
					</li>
					{/if}
					
					{if $goods.520}
					<li class="m12 poster">
						<a rel="nofollow" class="item" title="{$goods.520.good_name}  - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="/catalog/poster/{$goods.520.style_slug}/">
							<span class="list-img-wrap">
								<img  alt="{$goods.520.good_name}  - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if} " src="{$goods.520.picture_path}" />
							</span>
							<span class="bg"></span>
						</a>
						<div class="item">
							<a rel="nofollow"  class="title" title="Подушка - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="/catalog/poster/{$goods.520.style_slug}/">Постер в раме</a>
							<!--noindex--><span class="price">от&nbsp;590&nbsp;руб.</span>
							<div class="garantia">Гарантия 1 год</div><!--/noindex-->
						</div>		
					</li>
					{/if}
					
					{if $cup}
					<li class="m12 cup">
						<a rel="nofollow" class="item" title="{$cup.good_name}  - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="/catalog/cup/">
							<span class="list-img-wrap">
								<img title="{$cup.good_name} - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" alt="{$cup.good_name} - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if} " src="{$cup.picture_path}" />
							</span>
							<span class="bg"></span>
						</a>	
						<div class="item">
							<a rel="nofollow"  class="title" title=" - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="/catalog/cup/">Кружка</a>
							<!--noindex-->
							<span class="price gold"><strike>490</strike> 390&nbsp;руб.</span>
							<div class="garantia">
								Гарантия 1 год
							</div>
							<!--/noindex-->
						</div>		
					</li>
					{/if}
					
					{* сумка с полной запечаткой *}
					{if $goods.744}
					<li class="m12">
						<a rel="nofollow" class="item" href="/catalog/patterns-bag/sumka-s-polnoj-zapechatkoj/">
							<span class="list-img-wrap">
								<img title="{if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" alt="{if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if} " src="{$goods.744.picture_path}" height="" width=""/>
							</span>
						</a>
						<div class="item">
							<a rel="nofollow"  class="title" title="Холст- {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="/catalog/patterns-bag/sumka-s-polnoj-zapechatkoj/">Сумка с полной запечаткой</a>
							<!--noindex--><span class="price">от&nbsp;590&nbsp;руб.</span>
							<div class="garantia">
								Гарантия 1 год
							</div>
							<!--/noindex-->
						</div>
					</li>
					{/if}
					
					{*Конструктор*}
					<li class="m12">
						<a rel="nofollow" class="item" href="/customize/">
							<span class="list-img-wrap">
								<img title="Конструктор - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" alt="Конструктор  - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if} " src="/images/_tmp/stickers/constr.jpg" height="" width=""/>
							</span>
						</a>
						<div class="item">
							<a rel="nofollow"  class="title" title="Конструктор - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="/customize/">Конструктор</a>
						</div>
				    </li>
				</ul>
			{/if}
			
		</div>		
	</div>	
	<!-- Кнопка ВВерх -->
	<div id="button_toTop"><div>{$L.SHOPWINDOW_top}</div></div>
</div>
<br clear="all" />
<br />
<div class="list_seotexts">
	{include file="catalog/list.seotexts.tpl"}
</div>