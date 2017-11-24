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

<script type="text/javascript" src="/{$module}/styles/{$good.good_id}/{if $style}{$style->id}/{/if}"></script>

<script type="text/javascript">
var	default_sex = '{$default.sex}',
	default_category = '{$default.category}',
	default_size = '{$default.size}',
	default_color = '{$default.color}',
	default_good_name = "{$good.good_name_escaped}",
	default_new_id = '{$style->id}',
	default_new_slug = '{$style->style_slug}',
	default_new_category = '{$style->category}';

var likes = {
	 count: '{$good.good_likes}', liked:{ {foreach from=$mylikes item=item key=k name=i}{$k}:{$item}{if !$smarty.foreach.i.last},{/if}{/foreach} }, likeUrl: '/ajax/like/{$good.good_id}/ps_src/'
}

//тока для эскпеременат уберем запоминание, что бы не ставить костыли в скрипте
setCookie('last_tab_catalog', "",  -1, '/');
setCookie('catalog_gender_gadg', "",  -1, '/');
setCookie('catalog_gender', "",  -1, '/');
setCookie('catalog_type', "",  -1, '/');
setCookie('catalog_size', "",  -1, '/');
setCookie('catalog_color', "",  -1, '/');
</script>

<script type="text/javascript" src="/js/tiny_mce/tiny_mce.js"></script>

{* этот файл подключать обязательно после предыдущийх объявлений, так как он использует данные из них *}
<script type="text/javascript" src="/vendor/min/?f=/js/p/catalog.good.js&v20"></script>

<script type="text/javascript" src="/js/2012/auto.js"></script>

{if $USER->id == $good.user_id && $good.good_visible == 'modify'}
<div class="redZebra">
	<div class="abut">Вы перезагрузили исходник для этой работы. <a href="/faq/group/25/view/189/">Почему я вижу старые картинки</a>&nbsp;&nbsp;<span class="help"><a class="help red thickbox" rel="nofollow" href="/faq/189/?height=500&width=600">?</a></span>
	</div>
</div>
{/if}

<div class="b-good-content {$PAGE->lang} LiteVersion" itemscope="itemscope" itemtype="http://schema.org/Product">
	
	<meta itemprop="name" content="{$good.good_name}">
	<meta itemprop="description" content="{$style->style_name} '{$good.good_name}'">
	
	{if $MobilePageVersion}
	<div class="buy_quickly_vote clearfix">			
		<div class="vote-small {if $mylikes.ps_src} select{/if} {if $good.place > 0} heart_red{/if}" title="">
			<span><br replaces="В избранно{if $mylikes.ps_src}м{else}е{/if}"/></span> (<font>{$good.good_likes}</font>)
		</div>
		
		<div id="one_click_order_block">
			<a href="#" _href="/order.v3/" rel="nofollow"><br replaces="{$L.GOOD_buy_quickly}"/></a>
		</div>						
	</div>	
	{/if}
	
	 <!-- Галерея под большим рисунком -->
	<div class="b-bottomgallery b-smlgallery-vertical">
		{if count($style_photos) || $photos || count($pics.as_oncar) > 0 || count($pics.touchpads) > 0 || count($pics.laptops) > 0}
		<a class="left-arr" href="#!/left" rel="nofollow"></a>
		<div class="gallery-wrap">
			<div class="scroll-wrap">					
			
			{* непонятная версия, почему-то заместо основной превьюхи выводилось пусто, переделал 2017-10-13 *}
			{* <a href="#" class="o_good origin active_good" rel="nofollow" style="display:block;"><img src="/images/empty.gif" alt="{$L.GOOD_tshirt_photo} {$good.good_name}" height="48" /></a>*}
			<a href="#" class="o_good origin active_good" rel="nofollow" style="display:block;"><img src="{if $good.mainPreview}{$good.mainPreview}{else}/images/empty.gif{/if}" alt="{$L.GOOD_tshirt_photo} {$good.good_name}" height="48" /></a>
			
			{if $photos|count > 0}
				{foreach from=$photos item=photo}
					<a href="#" name="{$photo.picId}" class="o_good ppphoto" like-name="ps_src" category="clothes" rel="nofollow" {if $photo.style_id > 0}style_id="{$photo.style_id}"{/if}><img src="{$photo.small}" alt="{$L.GOOD_tshirt_photo} {$good.good_name}" height="48" /></a>
				{/foreach}
			{/if}
			
			{if count($pics.as_oncar) > 0}
				{foreach from=$pics.as_oncar item="p" key="k"}
					{if $p.id > 0}
					<a href="#" name="{$p.id}" class="o_good" like-name="as_oncar" _src="{$p.path}" {if $k == 10}_noresize="true"{/if} style="display:none;" category="auto" rel="nofollow"><img src="{$p.preview}" alt="#" height="48" /></a>
					{/if}
				{/foreach}
			{/if}
			
			{if count($style_photos) > 0}
				{foreach from=$style_photos item="style" key="style_id"}
					{foreach from=$style item="p1" key="k1"}
						<a href="#" name="" class="o_good" like-name="ps_src" style_id="{$style_id}" _src="{$p1.big}" rel="nofollow"><img src="{$p1.small}" height="48" /></a>
					{/foreach}
				{/foreach}
			{/if}
			{* if count($pics.postcards) > 0}
				{foreach from=$pics.postcards item=p key=k}
					<a href="#" name="{$p.id}" class="o_good" like-name="postcards" _src="{$p.path}" style="display:none;" category="postcards" rel="nofollow"><img src="{$p.preview}" alt="#" height="48" /></a>
				{/foreach}
			{/if *}
			{* постеры *}
			{if count($pics.photoprint) > 0}
				{foreach from=$pics.photoprint item=p key=k}
					<a href="#" name="{$p.id}" class="o_good" like-name="poster" _src="{$p.path}" style="display:none" category="poster" rel="nofollow"><img src="{$p.preview}" alt="#" height="48" /></a>
				{/foreach}
			{/if}
			{if count($pics.printoncanvas) > 0}
				{foreach from=$pics.printoncanvas item=p key=k}
					<a href="#" name="{$p.id}" class="o_good" like-name="poster" _src="{$p.path}" style="display:none" category="poster" rel="nofollow"><img src="{$p.preview}" alt="#" height="48" /></a>
				{/foreach}
			{/if}
			{if count($pics.printonplastik) > 0}
				{foreach from=$pics.printonplastik item=p key=k}
					<a href="#" name="{$p.id}" class="o_good" like-name="poster" _src="{$p.path}" style="display:none" category="poster" rel="nofollow"><img src="{$p.preview}" alt="#" height="48" /></a>
				{/foreach}
			{/if}
			
			</div>
		</div>
		<a class="right-arr" href="#!/right"  rel="nofollow"></a>
		{else}			
		<!--noindex-->
		<a href="/sendphoto/to/{$good.good_id}/" class="nofoto_sml" rel="nofollow" title="Добавьте ваше фото">					
			<!--img width="48" src="/images/buttons/gallery-add-photo.gif" style="padding-top:5px;" title="Добавьте фото, мы платим 100 руб." alt="Добавьте фото, мы платим 100 руб." /-->
		</a>
		<!--/noindex-->
		{/if}						
	</div>
	
	{* Блок с футболкой внизу галерея и кнопки из соц сетей *}
    <div class="big-imgbox {if $good.competition_id == 2475}monochrome{/if}">
	
		{* Добавить в избранное *}		
		<div class="vote {if $mylikes.ps_src} select{/if} {if $good.place > 0} heart_red {/if}" liked="{$mylikes.ps_src}" likes="{$good.good_likes}{*$likes.ps_src*}"><span>{*$likes.ps_src*}{$good.good_likes}</span></div>
		{* Превьюшка *}
		<div class="big-preview no-select">
			<div id="loader-img"></div>
			
			<a id="voting-img-wrap" href="{if $good.big != ''}{$good.big}{else}/zoom/{$good.good_id}/{$good.bg}/?width=740{/if}" title="" style="text-align:center;background: #{$good.bg}" onclick="return false;" data-big="{$good.big}" class='{if $USER->client->ismobiledevice == '1'}glisse{/if} cloud-zoom voting-good-link no-select' rel="position: 'inside' , showTitle: false, zoomWidth:'648', zoomHeight:'518'">
				<img id="big-img" itemprop="image" class="big-img-pos" src="{if $good.mainPreview}{$good.mainPreview}{else}/images/empty.gif{/if}" alt="{foreach from=$PAGE->title item='t'}{$t}{/foreach}" width="500" />
			</a>			

			{if !$MobilePageVersion}
			<a href="/customize/style,429/{$good.good_id}/#text" rel="nofollow" class="customization-good"></a>
			{/if}							
			
			{if $USER->id == 81706 || $USER->id == 27278 || $USER->id == 215735 || $USER->id == 63250}			
			<a href="/ajax/exportgood/{$good.good_id}/" rel="nofollow" download class="download-good" style="border-bottom:1px dashed orange"></a>
			{/if}
			
			<a href="#" rel="nofollow" class="rotate-good" />
			
			<a {if $pics.stickerset}style="display:none"{/if} data-big="{if $pics.ps_740x.path != ''}{$pics.ps_740x.path}{else}{$good.big}{/if}" rel="nofollow" class="glisse btn-zoom-good"></a>	
			{*else}
				<a {if $pics.stickerset}style="display:none"{/if} href="#" rel="nofollow" class="btn-zoom-good" ></a>
				<div id="img-zoom-block" style="display:none"><img src="{$pics.ps_740x.path}" class="img-zoom-block" style="background-color:#{$good.bg};" alt="{if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if} - Maryjane.ru"></div>
			{/if*}
			
		</div>
        <div class="big-imgbottom">           
			{*раньше тут была галерея, перенес вверх*}
        </div>
		
		{if !$MobilePageVersion}
			<div class="tgs">
				{if $USER->authorized}
				<a href="/ajax/exportgood/{$good.good_id}/?style=407" rel="nofollow" class="download-img" style="color:green">скачать</a>
				{/if}
				{if $USER->id == $good.user_id ||  $USER->id == 27278 ||  $USER->id == 6199 ||  $USER->id == 63250 || $USER->id == 86455 || $USER->id == 105091}
					<a href="/senddrawing.design/{$good.good_id}/" style="color:red" rel="nofollow">ред-ть</a>, 
				{/if}
				{foreach from=$tags name="tags" item="tag"}
					{if $tag.tag_ps_goods > 1}
						<a href="/tag/{$tag.slug}/" {if !$user && $filters.category}rel="nofollow"{/if}>{$tag.name}</a>
					{else}
						{$tag.name}
					{/if}
					{if !$smarty.foreach.tags.last}, {/if}
				{/foreach}
			</div>	
		{/if}
		
    </div> <!-- Окончание блока большой фотографии -->

	<!-- Правее блок с настройками размера/носителя/цвета и кнопка в корзину -->
	<form action="" method="post" id="good_fomr">
		<div class="good-settings">				
				
			<div class="hintAuthorRight clearfix">
				<div class="arr"></div>
				<h2 class="n">{$good.good_name}</h2>
				
				<div class="av left"><a href="/catalog/{$good.user_login}/" rel="nofollow">{$good.user_avatar}</a></div>
				<div class="te left">
					<!--noindex-->
					<a class="a-link" href="/catalog/{$good.user_login}/" rel="nofollow">{$good.user_login}</a>&nbsp;{$user.user_designer_level}
					{if $user.user_city}
					<span class="city">{$user.user_city}</span>
					{/if}
					<div class="rub">
						{if $good.place > 0}
							{if $good.competition_winner}
								<a href="/voting/competition/{$good.competition_slug}/">{$good.competition_winner}</a>
							{else}
								Лучшая работа недели - 15000 руб.
							{/if}
						{else}
							<br replaces="Автор получит"/> <span id="authorReceiveM">10%</span> <br replaces="с продажи."/>
						{/if}
					</div>
					<!--/noindex-->	
				</div>						
			</div>
				
			<div class="good-properties {if $default.sex=='kids'}defaultSexKids{/if}">
				{if !$sale}
				<div class="b-radio-manwomen">
					<div class="radio-input selected-man" id="input_man_woman">
						<a class="type-select" id="male" href="#!/select-man" title="мужская" rel="nofollow"></a>
						<a class="type-select" id="female" href="#!/select-woman" title="женская" rel="nofollow"></a>
						<a class="type-select" id="kids" href="#!/select-kids" title="детская" rel="nofollow"></a>
						<input id="good_gender" type="hidden" name="gender" value="">
					</div>
					<!-- a class="link anchor" name="tab-delivery" href="#" >Доставка</a -->
				</div>
				{/if}
				<div class="good-parts clearfix">
					<div class="good-tpl good-futbolki" tpl="futbolki,tolstovki,mayki-alkogolichki,kid,child,pupil,platya,patterns,patterns-sweatshirts,patterns-tolstovki,body,bomber" manwomen="yes" >
					  {include file="catalog/good.t-shirt.tpl"}
					</div>
					<div class="good-tpl good-sumki" tpl="sumki" manwomen="no" style="display:none">
						{include file="catalog/good.t-sumki.tpl"}
					</div>
					<div class="good-tpl good-auto" tpl="auto" manwomen="no" style="display:none">
						{include file="catalog/good.t-auto.tpl"}
						{include file="catalog/good.t-auto-monochrome.tpl"}
					</div>
					<div class="good-tpl good-moto" tpl="moto" manwomen="no" style="display:none">
						{include file="catalog/good.t-moto.tpl"}
					</div>
					<div class="good-tpl good-phones" tpl="phones" manwomen="no" style="display:none">
						{include file="catalog/good.t-phones.tpl"}
					</div>
					<div class="good-tpl good-laptops" tpl="laptops" manwomen="no" style="display:none">
						{include file="catalog/good.t-laptops.tpl"}
					</div>
					<div class="good-tpl good-touchpads" tpl="touchpads" manwomen="no" style="display:none">
						{include file="catalog/good.t-touchpads.tpl"}
					</div>
					<div class="good-tpl good-postcards" tpl="postcards" manwomen="no" style="display:none">
						{include file="catalog/good.t-postcards.tpl"}
					</div>
					<div class="good-tpl good-ipodmp3" tpl="ipodmp3" manwomen="no" style="display:none">
						{include file="catalog/good.t-ipodmp3.tpl"}
					</div>
					<div class="good-tpl good-poster" tpl="poster" manwomen="no" style="display:none">
						{include file="catalog/good.t-posters.tpl"}
					</div>	
					<div class="good-tpl good-stickerset" tpl="stickerset" manwomen="no" style="display:none">
						{include file="catalog/good.stickerset.tpl"}
					</div>					
					<div class="good-tpl good-boards" tpl="boards" manwomen="no" style="display:none">
						{include file="catalog/good.boards.tpl"}
					</div>
					<div class="good-tpl good-patterns-bag" tpl="patterns-bag" manwomen="yes" style="display:none">
						{include file="catalog/good.patterns-bag.tpl"}
					</div>
					<div class="good-tpl good-bag" tpl="bag" manwomen="no" style="display:none">
						{include file="catalog/good.bag.tpl"}
					</div>	
					<div class="good-tpl good-cup" tpl="cup" manwomen="no" style="display:none">
						{include file="catalog/good.cup.tpl"}
					</div>		
					<div class="good-tpl good-textile" tpl="textile" manwomen="no" style="display:none">
						{include file="catalog/good.textile.tpl"}
					</div>
					<div class="good-tpl good-pillows" tpl="pillows" manwomen="no" style="display:none">
						{include file="catalog/good.pillows.tpl"}
					</div>
				</div>
			</div>
			
			<div class="price-order clearfix" lockedCity="{if $USER->city && $USER->city != 'Москва'}true{/if}" city="{$USER->city}">
			<!--noindex-->
				<div class="predzakaz clearfix" style="display:none">
					{*<div class="info">
						<div class="text"><br replaces="Доступно по <br/> предзаказу"/></div>
						<div class="price">{ *790руб.* }</div>
					</div>*}
					<div class="form">
						<div class="text_email"><br replaces="Укажите Ваш Е-mail, что бы узнать о поступлении товара на склад первым*"/></div>
						<div class="text_yes" style="display:none"><br replaces="Cпасибо, Ваш голос учтен."/></div>
						<input type="text" name="" class="predzakaz-input" placeholder="Email" _placeholder="" value="{$USER->user_email}">
						<input type="submit" value="" class="predzakaz-submit">
						<div class="text_email2"><br replaces="* Предзаказ предназначен для сбора потребительских предпочтений.<br/>Это не обещание поступления на склад выбраного Вами носителя."/></div>
					</div>
				</div>
				<div class="b-good-price-submit V2"  itemprop="offers" itemscope itemtype="http://schema.org/Offer">
					
					<meta itemprop="availability" content="InStock">
			        <meta itemprop="priceCurrency" content="RUB">
					<meta itemprop="price" content="{$default_price}">
					
					{if !$MobilePageVersion}
					<div class="buy_quickly_vote left clearfix">	
						
						<div class="vote-small {if $mylikes.ps_src} select{/if} {if $good.place > 0} heart_red{/if}" title="">
							<span><br replaces="В избранно{if $mylikes.ps_src}м{else}е{/if}"/></span> (<font>{$good.good_likes}</font>)
						</div>
						
						<div id="one_click_order_block">
							<a href="#" _href="/order.v3/" rel="nofollow"><br replaces="{$L.GOOD_buy_quickly}"/></a>
						</div>						
					</div>	
					{/if}
					
					<div class="b-good-price-submit-wrap">
						<input type="submit" id="submit" title="{$L.GOOD_2_basket}" value="{if $PAGE->lang == "ru"}Купить{else}Buy{/if}" class="not_select_size addToBasketTrackGtagmanager"> <!--  onclick="return checkAddToCartForm();" -->
					</div>
				
					<div class="good-price">
						<div class="old-price">{*--*}</div>
						<div class="current-price"><p>цена:</p> <span>{*---*}</span> <br replaces="{if $default.category=='textile'}руб.{else}{$L.CURRENT_CURRENCY}{/if}"/></div>
					</div>
					<a class="success_message" href="/basket/" id="go2basket" rel="nofollow"><br replaces="{$L.GOOD_in_basket}:  +1"/></a>
					<div class="price-hint">
						<span class="price_back_holder">{*<br replaces="Вернем"/> <span id="good_price_back">0</span> <br replaces="руб."/>*}</span>
					</div>
				</div>
				<!--/noindex-->
			</div><!--price-order -->
			
			<!--noindex-->
			<ul class="b-good-calc-deliv clearfix V2 {if $USER->city != 'Москва'}notMoskov{/if}">
				<li class="g">
					<br/>
					<a href="#/moneyback" title="moneyback" rel="nofollow"><br replaces="Гарантия 365 дней"/></a>
				</li>
				<li class="d">
					<br/>
					<a href="#!/show-delivery" title="delivery" rel="nofollow" class="txt"><br replaces="{$USER->city}. Доставим {if $deliver_srok}{$deliver_srok}{else} в течении двух недель{/if}"/></a>
				</li>
				<li class="m">
					<br/>
					<a href="#!/moneyback" title="moneyback" rel="nofollow"><br replaces="Обмен 365 дней"/></a>
				</li>
			</ul><!--/noindex-->			
			
		</div>
		
		{*if $MobilePageVersion}<!--noindex--><div class="b-tshirttype-title">Доступные носители:</div><!--/noindex-->{/if*}
		
		<div class="b-tshirttype">
			<input type="hidden" id="good_type" name="type" value="" />

			{if $pics.patterns.id > 0}
				<span><a href="#" id="tshirt-patterns" like-name="patterns" name="patterns" class="some-tshirt" title="{$L.GOOD_tshirt}"  rel="nofollow">&nbsp;</a></span>
				<span><a href="#" id="tshirt-patterns-sweatshirts" like-name="patterns" name="patterns-sweatshirts" class="some-tshirt" title="{$L.GOOD_tolstovki}"  rel="nofollow">&nbsp;</a></span>
			{/if}
			{if $pics.ps_src.id > 0}
				<span><a href="#" id="tshirt-futbolki" like-name="ps_src" name="futbolki" class="some-tshirt active-tshirt-type" title="{$L.GOOD_tshirt}"  rel="nofollow">&nbsp;</a></span>
				<span><a href="#" id="tshirt-sumki" like-name="ps_src" name="sumki" class="some-tshirt" title="{$L.GOOD_bags}"  rel="nofollow">&nbsp;</a></span>
				{*<span><a href="#" id="tshirt-futbolki-s-rukavom" like-name="ps_src" name="futbolki-s-rukavom" class="some-tshirt" title="{$L.GOOD_long_sleeved_tshirt}"  rel="nofollow">&nbsp;</a></span>*}
				<span><a href="#" id="tshirt-mayki-alkogolichki" like-name="ps_src" name="mayki-alkogolichki" class="some-tshirt" title="{$L.GOOD_mayka}"  rel="nofollow">&nbsp;</a></span>
				<span><a href="#" id="tshirt-mayki" like-name="ps_src" name="mayki" class="some-tshirt" title="{$L.GOOD_mayki}"  rel="nofollow">&nbsp;</a></span>
				<span><a href="#" id="tshirt-platya" like-name="ps_src" name="platya" class="some-tshirt" title="{$L.GOOD_dresses}"  rel="nofollow">&nbsp;</a></span>
				
				<!-- Детские носители -->
				<span><a href="#" id="tshirt-kid"   like-name="ps_src" name="kid"   class="some-tshirt" title="{$L.GOOD_kids_tshirts1}"  rel="nofollow"></a></span>
				<span><a href="#" id="tshirt-child" like-name="ps_src" name="child" class="some-tshirt" title="{$L.GOOD_kids_tshirts2}"  rel="nofollow"></a></span>
				<span><a href="#" id="tshirt-pupil" like-name="ps_src" name="pupil" class="some-tshirt" title="{$L.GOOD_kids_tshirts3}"  rel="nofollow"></a></span>
				
				<span><a href="#" id="tshirt-tolstovki" like-name="ps_src" name="tolstovki" class="some-tshirt" title="{$L.GOOD_tolstovki}"  rel="nofollow">&nbsp;</a></span>
			{/if}
			{if count($pics.as_oncar) > 0}
				<span><a href="#" id="tshirt-auto" like-name="as_oncar" name="auto" class="some-tshirt" title="{$L.GOOD_auto}"  rel="nofollow">&nbsp;</a></span>
			{/if}
			{if $pics.phones.id > 0}
				<span><a href="#" id="tshirt-phones" like-name="phones" name="phones" class="some-tshirt" title="{$L.GOOD_phones}"  rel="nofollow">&nbsp;</a></span>
			{/if}
			{if $pics.laptops.id > 0}
				<span><a href="#" id="tshirt-laptops" like-name="laptops" name="laptops" class="some-tshirt" title="{$L.GOOD_laptops}"  rel="nofollow">&nbsp;</a></span>
			{/if}
			{if $pics.touchpads.id > 0}
				<span><a href="#" id="tshirt-touchpads" like-name="touchpads" name="touchpads" class="some-tshirt" title="{$L.GOOD_touchpads}"  rel="nofollow">&nbsp;</a></span>
			{/if}
			{if $pics.ipodmp3.id > 0}
				<span><a href="#" id="tshirt-ipodmp3" like-name="ipodmp3" name="ipodmp3" class="some-tshirt" title="iPod"  rel="nofollow">&nbsp;</a></span>
			{/if}
			{if $pics.poster.id > 0}
				<span><a href="#" id="tshirt-posters" like-name="poster" name="poster" class="some-tshirt" title="{$L.GOOD_posters}"  rel="nofollow">&nbsp;</a></span>
			{/if}
			{if $pics.moto.id > 0}
				<span><a href="#" id="tshirt-moto" like-name="moto" name="moto" class="some-moto" title="{$L.GOOD_moto}"  rel="nofollow">&nbsp;</a></span>
			{/if}			
		</div>
	</form>
		
	{*if $good.good_description}
		<div class="OrderedText">
			{$good.good_description}
		</div>
	{/if*}	
	
   <div class="page-bottom">
		
		{if $MobilePageVersion}
			{if $also|count > 1}
				{include file="catalog/good.also.tpl"}				
			{/if}										
		{/if}		
		
		{if $sale || $MobilePageVersion}			
			{literal}
				<script type="text/javascript">
					$(document).ready(function(){/*вместо комментов первое*/
						$(".tabs-block_titles .tabs-link").removeClass('active-tab');
						$(".tabs-block_titles .tabs-link").each(function(){
							if ($(this).parent().css("display") != "none" && $(".tabs-block_titles .active-tab").length == 0) $(this).click();
						});
					});
				</script>
			{/literal}			
		{/if}
				
		{*<div style="clear:both;margin-bottom:17px"></div>*}
				
		<!-- Левый блок с вкладками -->
		<div class="tabs-block">
			<!--noindex-->
			{*
			<ul class="tabs-block_titles">
				<li class="tabs-tab tab-sizes"><a class="tabs-link"  href="#!/sizes" title="sizes"  rel="nofollow"><br replaces="{$L.GOOD_sizes}"/></a></li>
				<li class="tabs-tab tab-composition"><a class="tabs-link"  href="#!/composition" title="composition"  rel="nofollow"><br replaces="{$L.GOOD_sostav2}"/></a></li>
				<li class="tabs-tab tab-delivery"><a class="tabs-link {if $sale && $filters.category=='sumki'}active-tab{/if}"  href="#!/delivery" title="delivery"  rel="nofollow"><br replaces="{$L.GOOD_delivery}"/></a></li>
				<li class="tabs-tab tab-moneyback"><a class="tabs-link"  href="#!/moneyback{if $sale}-sale{/if}" title="moneyback"  rel="nofollow"><br replaces="{$L.GOOD_return_change2}"/></a></li>
				{if !$sale}				
					<li class="tabs-tab tab-comments"><a class="tabs-link"  href="#!/comments" title="comments" rel="nofollow"><br replaces="{$L.GOOD_comments2} ({$good.comments})"/></a></li>
					<li class="tabs-tab tab-reviews"><a class="tabs-link"  href="#!/reviews" title="reviews" rel="nofollow"><br replaces="Отзывы"/></a></li>
					{if $also|count > 1}
						<li class="tabs-tab tab-also"><a class="tabs-link active-tab"  href="#!/also" title="dostupno" rel="nofollow"><br replaces="{$L.GOOD_also}"/></a></li>
					{/if}
				{/if}
			</ul>
			
			<br clear="all">
			*}
			
			<ul class="also-tabs">
				<li class="tab-composition"><a class="tabs-link"  href="#!/composition" title="composition"  rel="nofollow"><br replaces="Качество"/></a></li>
				<li class="tab-delivery"><a class="tabs-link {if $sale && $filters.category=='sumki'}active-tab{/if}"  href="#!/delivery" title="delivery"  rel="nofollow"><br replaces="{$L.GOOD_delivery}"/></a></li>
				<li class="tab-payment"><a class="tabs-link"  href="#!/payment" title="payment"  rel="nofollow"><br replaces="Оплата"/></a></li>
				{if !$sale}				
					<li class="tab-reviews"><a class="tabs-link"  href="#!/reviews" title="reviews" rel="nofollow"><br replaces="Отзывы"/></a></li>
					<li class="tab-comments prev"><a class="tabs-link"  href="#!/comments" title="comments" rel="nofollow"><br replaces="{$L.GOOD_comments2} ({$good.comments})"/></a></li>
					{if $also|count > 1}
						<li class="tab-also active"><a class="tabs-link active-tab"  href="#!/also" title="dostupno" rel="nofollow"><br replaces="{$L.GOOD_also}"/></a></li>
					{/if}
				{/if}
				<li class="tab-moneyback"><a class="tabs-link"  href="#!/moneyback{if $sale}-sale{/if}" title="moneyback{*if $sale}-sale{/if*}"  rel="nofollow"><br replaces="{$L.GOOD_return_change2}"/></a></li>
			</ul>
			
			<!--/noindex-->
			
			<div class="tabs-block_content">
				<div id="tab-sizes" class="one-tab-content" title="{$L.GOOD_sizes}" style="display:none"></div>
				<div id="tab-composition" class="one-tab-content" title="{$L.GOOD_sostav}" style="display:none"></div>
				<div id="tab-delivery" class="one-tab-content" title="{$L.GOOD_delivery}" style="{if $sale && $filters.category=='sumki'}{else}display:none{/if}"></div>
				<div id="tab-payment" class="one-tab-content" title="Оплата" style="display:none"></div>
				<div id="tab-moneyback{*if $sale}-sale{/if*}" class="one-tab-content" title="Возврат денег" style="display:none"></div>
				{if !$sale}
				<div id="tab-comments" class="one-tab-content" title="Комментарии">
					<div class="b-one-comment auth_comment" style="background-color:#E5F6ED">
						<a class="b-one-comment_auth" href="/profile/{$good.user_id}/" title="{$good.user_login}" rel="nofollow">{$good.user_avatar}</a>
						<div class="b-comm_content">
							<div class="b-auth_link">
								<a class="a" href="/profile/{$good.user_id}/" title="{$good.user_login}" style="color:#00a851; text-decoration:underline" rel="nofollow">{$good.user_login}</a> {$user.user_designer_level}
								<font class="comment-date">{$good.good_date}</font>
							</div>
							<div class="comment-text">
							{if $good.good_description}
								{$good.good_description}<br />
							{/if}
							
							{if $good.good_status != 'voting' && $good.good_status != 'new'}
								<span style="font-size:10px;color:#999">{$L.GOOD_voting_finished}</span>
							{else}
								<a href="/voting/view/{$good.good_id}/" style="font-size:10px;color:#999" rel="nofollow">{$L.GOOD_vote}</a>
							{/if}
							</div>				
						</div>
					</div>
					<div class="comments-content">{include file="comments.tpl"}	</div>					
				</div>
				
				<div id="tab-reviews" class="one-tab-content" title="Отзывы" style="display:none">
					{*include file="catalog/good.reviews.tpl"*}							
				</div>
				
				{if $also|count > 1 && !$MobilePageVersion}
				<div id="tab-dostupno" class="one-tab-content" style="display:block">
					{include file="catalog/good.also.tpl"}
				</div>
				{/if}
			
				{*<div id="tab-recomend clearfix" class="one-tab-content" style="display:none">
					<!--  блок "Рекомендуем" --><!--noindex-->
					<div class="b-we-recomended">
						{if $temporaryNotice}            
						<div style="width:966px" class="clearfix clr" id="temporaryNotice">
							<p>{$L.GOOD_plagiat} <a href="#" id="temporaryClose" style="float:right" rel="nofollow">закрыть</a></p>
							<p class="clearfix clr">{$L.GOOD_glagiat_hint}<br />
							<a href="/feedback/?height={if $USER->authorized}430{else}550{/if}&width=300" class="showFeedback thickbox" rel="nofollow">{$L.GOOD_complain}</a>
							</p>
							<p>{$L.GOOD_plagiat_responsibility}</p>
							<p>{$L.GOOD_illegal_alert_1}</p>
							<ul>
								<li>{$L.GOOD_illegal_alert_2}</li>
								<li>{$L.GOOD_illegal_alert_3}</li>
								<li>{$L.GOOD_illegal_alert_4}</li>
								<li>{$L.GOOD_illegal_alert_5}</li>
							</ul>							
						</div>
						{/if}						
						<div id="recomendet-content"></div>					
					</div><!--/noindex-->
				</div>*}
			{/if}
			</div>							
			
		</div>
	</div>
</div>

{literal}
<script type="text/javascript">
	//$(document).ready(function(){
		$("br[replaces]").each(function(){
			$(this).replaceWith($(this).attr('replaces'));
		});		
	//});
</script>
{/literal}