{if $filters.category == 'laptops'}

	{if $PAGE->url == '/catalog/laptops/' || $PAGE->url == '/catalog/laptops/new/'}
	
		<div id="macbook-menu">
			
			<ul id="macbook-menu--models">
				<li><a href="#" data-tab="0" class="active">MacBookPro</a></li>
				<li><a href="#" data-tab="1">MacBook Air</a></li>
				<li><a href="#" data-tab="2">MacBook</a></li>
				<li><a href="/catalog/laptops/pc-notebook/" class="self-size">Свой размер</a></li>
			</ul>
			
			<ul id="macbook-menu--lines">
				<li>
					<div class="year clearfix">
						<span>2016-2017</span>
						<ul class="models">
							<li><a href="/catalog/laptops/macbook-retina-pro-touch-bar/new/">Touch Bar"13</a></li>
						</ul>
					</div>
					
					<div class="year clearfix">
						<span>2015</span>
						<ul class="models">
							<li><a href="/catalog/laptops/macbook-retina-pro/new/">"13</a></li>
							<li><a href="/catalog/laptops/macbook-retina-pro/new/">"15</a></li>
						</ul>
					</div>
					
					<div class="year clearfix">
						<span>2013-2015</span>
						<ul class="models">
							<li><a href="/catalog/laptops/macbook-retina-pro/new/">Retina"13</a></li>
							<li><a href="/catalog/laptops/macbook-retina-pro/new/">Retina"15</a></li>
						</ul>
					</div>
					
					<div class="year clearfix">
						<span>2008-2013</span>
						<ul class="models">
							<li><a href="/catalog/laptops/macbook-pro/">Unibody"13</a></li>
							<li><a href="/catalog/laptops/macbook-pro/">Unibody"15</a></li>
							<li><a href="/catalog/laptops/macbook-pro/">Unibody"17</a></li>
						</ul>
					</div>
					
					<div class="year clearfix">
						<span>2006</span>
						<ul class="models">
							<li><a href="/catalog/laptops/macbook-pro/new/">"15</a></li>
							<li><a href="/catalog/laptops/macbook-pro/new/">"17</a></li>
						</ul>
					</div>
				</li>
				
				<li>
					
					<div class="year clearfix">
						<span>2010-2017</span>
						<ul class="models">
							<li><a href="/catalog/laptops/macbook-air/new/">Air Retina"11,6</a></li>
							<li><a href="/catalog/laptops/macbook-air/new/">Air Retina"13,3</a></li>
						</ul>
					</div>
					
					<div class="year clearfix">
						<span>2008-2009</span>
						<ul class="models">
							<li><a href="/catalog/laptops/macbook-air/new/">Air"13,3</a></li>
						</ul>
					</div>
					
				</li>
				
				<li>
					
					<div class="year clearfix">
						<span>2015-2017</span>
						<ul class="models">
							<li><a href="/catalog/laptops/macbook-retina-12inch/new/">Retina"12</a></li>
						</ul>
					</div>
					
					<div class="year clearfix">
						<span>2009-2010</span>
						<ul class="models">
							<li><a href="/catalog/laptops/macbook/new/">Белый"13</a></li>
						</ul>
					</div>
					
				</li>
			</ul>
		</div>
		
	{/if}
	
{else}

	<div class="b-telephone-select {$category}" category="{$category}">
		
		<ul class="left-menu tel-list">
			{foreach from=$MODELS key="key" item="item_2" name="submenu1foreach"}
				{if $key != 'title'}
					<li class="li {if $smarty.foreach.submenu1foreach.iteration == 4} lowblock{/if}"><a class="{if $key == $Style->cat_slug} active {/if} {if $item_2.top} top{/if}" _id="{$key}" name="{$key}_menu_lev2" href="/ajax/getNewCategoryDesigns/{$category}/{$key}/" rel="nofollow">{$item_2.title}</a></li>
				{/if}
			{/foreach}
		</ul>
	
		<div class="menu-level2-marka {$key}"></div>
		<div class="menu-level2">		
			<div class="menu-level2-wrap" style="width:240px;height:{if $category=='laptops'}155{elseif $category=='touchpads'}192{else}272{/if}px">
				{foreach from=$MODELS key="brand" item="b" name="submenu3foreach"}
					{if $brand != 'title'}
						<ul id="{$brand}_menu_lev2" class="sub-menu" {if $brand == $subcategory} style="display:block" {/if}>
							{foreach from=$b key="model_id" item="s" name="submenu2foreach"}
								{if $model_id != 'title'}
								<li><a href="/catalog/{$category}/{$s.style_slug}/new/" _key="{$model_id}" class="{if $Style->style_slug == $s.style_slug} active {/if} load_goods" rel="nofollow">{$s.style_name}</a></li>
								{/if}							
							{/foreach}
							{if $category !='laptops'}
							<li style="margin-top:10px"><a href="#" _key="" class="no_my_model" rel="nofollow">{$L.FOOTER_menu_not_model_find}</a></li>{*Не нашёл свою модель?*}
							{/if}
						</ul>
					{/if}
				{/foreach}
				
			</div>
		</div>

		{if $Style->id == 354 || $Style->id == 628 || $Style->id == 333 || $Style->id == 580 || $Style->id == 360}
		
		<div class="apple-block" id="apple-222">
			<ul>
				<li class="one"></li>
				<li class="two"></li>
				<li class="three on" style="margin-left:16px">
					<a href="{if $Style->style_slug == 'iphone-3gs'}#{else}/catalog/phones/iphone-3gs/new/{/if}" rel="nofollow">
						<span class="knopki">
							<div class="wall"></div>
							<div class="text">наклейка</div>			
						</span>
						<div class="hintB">
							<div class="fake"></div><div class="arr-top"></div>
							<div class="t left">Наклейка на iPhone 3G, 3Gs</div>					
							<div class="price right">{$GADGETS.phones.apple.222.price} руб.</div>
							{if $GADGETS.phones.apple.222.discount > 0}
							<strike class="right">{$GADGETS.phones.apple.222.old_price}</strike>
							{/if}
							<div class="clearfix clr"></div>
							<div class="left img">								
								<img src="/images/catalog/fsdfds_iph_stiker.jpg" alt="" width="116" height="116">
							</div>
							<div class="left text">
								<ul>
									<li>Легко наносится, легко удаляется с iPhone 3G, 3Gs</li>
									<li>Защита iPhone 3G, 3Gs от падений и царапин.</li>
									<li>Профессиональная пленка, которая наносится без пузырьков и легко удаляется</li>
									<li>Уникальный облик вашего iPhone 3G, 3Gs</li>
									<li>Качественная печать и дополнительная ламинация</li>
								</ul>
								<br/>
								<span class="{if $Style->style_slug == 'iphone-3gs'}slinkSw">&darr; Показаны наклейки на iPhone 3G, 3Gs{else}alinkSw">Показать дизайны наклеек на iPhone 3G, 3Gs{/if}</span>
							</div>
							<div class="clearfix clr"></div>
						</div>
					</a>
				</li>
				<div class="clearfix clr"></div>
			</ul>
		</div>
		
		<div class="apple-block" id="apple-224">
			<ul>
				<li class="one {if $Style->style_slug == 'case-iphone-4'}on{/if}">
					<a href="{if $Style->style_slug == 'case-iphone-4'}#{else}/catalog/cases/case-iphone-4/new/{/if}" rel="nofollow">
						<span class="knopki">
							<div class="wall"></div>
							<div class="text">чехол<br>матовый</div>
							<img src="/images/3/sale.gif" width="22" height="14" title="">
						</span>
						<div class="hintB">						
							<div class="fake"></div><div class="arr-top"></div>
							<div class="t left">Матовый чехол для iPhone 4, 4S</div>
							<div class="price right">{$GADGETS.cases.apple.315.price} руб.</div>
							{if $GADGETS.cases.apple.315.discount > 0}
							<strike class="right">{$GADGETS.cases.apple.315.old_price}</strike>
							{/if}
							<div class="clearfix clr"></div>
							<div class="left img">							
								<img src="/images/catalog/menu_case_mate.jpg" alt="Матовый чехол для iPhone 4, 4S" width="116" height="116">
							</div>
							<div class="left text">
								<ul>
									<li>Новая модель 2014 года, матовый чехол</li>
									<li>3D печать по всей поверхности чехла, включая боковины</li>
									<li>Чехол для Айфон 4, 4S не закрывает элементы управления</li>
									<li>Чехол выполнен из прочного матового пластика</li>
									<li>Уникальный облик и защита для вашего iPhone 4, 4S</li>
								</ul>
								<br/>
								<span class="{if $Style->style_slug == 'case-iphone-4'}slinkSw">&darr; Показаны дизайны матовых чехлов для iPhone 4, 4S{else}alinkSw">Показать дизайны матовых чехлов для iPhone 4, 4S{/if}</span>
							</div>
							<div class="clearfix clr"></div>
						</div>
					</a>
				</li>
				<li class="vertikal"></li>
				<li class="two {if $Style->style_slug == 'case-iphone-4-glossy'}on{/if}">
					<a href="{if $Style->style_slug == 'case-iphone-4-glossy'}#{else}/catalog/cases/case-iphone-4-glossy/new/{/if}" rel="nofollow">
						<span class="knopki">
							<div class="wall"></div>
							<div class="text">чехол<br/>глянцевый</div>
							{if $GADGETS.cases.apple.599.discount > 0}<img src="/images/3/sale.gif" width="22" height="14">{/if}		
						</span>
						<div class="hintB">
							<div class="fake"></div><div class="arr-top"></div>
							<div class="t left">Глянцевый чехол для iPhone 4,4S</div>				
							<div class="price right">{$GADGETS.cases.apple.599.price} руб.</div>
							{if $GADGETS.cases.apple.599.discount > 0}
							<strike class="right">{$GADGETS.cases.apple.599.old_price}</strike>
							{/if}
							<div class="clearfix clr"></div>
							<div class="left img">
								<img src="/images/catalog/menu_case_glare_gl_case.jpg" alt="Глянцевый чехол для iPhone 4,4S" width="116" height="116">
							</div>
							<div class="left text">
								<ul>
									<li>Новая модель 2014 года, глянцевый чехол</li>
									<li>3D печать по всей поверхности чехла, включая боковины</li>
									<li>Чехол для Айфон 4, 4S не закрывает элементы управления</li>
									<li>Чехол выполнен из прочного, глянцевого пластика</li>
									<li>Уникальный облик и защита для вашего iPhone 4, 4S</li>
								</ul>
								<br/>
								<span class="{if $Style->style_slug == 'case-iphone-4-glossy'}slinkSw">&darr; Показаны глянц. чехлы для iPhone 4,4S{else}alinkSw">Показать дизайны глянц. чехлов для iPhone 4,4S{/if}</span>
							</div>
							<div class="clearfix clr"></div>
						</div>
					</a>
				</li>
				<li class="vertikal"></li>
				<li class="three on">
					<a href="{if $Style->style_slug == 'iphone-4'}#{else}/catalog/phones/iphone-4/new/{/if}" rel="nofollow">
						<span class="knopki">
							<div class="wall"></div>
							<div class="text">наклейка</div>			
						</span>
						<div class="hintB">
							<div class="fake"></div><div class="arr-top"></div>
							<div class="t left">Наклейка на iPhone 4,4S</div>					
							<div class="price right">{$GADGETS.phones.apple.224.price} руб.</div>
							{if $GADGETS.phones.apple.224.discount > 0}
							<strike class="right">{$GADGETS.phones.apple.224.old_price}</strike>
							{/if}
							<div class="clearfix clr"></div>
							<div class="left img">
								<img src="/images/catalog/fsdfds_iph_stiker.jpg" alt="" width="116" height="116">									
							</div>
							<div class="left text">
								<ul>
									<li>Легко наносится, легко удаляется с iPhone 4</li>
									<li>Защита iPhone 4 от падений и царапин.</li>
									<li>Профессиональная пленка, которая наносится без пузырьков и легко удаляется</li>
									<li>Уникальный облик вашего iPhone 4</li>
									<li>Качественная печать и дополнительная ламинация</li>
								</ul>
								<br/>
								<span class="{if $Style->style_slug == 'iphone-4'}slinkSw">&darr; Показаны наклейки на iPhone 4,4S{else}alinkSw">Показать дизайны наклеек на iPhone 4,4S{/if}</span>
							</div>
							<div class="clearfix clr"></div>
						</div>
					</a>
				</li>
				{*<li class="vertikal"></li>
				<li class="four {if $Style->style_slug == 'iphone-4-resin'}on{/if}">
					<a href="{if $Style->style_slug == 'iphone-4-resin'}#{else}/catalog/phones/iphone-4-resin/new/{/if}" rel="nofollow">
						<span class="knopki">						
							<div class="wall"></div>
							<div class="text">смола</div>		
						</span>
						<div class="hintB">
							<div class="fake"></div><div class="arr-top"></div>
							<div class="t left">Смола на iPhone 4,4S</div>					
							<div class="price right">{$GADGETS.phones.apple.579.price} руб.</div>
							{if $GADGETS.phones.apple.579.discount > 0}
							<strike class="right">{$GADGETS.phones.apple.579.old_price}</strike>
							{/if}
							<div class="clearfix clr"></div>
							<div class="left img">
								<img src="/images/catalog/5374a78091_s2smola.jpg" alt="Apple - iPhone 4 со смолой" width="116" height="116">				
							</div>
							<div class="left text">
								<ul>
									<li>Принт заливается специальной полиуретановой смолой</li>
									<li>Легко наносится, легко удаляется с iPhone 4</li>
									<li>Защита iPhone 4 от падений и царапин.</li>
									<li>Наклейка приобретает объем, перестает скользить</li>
									<li>Гарантия на смолу 1 год</li>
								</ul>
								<br/>
								<span class="{if $Style->style_slug == 'iphone-4-resin'}slinkSw">&darr; Показаны дизайны cмолы для iPhone 4,4S{else}alinkSw">Показать дизайны смолы для iPhone 4, 4S{/if}</span>
							</div>
							<div class="clearfix clr"></div>
						</div>
					</a>	
				</li>*}
				{*<li class="vertikal"></li>
				<li class="five {if $Style->style_slug == 'iphone-4-bumper'}on{/if}">
					<a href="{if $Style->style_slug == 'iphone-4-bumper'}#{else}/catalog/phones/iphone-4-bumper/new/{/if}" rel="nofollow">
						<span class="knopki">
							<div class="wall"></div>
							<div class="text">бампер</div>		
						</span>
						<div class="hintB">
							<div class="fake"></div><div class="arr-top"></div>
							<div class="t left">Бампер на iPhone 4,4S</div>					
							<div class="price right">{$GADGETS.phones.apple.359.price} руб.</div>
							{if $GADGETS.phones.apple.359.discount > 0}
							<strike class="right">{$GADGETS.phones.apple.359.old_price}</strike>
							{/if}
							<div class="clearfix clr"></div>
							<div class="left img">
								<img src="/images/catalog/1379663407_1379071850_d3_bamper.jpg" alt="Бампер на iPhone 4,4S" width="116" height="116">
							</div>
							<div class="left text">
								<ul>
									<li>В комплекте бампер черного цвета + наклейка</li>
									<li>Амортизация iPhone 4, 4S при ударах</li>
									<li>Композитный материал, создает дополнительные ребра жесткости</li>
									<li>Идеально подходит iPhone 4, 4S по форме</li>
									<li>Не увеличивает габариты устройства</li>
								</ul>
								<br/>
								<span class="{if $Style->style_slug == 'iphone-4-bumper'}slinkSw">&darr; Показаны дизайны бамперов на iPhone 4,4S{else}alinkSw">Показать дизайны бамперов на iPhone 4,4S{/if}</span>
							</div>
							<div class="clearfix clr"></div>
						</div>
					</a>
				</li>*}
				<div class="clearfix clr"></div>
			</ul>
		</div>
		
		<div class="apple-block" id="apple-333">
			<ul>
				<li class="one {if $Style->style_slug == 'case-iphone-5'}on{/if}">
					<a href="{if $Style->style_slug == 'case-iphone-5'}#{else}/catalog/cases/case-iphone-5/new/{/if}" rel="nofollow">
						<span class="knopki">	
							<div class="wall"></div>
							<div class="text">чехол<br>матовый</div>
							<img src="/images/3/fresh.gif" width="22" height="13" title="">
						</span>
						<div class="hintB">
							<div class="fake"></div><div class="arr-top"></div>
							<div class="t left">Матовый чехол для iPhone 5,5S</div>
							<div class="price right">{$GADGETS.cases.apple.354.price} руб.</div>
							{if $GADGETS.cases.apple.354.discount > 0}
							<strike class="right">{$GADGETS.cases.apple.354.old_price}</strike>
							{/if}
							<div class="clearfix clr"></div>
							<div class="left img">
								<img src="/images/catalog/menu_case_mate.jpg" alt="Матовый чехол для iPhone 5,5S" width="116" height="116">
							</div>
							<div class="left text">
								<ul>
									<li>Новая модель 2014 года - матовый чехол</li>
									<li>3D печать по всей поверхности чехла, включая боковины</li>
									<li>Чехол для Айфон 5, 5S не закрывает элементы управления</li>
									<li>Чехол выполнен из прочного матового пластика</li>
									<li>Уникальный облик и защита для вашего iPhone 5, 5S</li>
								</ul>
								<br/>
								<span class="{if $Style->style_slug == 'case-iphone-5'}slinkSw">&darr; Показаны дизайны матовых чехлов для iPhone 5,5S{else}alinkSw">Показать дизайны матовых чехлов для iPhone 5,5S{/if}</span>
							</div>
							<div class="clearfix clr"></div>
						</div>
					</a>
				</li>
				<li class="vertikal"></li>
				<li class="two {if $Style->style_slug == 'case-iphone-5-glossy'}on{/if}">
					<a href="{if $Style->style_slug == 'case-iphone-5-glossy'}#{else}/catalog/cases/case-iphone-5-glossy/new/{/if}" rel="nofollow">
						<span class="knopki">	
							<div class="wall"></div>
							<div class="text">чехол<br/>глянцевый</div>
							{if $GADGETS.cases.apple.628.discount > 0}<img src="/images/3/sale.gif" width="22" height="14">{/if}			
						</span>
						<div class="hintB">
							<div class="fake"></div><div class="arr-top"></div>
							<div class="t left">Глянцевый чехол для iPhone 5,5S</div>
							<div class="price right">{$GADGETS.cases.apple.628.price} руб.</div>
							{if $GADGETS.cases.apple.628.discount > 0}
							<strike class="right">{$GADGETS.cases.apple.628.old_price}</strike>
							{/if}
							<div class="clearfix clr"></div>
							<div class="left img">
								<img src="/images/catalog/menu_case_glare_gl_case.jpg" alt="Глянцевый чехол для iPhone 5,5S" width="116" height="116">
							</div>
							<div class="left text">
								<ul>
									<li>Новая модель 2014 года - матовый чехол</li>
									<li>3D печать по всей поверхности чехла, включая боковины</li>
									<li>Чехол для Айфон 5, 5S не закрывает элементы управления</li>
									<li>Чехол выполнен из прочного матового пластика</li>
									<li>Уникальный облик и защита для вашего iPhone 5, 5S</li>
								</ul>
								<br/>
								<span class="{if $Style->style_slug == 'case-iphone-5-glossy'}slinkSw">&darr; Показаны дизайны глянц. чехлов для iPhone 5,5S{else}alinkSw">Показать дизайны глянц. чехлов для iPhone 5,5S{/if}</span>
							</div>
							<div class="clearfix clr"></div>
						</div>
					</a>
				</li>
				<li class="vertikal"></li>
				<li class="three {if $Style->style_slug == 'iphone-5'}on{/if}">
					<a href="{if $Style->style_slug == 'iphone-5'}#{else}/catalog/phones/iphone-5/new/{/if}" rel="nofollow">
						<span class="knopki">
							<div class="wall"></div>
							<div class="text">наклейка</div>			
						</span>
						<div class="hintB">
							<div class="fake"></div><div class="arr-top"></div>
							<div class="t left">Наклейка на iPhone 5,5S</div>					
							<div class="price right">{$GADGETS.phones.apple.333.price} руб.</div>
							{if $GADGETS.phones.apple.333.discount > 0}
							<strike class="right">{$GADGETS.phones.apple.333.old_price}</strike>
							{/if}
							<div class="clearfix clr"></div>
							<div class="left img">
								<img src="/images/catalog/fsdfds_iph_stiker.jpg" alt="" width="116" height="116">									
							</div>
							<div class="left text">
								<ul>
									<li>Легко наносится, легко удаляется с iPhone 5</li>
									<li>Защита iPhone 5 от падений и царапин.</li>
									<li>Профессиональная пленка, которая наносится без пузырьков и легко удаляется</li>
									<li>Уникальный облик вашего iPhone 5</li>
									<li>Качественная печать и дополнительная ламинация</li>
								</ul>
								<br/>
								<span class="{if $Style->style_slug == 'iphone-5'}slinkSw">&darr; Показаны дизайны наклеек iPhone 5,5S{else}alinkSw">Показать дизайны наклеек для iPhone 5,5S{/if}</span>
							</div>
							<div class="clearfix clr"></div>
						</div>
					</a>
				</li>
				{*<li class="vertikal"></li>
				<li class="four {if $Style->style_slug == 'iphone-5-resin'}on{/if}">
					<a href="{if $Style->style_slug == 'iphone-5-resin'}#{else}/catalog/phones/iphone-5-resin/new/{/if}" rel="nofollow">
						<span class="knopki">
							<div class="wall"></div>
							<div class="text">смола</div>		
						</span>
						<div class="hintB">
							<div class="fake"></div><div class="arr-top"></div>
							<div class="t left">Смола на iPhone 5,5S</div>					
							<div class="price right">{$GADGETS.phones.apple.580.price} руб.</div>
							{if $GADGETS.phones.apple.580.discount > 0}
							<strike class="right">{$GADGETS.phones.apple.580.old_price}</strike>
							{/if}
							<div class="clearfix clr"></div>
							<div class="left img">
								<img src="/images/catalog/5374a78091_s2smola.jpg" alt="Apple - iPhone 5 со смолой" width="116" height="116">
							</div>
							<div class="left text">
								<ul>
									<li>Принт заливается специальной полиуретановой смолой</li>
									<li>Легко наносится, легко удаляется с iPhone 5</li>
									<li>Защита iPhone 5 от падений и царапин.</li>
									<li>Наклейка приобретает объем, перестает скользить</li>
									<li>Гарантия на смолу 1 год</li>
								</ul>
								<br/>
								<span class="{if $Style->style_slug == 'iphone-5-resin'}slinkSw">&darr; Показаны дизайны cмолы для iPhone 5,5S{else}alinkSw">Показать дизайны смолы для iPhone 5, 5S{/if}</span>
							</div>
							<div class="clearfix clr"></div>
						</div>
					</a>
				</li>*}
				{*
				<li class="vertikal"></li>
				<li class="four _five {if $Style->style_slug == 'iphone-5-bumper'}on{/if}">
					<a href="{if $Style->style_slug == 'iphone-5-bumper'}#{else}/catalog/phones/iphone-5-bumper/new/{/if}" rel="nofollow">
						<span class="knopki">					
							<div class="wall"></div>
							<div class="text">бампер</div>		
						</span>
						<div class="hintB">
							<div class="fake"></div><div class="arr-top"></div>
							<div class="t left">Бампер на iPhone 5,5S</div>					
							<div class="price right">{$GADGETS.phones.apple.360.price} руб.</div>
							{if $GADGETS.phones.apple.360.discount > 0}
							<strike class="right">{$GADGETS.phones.apple.360.old_price}</strike>
							{/if}
							<div class="clearfix clr"></div>
							<div class="left img">
								<img src="/images/catalog/1379663407_1379071850_d3_bamper.jpg" alt="Бампер на iPhone 5,5S" width="116" height="116">
							</div>
							<div class="left text">
								<ul>
									<li>В комплекте бампер черного цвета + наклейка</li>
									<li>Амортизация iPhone 5, 5S при ударах</li>
									<li>Композитный материал, создает дополнительные ребра жесткости</li>
									<li>Идеально подходит iPhone 5, 5S по форме</li>
									<li>Не увеличивает габариты устройства</li>
								</ul>
								<br/>
								<span class="{if $Style->style_slug == 'iphone-5-bumper'}slinkSw">&darr; Показаны дизайны бамперов на iPhone 5,5S{else}alinkSw">Показать дизайны бамперов на iPhone 5,5S{/if}</span>
							</div>
							<div class="clearfix clr"></div>
						</div>
					</a>
				</li>
				*}
				<div class="clearfix clr"></div>
			</ul>
		</div>
		
		<div class="apple-block" id="apple-553">
			<ul>
				<li class="one"></li>
				<li class="two"></li>
				<li class="three on" style="margin-left:16px;">
					<a href="{if $Style->style_slug == 'iphone-5c'}#{else}/catalog/phones/iphone-5c/new/{/if}" rel="nofollow">
						<span class="knopki">
							<div class="wall"></div>
							<div class="text">наклейка</div>			
						</span>
						<div class="hintB">
							<div class="fake"></div><div class="arr-top"></div>
							<div class="t left">Наклейка на iPhone 5c</div>					
							<div class="price right">{$GADGETS.phones.apple.553.price} руб.</div>
							{if $GADGETS.phones.apple.553.discount > 0}
							<strike class="right">{$GADGETS.phones.apple.553.old_price}</strike>
							{/if}
							<div class="clearfix clr"></div>
							<div class="left img">								
								<img src="/images/catalog/fsdfds_iph_stiker.jpg" alt="" width="116" height="116">
							</div>
							<div class="left text">
								<ul>
									<li>Легко наносится, легко удаляется с iPhone 5c</li>
									<li>Защита iPhone 5c от падений и царапин.</li>
									<li>Профессиональная пленка, которая наносится без пузырьков и легко удаляется</li>
									<li>Уникальный облик вашего iPhone 5c</li>
									<li>Качественная печать и дополнительная ламинация</li>
								</ul>
								<br/>
								<span class="{if $Style->style_slug == 'iphone-5c'}slinkSw">&darr; Показаны наклейки на iPhone 5c{else}alinkSw">Показать дизайны наклеек на iPhone 5c{/if}</span>
							</div>
							<div class="clearfix clr"></div>
						</div>
					</a>
				</li>
				<div class="clearfix clr"></div>
			</ul>
		</div>
		
		<div class="apple-block" id="apple-554">
			<ul>
				<li class="one">
					<a href="{if $Style->style_slug == 'case-iphone-5'}#{else}/catalog/cases/case-iphone-5/new/{/if}" rel="nofollow">
						<span class="knopki">	
							<div class="wall"></div>
							<div class="text">чехол<br>матовый</div>
							<img src="/images/3/fresh.gif" width="22" height="13" title="">
						</span>
						<div class="hintB">
							<div class="fake"></div><div class="arr-top"></div>
							<div class="t left">Матовый чехол для iPhone 5,5S</div>
							<div class="price right">{$GADGETS.cases.apple.354.price} руб.</div>
							{if $GADGETS.cases.apple.354.discount > 0}
							<strike class="right">{$GADGETS.cases.apple.354.old_price}</strike>
							{/if}
							<div class="clearfix clr"></div>
							<div class="left img">
								<img src="/images/catalog/menu_case_mate.jpg" alt="Матовый чехол для iPhone 5,5S" width="116" height="116">
							</div>
							<div class="left text">
								<ul>
									<li>Новая модель 2014 года - матовый чехол</li>
									<li>3D печать по всей поверхности чехла, включая боковины</li>
									<li>Чехол для Айфон 5, 5S не закрывает элементы управления</li>
									<li>Чехол выполнен из прочного матового пластика</li>
									<li>Уникальный облик и защита для вашего iPhone 5, 5S</li>
								</ul>
								<br/>
								<span class="{if $Style->style_slug == 'case-iphone-5'}slinkSw">&darr; Показаны дизайны матовых чехлов для iPhone 5, 5S{else}alinkSw">Показать дизайны матовых чехлов для iPhone 5, 5S{/if}</span>
							</div>
							<div class="clearfix clr"></div>
						</div>
					</a>
				</li>
				<li class="vertikal"></li>
				<li class="two {if $Style->style_slug == 'case-iphone-5-glossy'}on{/if}">
					<a href="{if $Style->style_slug == 'case-iphone-5-glossy'}#{else}/catalog/cases/case-iphone-5-glossy/new/{/if}" rel="nofollow">
						<span class="knopki">	
							<div class="wall"></div>
							<div class="text">чехол<br/>глянцевый</div>
							{if $GADGETS.cases.apple.628.discount > 0}<img src="/images/3/sale.gif" width="22" height="14">{/if}			
						</span>
						<div class="hintB">
							<div class="fake"></div><div class="arr-top"></div>
							<div class="t left">Глянцевый чехол для iPhone 5,5S</div>					
							<div class="price right">{$GADGETS.cases.apple.628.price} руб.</div>
							{if $GADGETS.cases.apple.628.discount > 0}
							<strike class="right">{$GADGETS.cases.apple.628.old_price}</strike>
							{/if}
							<div class="clearfix clr"></div>
							<div class="left img">
								<img src="/images/catalog/menu_case_glare_gl_case.jpg" alt="Глянцевый чехол для iPhone 5,5S" width="116" height="116">
							</div>
							<div class="left text">
								<ul>
									<li>Новая модель 2014 года - матовый чехол</li>
									<li>3D печать по всей поверхности чехла, включая боковины</li>
									<li>Чехол для Айфон 5, 5S не закрывает элементы управления</li>
									<li>Чехол выполнен из прочного матового пластика</li>
									<li>Уникальный облик и защита для вашего iPhone 5, 5S</li>
								</ul>
								<br/>
								<span class="{if $Style->style_slug == 'case-iphone-5-glossy'}slinkSw">&darr; Показаны дизайны глянц. чехлов для iPhone 5,5S{else}alinkSw">Показать дизайны глянц. чехлов для iPhone 5,5S{/if}</span>
							</div>
							<div class="clearfix clr"></div>
						</div>
					</a>
				</li>
				<li class="vertikal"></li>
				<li class="three on">
					<a href="{if $Style->style_slug == 'iphone-5s'}#{else}/catalog/phones/iphone-5s/new/{/if}" rel="nofollow">
						<span class="knopki">						
							<div class="wall"></div>
							<div class="text">наклейка</div>			
						</span>
						<div class="hintB">
							<div class="fake"></div><div class="arr-top"></div>
							<div class="t left">Наклейка на iPhone 5S</div>					
							<div class="price right">{$GADGETS.phones.apple.554.price} руб.</div>
							{if $GADGETS.phones.apple.554.discount > 0}
							<strike class="right">{$GADGETS.phones.apple.554.old_price}</strike>
							{/if}
							<div class="clearfix clr"></div>
							<div class="left img">
								<img src="/images/catalog/fsdfds_iph_stiker.jpg" alt="" width="116" height="116">										
							</div>
							<div class="left text">
								<ul>
									<li>Легко наносится, легко удаляется с iPhone 5s</li>
									<li>Защита iPhone 5s от падений и царапин.</li>
									<li>Профессиональная пленка, которая наносится без пузырьков и легко удаляется</li>
									<li>Уникальный облик вашего iPhone 5s</li>
									<li>Качественная печать и дополнительная ламинация</li>
								</ul>
								<br/>
								<span class="{if $Style->style_slug == 'iphone-5s'}slinkSw">&darr; Показаны дизайны наклеек iPhone 5S{else}alinkSw">Показать дизайны наклеек для iPhone 5S{/if}</span>
							</div>
							<div class="clearfix clr"></div>
						</div>
					</a>	
				</li>
				{*<li class="vertikal"></li>
				<li class="four {if $Style->style_slug == 'iphone-5s-resin'}on{/if}">
					<a href="{if $Style->style_slug == 'iphone-5s-resin'}#{else}/catalog/phones/iphone-5s-resin/new/{/if}" rel="nofollow">
						<span class="knopki">		
							<div class="wall"></div>
							<div class="text">смола</div>		
						</span>
						<div class="hintB">
							<div class="fake"></div><div class="arr-top"></div>
							<div class="t left">Смола на iPhone 5,5S</div>					
							<div class="price right">{$GADGETS.phones.apple.581.price} руб.</div>
							{if $GADGETS.phones.apple.581.discount > 0}
							<strike class="right">{$GADGETS.phones.apple.581.old_price}</strike>
							{/if}
							<div class="clearfix clr"></div>
							<div class="left img">
								<img src="/images/catalog/5374a78091_s2smola.jpg" alt="Apple - iPhone 5 со смолой" width="116" height="116">
							</div>
							<div class="left text">
								<ul>
								<li>Принт заливается специальной полиуретановой смолой</li>
								<li>Легко наносится, легко удаляется с iPhone 5</li>
								<li>Защита iPhone 5 от падений и царапин.</li>
								<li>Наклейка приобретает объем, перестает скользить</li>
								<li>Гарантия на смолу 1 год</li>
								</ul>
								<br/>
								<span class="{if $Style->style_slug == 'iphone-5-resin'}slinkSw">&darr; Показаны дизайны cмолы для iPhone 5,5S{else}alinkSw">Показать дизайны смолы для iPhone 5, 5S{/if}</span>
							</div>
							<div class="clearfix clr"></div>
						</div>
					</a>
				</li>*}
				<li class="vertikal"></li>
				<li class="four _five {if $Style->style_slug == 'iphone-5-bumper'}on{/if}">
					<a href="{if $Style->style_slug == 'iphone-5-bumper'}#{else}/catalog/phones/iphone-5-bumper/new/{/if}" rel="nofollow">
						<span class="knopki">	
							<div class="wall"></div>
							<div class="text">бампер</div>		
						</span>
						<div class="hintB">
							<div class="fake"></div><div class="arr-top"></div>
							<div class="t left">Бампер на iPhone 5,5S</div>					
							<div class="price right">{$GADGETS.phones.apple.360.price} руб.</div>
							{if $GADGETS.phones.apple.360.discount > 0}
							<strike class="right">{$GADGETS.phones.apple.360.old_price}</strike>
							{/if}
							<div class="clearfix clr"></div>
							<div class="left img">
								<img src="/images/catalog/1379663407_1379071850_d3_bamper.jpg" alt="Бампер на iPhone 5,5S" width="116" height="116">
							</div>
							<div class="left text">
								<ul>
									<li>В комплекте бампер черного цвета + наклейка</li>
									<li>Амортизация iPhone 5, 5S при ударах</li>
									<li>Композитный материал, создает дополнительные ребра жесткости</li>
									<li>Идеально подходит iPhone 5, 5S по форме</li>
									<li>Не увеличивает габариты устройства</li>
								</ul>
								<br/>
								<span class="{if $Style->style_slug == 'iphone-5-bumper'}slinkSw">&darr; Показаны дизайны бамперов на iPhone 5,5S{else}alinkSw">Показать дизайны бамперов на iPhone 5,5S{/if}</span>
							</div>
							<div class="clearfix clr"></div>
						</div>
					</a>
				</li>
				<div class="clearfix clr"></div>
			</ul>
		</div>
		
		<div class="apple-block" id="apple-630">
			<ul>
				<li class="one {if $Style->style_slug == 'case-iphone-6'}on{/if}">
					<a href="{if $Style->style_slug == 'case-iphone-6'}#{else}/catalog/cases/case-iphone-6/new/{/if}" rel="nofollow">
						<span class="knopki">	
							<div class="wall"></div>
							<div class="text">чехол<br>матовый</div>
							<img src="/images/3/fresh.gif" width="22" height="13" title="">
						</span>
						<div class="hintB">
							<div class="fake"></div><div class="arr-top"></div>
							<div class="t left">Матовый чехол для iPhone 6</div>
							<div class="price right">690 руб.</div>
							{if $GADGETS.cases.apple.643.discount > 0}
							<strike class="right">{$GADGETS.cases.apple.643.old_price}</strike>
							{/if}
							<div class="clearfix clr"></div>
							<div class="left img">
								<img src="/images/catalog/menu_case_mate.jpg" alt="Матовый чехол для iPhone 6" width="116" height="116">
							</div>
							<div class="left text">
								<ul>
									<li>Новая модель 2014 года - матовый чехол</li>
									<li>3D печать по всей поверхности чехла, включая боковины</li>
									<li>Чехол для iPhone 6 не закрывает элементы управления</li>
									<li>Чехол выполнен из прочного матового пластика</li>
									<li>Уникальный облик и защита для вашего iPhone 6</li>
								</ul>
								<br/>
								<span class="{if $Style->style_slug == 'case-iphone-6'}slinkSw">&darr; Показаны дизайны матовых чехлов для iPhone 6{else}alinkSw">Показать дизайны матовых чехлов для iPhone 6{/if}</span>
							</div>
							<div class="clearfix clr"></div>
						</div>
					</a>
				</li>
				<li class="vertikal"></li>
				<li class="two {if $Style->style_slug == 'case-iphone-6-glossy'}on{/if}">
					<a href="{if $Style->style_slug == 'case-iphone-6-glossy'}#{else}/catalog/cases/case-iphone-6-glossy/new/{/if}" rel="nofollow">
						<span class="knopki">	
							<div class="wall"></div>
							<div class="text">чехол<br/>глянцевый</div>
							{if $GADGETS.cases.apple.666.discount > 0}<img src="/images/3/sale.gif" width="22" height="14">	{/if}		
						</span>
						<div class="hintB">
							<div class="fake"></div><div class="arr-top"></div>
							<div class="t left">Глянцевый чехол для iPhone 6</div>					
							<div class="price right">690 руб.</div>
							{if $GADGETS.cases.apple.666.discount > 0}
							<strike class="right">{$GADGETS.cases.apple.666.old_price}</strike>
							{/if}
							<div class="clearfix clr"></div>
							<div class="left img">
								<img src="/images/catalog/menu_case_glare_gl_case.jpg" alt="Глянцевый чехол для iPhone 6" width="116" height="116">
							</div>
							<div class="left text">
								<ul>
									<li>Новая модель 2014 года - матовый чехол</li>
									<li>3D печать по всей поверхности чехла, включая боковины</li>
									<li>Чехол для iPhone 6 не закрывает элементы управления</li>
									<li>Чехол выполнен из прочного матового пластика</li>
									<li>Уникальный облик и защита для вашего iPhone 6</li>
								</ul>
								<br/>
								<span class="{if $Style->style_slug == 'case-iphone-6-glossy'}slinkSw">&darr; Показаны дизайны глянц. чехлов для iPhone 6{else}alinkSw">Показать дизайны глянц. чехлов для iPhone 6{/if}</span>
							</div>
							<div class="clearfix clr"></div>
						</div>
					</a>
				</li>
				<li class="vertikal"></li>
				<li class="three on">
					<a href="{if $Style->style_slug == 'iphone-6'}#{else}/catalog/phones/iphone-6/new/{/if}" rel="nofollow">
						<span class="knopki">	
							<div class="wall"></div>
							<div class="text">наклейка</div>			
						</span>
						<div class="hintB">
							<div class="fake"></div><div class="arr-top"></div>
							<div class="t left">Наклейка на iPhone 6</div>					
							<div class="price right">{$GADGETS.phones.apple.630.price} руб.</div>
							{if $GADGETS.phones.apple.630.discount > 0}
							<strike class="right">{$GADGETS.phones.apple.630.old_price}</strike>
							{/if}
							<div class="clearfix clr"></div>
							<div class="left img">
								<img src="/images/catalog/fsdfds_iph_stiker.jpg" alt="" width="116" height="116">
							</div>
							<div class="left text">
								<ul>
									<li>Легко наносится, легко удаляется с iPhone 6</li>
									<li>Защита iPhone 6 от падений и царапин.</li>
									<li>Профессиональная пленка, которая наносится без пузырьков и легко удаляется</li>
									<li>Уникальный облик вашего iPhone 6</li>
									<li>Качественная печать и дополнительная ламинация</li>
								</ul>
								<br/>
								<span class="{if $Style->style_slug == 'iphone-6'}slinkSw">&darr; Показаны дизайны наклеек iPhone 6{else}alinkSw">Показать дизайны наклеек для iPhone 6{/if}</span>
							</div>
							<div class="clearfix clr"></div>
						</div>
					</a>
				</li>
				{*<li class="vertikal"></li>
				<li class="four {if $Style->style_slug == 'iphone-6-resin'}on{/if}">
					<a href="{if $Style->style_slug == 'iphone-6-resin'}#{else}/catalog/phones/iphone-6-resin/new/{/if}" rel="nofollow">
						<span class="knopki">	
							<div class="wall"></div>
							<div class="text">смола</div>		
						</span>
						<div class="hintB">
							<div class="fake"></div><div class="arr-top"></div>
							<div class="t left">Смола на iPhone 6</div>					
							<div class="price right">{$GADGETS.phones.apple.631.price} руб.</div>
							{if $GADGETS.phones.apple.631.discount > 0}
							<strike class="right">{$GADGETS.phones.apple.631.old_price}</strike>
							{/if}
							<div class="clearfix clr"></div>
							<div class="left img">
								<img src="/images/catalog/5374a78091_s2smola.jpg" alt="Apple - iPhone 6 со смолой" width="116" height="116">
							</div>
							<div class="left text">
								<ul>
								<li>Принт заливается специальной полиуретановой смолой</li>
								<li>Легко наносится, легко удаляется с iPhone 6</li>
								<li>Защита iPhone 6 от падений и царапин.</li>
								<li>Наклейка приобретает объем, перестает скользить</li>
								<li>Гарантия на смолу 1 год</li>
								</ul>
								<br/>
								<span class="{if $Style->style_slug == 'iphone-6-resin'}slinkSw">&darr; Показаны дизайны cмолы для iPhone 6{else}alinkSw">Показать дизайны смолы для iPhone 6{/if}</span>
							</div>
							<div class="clearfix clr"></div>
						</div>
					</a>
				</li>*}
				<li class="vertikal"></li>
				<li class="four _five {if $Style->style_slug == 'iphone-6-bumper'}on{/if}">
					<a href="{if $Style->style_slug == 'iphone-6-bumper'}#{else}/catalog/phones/iphone-6-bumper/new/{/if}" rel="nofollow">
						<span class="knopki">	
							<div class="wall"></div>
							<div class="text">бампер</div>		
						</span>
						<div class="hintB" >
							<div class="fake"></div><div class="arr-top"></div>
							<div class="t left">Бампер на iPhone 6</div>					
							<div class="price right">{$GADGETS.phones.apple.722.price} руб.</div>
							{if $GADGETS.phones.apple.722.discount > 0}
							<strike class="right">{$GADGETS.phones.apple.722.old_price}</strike>
							{/if}
							<div class="clearfix clr"></div>
							<div class="left img">
								<img src="/images/catalog/1379663407_1379071850_d3_bamper.jpg" alt="Бампер на iPhone 6" width="116" height="116">
							</div>
							<div class="left text">
								<ul>
									<li>В комплекте бампер черного цвета + наклейка</li>
									<li>Амортизация iPhone 6 при ударах</li>
									<li>Композитный материал, создает дополнительные ребра жесткости</li>
									<li>Идеально подходит iPhone 6 по форме</li>
									<li>Не увеличивает габариты устройства</li>
								</ul>
								<br/>
								<span class="{if $Style->style_slug == 'iphone-6-bumper'}slinkSw">&darr; Показаны дизайны бамперов на iPhone 6{else}alinkSw">Показать дизайны бамперов на iPhone 6{/if}</span>
							</div>
							<div class="clearfix clr"></div>
						</div>
					</a>
				</li>
				<div class="clearfix clr"></div>
			</ul>
		</div>
		
		<div class="apple-block" id="apple-633">
			<ul>
				<li class="one {if $Style->style_slug == 'case-iphone-6-plus'}on{/if}">
					<a href="{if $Style->style_slug == 'case-iphone-6-plus'}#{else}/catalog/cases/case-iphone-6-plus/new/{/if}" rel="nofollow">
						<span class="knopki">	
							<div class="wall"></div>
							<div class="text">чехол<br>матовый</div>
							<img src="/images/3/fresh.gif" width="22" height="13" title="">
						</span>
						<div class="hintB">
							<div class="fake"></div><div class="arr-top"></div>
							<div class="t left">Матовый чехол для iPhone 6 Plus</div>
							<div class="price right">790 руб.</div>
							{if $GADGETS.cases.apple.644.discount > 0}
							<strike class="right">{$GADGETS.cases.apple.644.old_price}</strike>
							{/if}
							<div class="clearfix clr"></div>
							<div class="left img">
								<img src="/images/catalog/menu_case_mate.jpg" alt="Матовый чехол для iPhone 6 Plus" width="116" height="116">
							</div>
							<div class="left text">
								<ul>
									<li>Новая модель 2014 года - матовый чехол</li>
									<li>3D печать по всей поверхности чехла, включая боковины</li>
									<li>Чехол для iPhone 6 Plus не закрывает элементы управления</li>
									<li>Чехол выполнен из прочного матового пластика</li>
									<li>Уникальный облик и защита для вашего iPhone 6 Plus</li>
								</ul>
								<span class="{if $Style->style_slug == 'case-iphone-6-plus'}slinkSw">&darr; Показаны дизайны матовых чехлов для iPhone 6 Plus{else}alinkSw">Показать дизайны матовых чехлов для iPhone 6 Plus{/if}</span>
							</div>
							<div class="clearfix clr"></div>
						</div>
					</a>
				</li>
				<li class="vertikal"></li>
				<li class="two {if $Style->style_slug == 'case-iphone-6-plus-glossy'}on{/if}">
					<a href="{if $Style->style_slug == 'case-iphone-6-plus-glossy'}#{else}/catalog/cases/case-iphone-6-plus-glossy/new/{/if}" rel="nofollow">
						<span class="knopki">							
							<div class="wall"></div>
							<div class="text">чехол<br/>глянцевый</div>
							{if $GADGETS.cases.apple.667.discount > 0}<img src="/images/3/sale.gif" width="22" height="14">{/if}			
						</span>
						<div class="hintB">
							<div class="fake"></div><div class="arr-top"></div>
							<div class="t left">Глянцевый чехол для iPhone 6 Plus</div>				
							<div class="price right">790 руб.</div>
							{if $GADGETS.cases.apple.667.discount > 0}
							<strike class="right">{$GADGETS.cases.apple.667.old_price}</strike>
							{/if}
							<div class="clearfix clr"></div>
							<div class="left img">
								<img src="/images/catalog/menu_case_glare_gl_case.jpg" alt="Глянцевый чехол для iPhone 6 Plus" width="116" height="116">
							</div>
							<div class="left text">
								<ul>
									<li>Новая модель 2014 года - матовый чехол</li>
									<li>3D печать по всей поверхности чехла, включая боковины</li>
									<li>Чехол для iPhone 6 Plus не закрывает элементы управления</li>
									<li>Чехол выполнен из прочного матового пластика</li>
									<li>Уникальный облик и защита для вашего iPhone 6 Plus</li>
								</ul>
								<span class="{if $Style->style_slug == 'case-iphone-6-plus-glossy'}slinkSw">&darr; Показаны дизайны глянц. чехлов для iPhone 6 Plus{else}alinkSw">Показать дизайны глянц. чехлов для iPhone 6 Plus{/if}</span>
							</div>
							<div class="clearfix clr"></div>
						</div>
					</a>
				</li>
				<li class="vertikal"></li>
				<li class="three on">
					<a href="{if $Style->style_slug == 'iphone-6-plus'}#{else}/catalog/phones/iphone-6-plus/new/{/if}" rel="nofollow">
						<span class="knopki">	
							<div class="wall"></div>
							<div class="text">наклейка</div>			
						</span>
						<div class="hintB">
							<div class="fake"></div><div class="arr-top"></div>
							<div class="t left">Наклейка на iPhone 6 Plus</div>					
							<div class="price right">{$GADGETS.phones.apple.633.price} руб.</div>
							{if $GADGETS.phones.apple.633.discount > 0}
							<strike class="right">{$GADGETS.phones.apple.633.old_price}</strike>
							{/if}
							<div class="clearfix clr"></div>
							<div class="left img">
								<img src="/images/catalog/fsdfds_iph_stiker.jpg" alt="" width="116" height="116">								
							</div>
							<div class="left text">
								<ul>
									<li>Легко наносится, легко удаляется с iPhone 6 Plus</li>
									<li>Защита iPhone 6 Plus от падений и царапин.</li>
									<li>Профессиональная пленка, которая наносится без пузырьков и легко удаляется</li>
									<li>Уникальный облик вашего iPhone 6 Plus</li>
									<li>Качественная печать и дополнительная ламинация</li>
								</ul>
								<br/>
								<span class="{if $Style->style_slug == 'iphone-6-plus'}slinkSw">&darr; Показаны дизайны наклеек iPhone 6 Plus{else}alinkSw">Показать дизайны наклеек для iPhone 6 Plus{/if}</span>
							</div>
							<div class="clearfix clr"></div>
						</div>
					</a>	
				</li>
				{*<li class="vertikal"></li>
				<li class="four {if $Style->style_slug == 'iphone-6-plus-resin'}on{/if}">
					<a href="{if $Style->style_slug == 'iphone-6-plus-resin'}#{else}/catalog/phones/iphone-6-plus-resin/new/{/if}" rel="nofollow">
						<span class="knopki">	
							<div class="wall"></div>
							<div class="text">смола</div>		
						</span>
						<div class="hintB">
							<div class="fake"></div><div class="arr-top"></div>
							<div class="t left">Смола на iPhone 6 Plus</div>					
							<div class="price right">{$GADGETS.phones.apple.635.price} руб.</div>
							{if $GADGETS.phones.apple.635.discount > 0}
							<strike class="right">{$GADGETS.phones.apple.635.old_price}</strike>
							{/if}
							<div class="clearfix clr"></div>
							<div class="left img">
								<img src="/images/catalog/5374a78091_s2smola.jpg" alt="Apple - iPhone 6 Plus со смолой" width="116" height="116">
							</div>
							<div class="left text">
								<ul>
								<li>Принт заливается специальной полиуретановой смолой</li>
								<li>Легко наносится, легко удаляется с iPhone 6 Plus</li>
								<li>Защита iPhone 6 Plus от падений и царапин.</li>
								<li>Наклейка приобретает объем, перестает скользить</li>
								<li>Гарантия на смолу 1 год</li>
								</ul>
								<br/>
								<span class="{if $Style->style_slug == 'iphone-6-plus-resin'}slinkSw">&darr; Показаны дизайны cмолы для iPhone 6 Plus{else}alinkSw">Показать дизайны смолы для iPhone 6 Plus{/if}</span>
							</div>
							<div class="clearfix clr"></div>
						</div>
					</a>
				</li>*}			
				<div class="clearfix clr"></div>
			</ul>
		</div>
		
		{/if}

		<div class="goods-block" style="display: none;" key="{$key}" model="">
			<div class="block-wrap">			
				<div class="goods-line" id="goods-line-box">
				</div>			
			</div>
			<p>
				<span id="showalldesignsDef" _def="{$Style->id}">&darr; Показаны все дизайны {$Style->style_name}</span>
				<a href="#" id="showalldesigns" rel="nofollow" style="display: none">Показать все дизайны</a>
				{if $USER->meta->mjteam == "super-admin"}
				| <a href="#" id="editstyle" target="_blank" style="border-bottom:1px dashed orange">Редактировать</a>
				{/if}
			</p>
		</div>	
    	
    	<div class="clearfix clr"></div>
    	
	</div>
{/if}

{literal}
<script>
	$('#macbook-menu--models a').not('.self-size').click(function(){
		$('#macbook-menu--lines > li').hide();
		$('#macbook-menu--lines').children().eq($(this).data('tab')).show();
		$('#macbook-menu--models li a').removeClass('active');
		$(this).addClass('active');
		return false;
	});
</script>
{/literal}