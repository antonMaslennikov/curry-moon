<script type="text/javascript" src="/js/cloud-zoom.1.0.2_v3.js"></script>
<link rel="stylesheet" href="/css/catalog/good.tpl.css" type="text/css" media="all"/>


<script type="text/javascript" src="/{$module}/styles/{$good.good_id}/{if $style}{$style->id}/{/if}zoom/"></script>

<script type="text/javascript">				
var	default_sex = '{$default.sex}';
	default_category = '{$default.category}';
	default_color = '{$default.color}';
	default_size = '{$default.size}';
	default_good_name = '{$good.good_name}';
	default_new_id = '{$style->id}',
	//default_new_slug = '{$style->slug}',
	default_new_slug = '{$style->style_slug}',
	default_new_category = '{$style->category}';

var likes = {
	 count: '{$good.good_likes}', liked:{ {foreach from=$mylikes item=item key=k name=i}{$k}:{$item}{if !$smarty.foreach.i.last},{/if}{/foreach} }, likeUrl: '/ajax/like/{$good.good_id}/ps_src/'
}		
</script>

<script type="text/javascript" src="/min/?f=/js/p/catalog.good.js&v14"></script>

<script type="text/javascript" src="/js/2012/auto.js"></script>

{literal}
<style>
	_html { overflow: hidden; }
	#loader-img { left:50%; top: 50%; }
	.b-good-content {width:908px;height: 512px;margin: 0;    overflow: hidden;}
</style>
{/literal}
	
	<div class="b-good-content {$PAGE->lang} {if $MobilePageVersion && ($USER->user_id == 105091 || $USER->user_id == 6199)}zoom-window-mobile{/if}">
	
		<div class="big-imgbottom">
			<!-- Галерея под большим рисунком -->
			<div class="b-bottomgallery b-smlgallery-vertical" style="left:34px">
				{if $style_photos || count($pics.as_oncar) > 0 || count($pics.touchpads) > 0 || count($pics.laptops) > 0}
			   <a class="left-arr" href="#!/left"></a>
			   <div class="gallery-wrap">
				<div class="scroll-wrap">					
					<a href="#" class="o_good origin active_good" rel="nofollow" style="display:block"><img src="/images/empty.gif" alt="{$L.GOOD_tshirt_photo} {$good.good_name}" height="48" /></a>
					{if $photos|count > 0}
						{foreach from=$photos item=photo}
							<a href="#" name="{$photo.picId}" class="o_good" like-name="ps_src" category="futbolki,tolstovki,mayki-alkogolichki" rel="nofollow"><img src="{$photo.picPath}" alt="{$L.GOOD_tshirt_photo} {$good.good_name}" height="48" /></a>
						{/foreach}
					{/if}
					
					{if count($pics.as_oncar) > 0}
						{foreach from=$pics.as_oncar item="p" key="k"}
							<a href="#" name="{$p.id}" class="o_good" like-name="as_oncar" _src="{$p.path}" {if $k == 10}_noresize="true"{/if} style="display:none" category="auto" rel="nofollow"><img src="{$p.preview}" alt="#" height="48" /></a>
						{/foreach}
					{/if}
					
					{if count($style_photos) > 0}
						{foreach from=$style_photos item="style" key="style_id"}
							{foreach from=$style item="p1" key="k1"}
								<a href="#" name="" class="o_good" like-name="ps_src" style_id="{$style_id}" _src="{$p1.big}" rel="nofollow"><img src="{$p1.small}" alt="Фотография футболки {$good.good_name}" height="48" /></a>
							{/foreach}
						{/foreach}
					{/if}
					{if count($pics.postcards) > 0}
						{foreach from=$pics.postcards item=p key=k}
							<a href="#" name="{$p.id}" class="o_good" like-name="postcards" _src="{$p.path}" style="display:none" category="postcards" rel="nofollow"><img src="{$p.preview}" alt="#" height="48" /></a>
						{/foreach}
					{/if}
					{* постеры *}
					{if count($pics.photoprint) > 0}
						{foreach from=$pics.photoprint item=p key=k}
							<a href="#" name="{$p.id}" class="o_good" like-name="poster" _src="{$p.path}" style="display:none" category="poster" rel="nofollow"><img src="{$p.preview}" alt="#" height="48" /></a>
						{/foreach}
					{/if}
					{if count($pics.printoncanvas) > 0}
						{foreach from=$pics.printoncanvas item=p key=k}
							<a href="#" name="{$p.id}" class="o_good" like-name="poster" _src="{$p.path}" style="display:none;" category="poster" rel="nofollow"><img src="{$p.preview}" alt="#" height="48" /></a>
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
				<a href="http://www.maryjane.ru/sendphoto/to/{$good.good_id}/" class="nofoto_sml" rel="nofollow" title="Добавьте ваше фото">					
					<!--img width="48" src="http://www.maryjane.ru/images/buttons/gallery-add-photo.gif" style="padding-top:5px;" title="Добавьте фото, мы платим 100 руб." alt="Добавьте фото, мы платим 100 руб." /-->
				</a>
				<!--/noindex-->
				{/if}						
			</div>
		</div>	
	
	<!-- Блок с футболкой внизу галерея и кнопки из соц сетей -->
    <div class="big-imgbox">
		
		<!-- Добавить в избранное -->		
		<div class="vote {if $mylikes.ps_src} select{/if} {if $good.place > 0} heart_red {/if}" liked="{$mylikes.ps_src}" likes="{$good.good_likes}{*$likes.ps_src*}"><span>{*$likes.ps_src*}{$good.good_likes}</span></div>		
		


		<!-- Превьюшка -->		
		<div class="big-preview">
			<div id="loader-img"></div>			
			<a id="voting-img-wrap" href="{if $good.big != ''}{$good.big}{else}/zoom/{$good.good_id}/{$good.bg}/?width=740{/if}" title="" style="text-align:center;background: #{$good.bg}" onclick="return false;" class='cloud-zoom voting-good-link' rel="position:'inside',showTitle:false,zoomWidth:'648',zoomHeight:'518'">
			<!--<a id="voting-img-wrap" href="{if $good.big != ''}{$good.big}{else}/zoom/{$good.good_id}/{$good.bg}/?width=740{/if}" title="" style="text-align:center;background: #{$good.bg}" onclick="return false;" >-->
				<img id="big-img" class="big-img-pos" src="/images/empty.gif" alt="" width="500" />
			</a>			
		</div>

    </div> <!-- Окончание блока большой фотографии -->

	<!-- Правее блок с настройками размера/носителя/цвета и кнопка в корзину -->
	<form action="" method="post" id="good_fomr" zoom="true">
		<div class="good-settings">
			<div class="good-properties" style="height:auto">
				
				<div class="buy_quickly_vote clearfix">							
					<div class="vote-small {if $mylikes.ps_src} select{/if} {if $good.place > 0} heart_red{/if}" title="">
						<span>В избранно{if $mylikes.ps_src}м{else}е{/if}</span> (<font>{$good.good_likes}</font>)
					</div>
					
					<div id="one_click_order_block">
						<a href="/order.v3/" rel="nofollow">{$L.GOOD_buy_quickly}</a>
						<!--span class="help2 clr help_size" id="one_click_order_help" style="color:#FFF;"><a rel="nofollow" href="/faq/142/?TB_iframe=true&amp;height=500&amp;width=600" class="thickbox" style="font-size: 9px;color:#FFF; border-color:#FFF">?</a></span-->
					</div>						
				</div>		
				
				
				<div class="b-radio-manwomen">
					<div class="radio-input selected-man" id="input_man_woman">
						<a class="type-select" id="male" href="#!/select-man"  rel="nofollow"></a>
						<a class="type-select" id="female" href="#!/select-woman"  rel="nofollow"></a>
						<a class="type-select" id="kids" href="#!/select-kids"  rel="nofollow"></a>
						<input id="good_gender" type="hidden" name="gender" value="">
					</div>
					<!-- a class="link anchor" name="tab-delivery" href="#" >Доставка</a -->
				</div>
			
				<div class="good-parts">
					<div class="good-tpl good-futbolki" tpl="futbolki,tolstovki,mayki-alkogolichki,kid,child,pupil,platya" manwomen="yes" >
						{include file="catalog/good.t-shirt.tpl"}
					</div>
					<div class="good-tpl good-sumki" tpl="sumki" manwomen="no" style="display:none;">
						{include file="catalog/good.t-sumki.tpl"}
					</div>
					<div class="good-tpl good-auto" tpl="auto" manwomen="no" style="display:none;">
						{include file="catalog/good.t-auto.tpl"}
						{include file="catalog/good.t-auto-monochrome.tpl"}
					</div>
					<div class="good-tpl good-phones" tpl="phones" manwomen="no" style="display:none;">
						{include file="catalog/good.t-phones.tpl"}
					</div>
					<div class="good-tpl good-laptops" tpl="laptops" manwomen="no" style="display:none;">
						{include file="catalog/good.t-laptops.tpl"}
					</div>
					<div class="good-tpl good-touchpads" tpl="touchpads" manwomen="no" style="display:none;">
						{include file="catalog/good.t-touchpads.tpl"}
					</div>
					<div class="good-tpl good-postcards" tpl="postcards" manwomen="no" style="display:none;">
						{include file="catalog/good.t-postcards.tpl"}
					</div>
					<div class="good-tpl good-ipodmp3" tpl="ipodmp3" manwomen="no" style="display:none;">
						{include file="catalog/good.t-ipodmp3.tpl"}
					</div>
					<div class="good-tpl good-poster" tpl="poster" manwomen="no" style="display:none">
						{include file="catalog/good.t-posters.tpl"}
					</div>		
					<div class="good-tpl good-cup" tpl="cup" manwomen="no" style="display:none">
						{include file="catalog/good.cup.tpl"}
					</div>		
					<div class="good-tpl good-bag" tpl="cup" manwomen="no" style="display:none">
						{include file="catalog/good.bag.tpl"}
					</div>	
					<div class="good-tpl good-textile" tpl="textile" manwomen="no" style="display:none">
						{include file="catalog/good.textile.tpl"}
					</div>
					<div style="clear:both"></div>
				</div>
			</div>
			
			<div class="price-order {$PAGE->lang}" lockedCity="{if $USER->city && $USER->city != 'Москва'}true{/if}" city="{$USER->city}">
				
				<div class="predzakaz clearfix" style="display:none">

					<div class="form">
						<div class="text_email">Укажите Ваш Е-mail, что бы узнать о поступлении товара на склад первым*</div>
						<div class="text_yes" style="display:none">Cпасибо, Ваш голос учтен.</div>
						<input type="text" name="" class="predzakaz-input" placeholder="Email" _placeholder="" value="{$USER->user_email}">
						<input type="submit" value="" class="predzakaz-submit">
						<div class="text_email2">* Предзаказ предназначен для сбора потребительских предпочтений.<br/>Это не обещание поступления на склад выбраного Вами носителя.</div>
					</div>
				</div>
				
				<div class="b-good-price-submit">
					
					<div class="b-good-price-submit-wrap">
						<input type="submit" id="submit" title="{$L.GOOD_2_basket}" value="{if $PAGE->lang == "ru"}Купить{else}Buy{/if}" class="not_select_size addToBasketTrackGtagmanager"> <!--  onclick="return checkAddToCartForm();" -->
					</div>
					
					{if $MobilePageVersion && ($USER->user_id == 105091 || $USER->user_id == 6199)}					
						<a class="fake-submit" href="#" title="В корзину" rel="nofollow"></a>
					{/if}			
					
					<div class="good-price">
						<div class="old-price">{*--*}</div>
						<div class="current-price"><p>цена:</p> <span>{*---*}</span> <br replaces="{if $default.category=='textile'}руб.{else}{$L.CURRENT_CURRENCY}{/if}"/></div>
					</div>
					<a class="success_message" target="_blank" href="http://www.maryjane.ru/basket" id="go2basket"  rel="nofollow">В корзине:  +1</a>
				</div>			
				
				
				<div class="m23">
					<div class="price-hint">
						{* <span class="price_back_holder">Вам вернется <span id="good_price_back">0</span> руб.</span> *}
						<!--span class="help2 clr help_size"><a class="thickbox" rel="nofollow" href="http://www.maryjane.ru/faq/group,13/?TB_iframe=true&amp;height=500&amp;width=600" style="font-size: 9px;line-height:35px">?</a></span-->
					</div>					
					<div style="clear:both"></div>
				</div>
				
				<div style="clear:both"></div>
			</div><!--price-order -->
			
			<!--noindex-->
			<ul class="b-good-calc-deliv clearfix V2 {if $USER->city != 'Москва'}notMoskov{/if}">
				<li class="g">
					<br/>
					<span>Гарантия 1 год</span>
				</li>								
				<li class="d">
					<br/>
					<span class="txt">{$USER->city}. Доставим {if $deliver_srok && $USER->city == 'Москва'}{$deliver_srok}{else} в течении двух недель{/if}</span>
				</li>	
				<li class="m">
					<br/>
					<span>Обмен 365 дней</span>
				</li>				
			</ul><!--/noindex-->			
			
		</div>
	</form>	
	<div id="tab-sizes" class="one-tab-content" {*type="box"*} onclick="$(this).hide();" title="Размеры" style="display:none;position:absolute;z-index:101;background-color:#fff;min-height:525px"></div>
