<script type="text/javascript" src="/js/jquery.cookie.min.js"></script>
<script type="text/javascript" src="/js/2012/goods_script.pr.js"></script>
<script type="text/javascript" src="/js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="/js/cloud-zoom.1.0.2.js"></script>
<link rel="stylesheet" href="/css/catalog/good.tpl.css" type="text/css" media="all"/>
<link rel="stylesheet" href="/css/catalog/styles.css" type="text/css" media="all"/>

<style>
#loader-img { left:50%; top: 50%; }
.top-banner {ldelim}display:none{rdelim}
</style>
<div class="b-good-content">
	<!-- Заголовок с названием работы, тегами и ссылкой на автора -->
	<div class="b-titleblock">
		{if $good.place > 0}
		<a href="http://www.maryjane.ru/catalog/winners/" class="orden" rel="nofollow">&nbsp;</a>
		{/if}
		
		{if !$filters.category}
		<h1 title="{$good.good_name}" style="{if $good.place > 0}max-width:315px;{/if}">
			<div id="hide_title_end" style="display:none;position: absolute; height: 44px; width: 50px; background: url(&quot;/images/_tmp/title-right-hide.png&quot;) no-repeat scroll right 0 transparent; right:0; top:0; z-index: 90;"></div>
			<div class="inner-title-text" style="visibility:hidden;">{$good.good_name}</div>
		</h1> 
		{else}
		<h2 title="{$good.good_name}" style="{if $good.place > 0}max-width:315px;{/if}color:#737373; float: left;font-family: 'MyriadPro-CondIt',arial;font-size: 36px;font-style: normal;font-weight: normal;height: 44px;max-width: 370px;overflow: hidden; padding: 0 15px 0 0; position: relative;">
			<div id="hide_title_end" style="display:none;position: absolute; height: 44px; width: 50px; background: url(&quot;/images/_tmp/title-right-hide.png&quot;) no-repeat scroll right 0 transparent; right:0; top:0; z-index: 90;"></div>
			<div class="inner-title-text" style="visibility:hidden;">{$good.good_name}</div>
		</h2> 	
		{/if}
		
			<div class="author" style="white-space:nowrap">&mdash;&nbsp;автор&nbsp;<a class="auth-link" href="/catalog/{$good.user_login}/" rel="nofollow">{$good.user_login}</a>&nbsp; {$user.user_designer_level}
		</div>		
		<div class="tags" style="color:#CCCCCC;">
			<em>
				{if $USER->user_id == $good.user_id ||  $USER->user_id == 27278 ||  $USER->user_id == 6199 ||  $USER->user_id == 63250}
					<a href="/senddrawing.design/{$good.good_id}/" style="color:red">ред-ть</a>, 
				{/if}
				
				{foreach from=$good.tags name="tags" item="tag"}
					{if $tag.tag_ps_goods > 1}
						<a href="/tag/{$tag.slug}/" {if !$user && $filters.category}rel="nofollow"{/if} style="color:#ccc">{$tag.name}</a>
					{else}
						{$tag.name}
					{/if}
					{if !$smarty.foreach.tags.last}, {/if}
				{/foreach}
			</em>
		</div>
	</div>

	<!-- Блок с футболкой внизу галерея и кнопки из соц сетей -->
    <div class="big-imgbox">
		<!-- Добавить в избранное -->		
		<div class="vote {if $liked} select{/if}" no-count="{if $good.good_status != "voting"}1{else}0{/if}" like-url="/ajax/like/{$good.good_id}/ps_src/" ><span>{if $good.good_status != "voting"}{$favoriteCount}{/if}</span></div>
		<!-- Превьюшка -->		
		<div class="big-preview">
			<div id="loader-img"></div>
			
			<a id="voting-img-wrap" href="{if $good.big != ''}{$good.big}{else}/zoom/{$good.good_id}/{$good.bg}/?width=740{/if}" title="" style="text-align:center;background: #{$good.bg}" onclick="return false;" class='cloud-zoom voting-good-link' rel="position: 'inside' , showTitle: false, zoomWidth:'648', zoomHeight:'518'">
			<!--<a id="voting-img-wrap" href="{if $good.big != ''}{$good.big}{else}/zoom/{$good.good_id}/{$good.bg}/?width=740{/if}" title="" style="text-align:center;background: #{$good.bg}" onclick="return false;" >-->
				<img id="big-img" class="big-img-pos" src="/images/empty.gif" alt="" width="500" />
			</a>
			
			
			
		</div>
        <div class="big-imgbottom">
            <!-- Галерея под большим рисунком -->
            <div class="b-bottomgallery b-smlgallery-vertical">
				{if $photos}
                <a class="left-arr" href="#!/left"></a>
                <div class="gallery-wrap">
                    <div class="scroll-wrap">					
					{if $photos|count > 0}
						{foreach from=$photos item=photo}
							<a href="#" name="{$photo.picId}" class="o_good"  rel="nofollow"><img src="{$photo.picPath}" alt="Фотография футболки {$good.good_name}" height="48" /></a>
						{/foreach}
					{/if}					
					<!--a href="http://www.maryjane.ru/sendphoto/to/{$good.good_id}/" class="nofoto_sml" rel="nofollow" title="Добавьте ваше фото">					
						<img width="48" src="http://www.maryjane.ru/images/buttons/gallery-add-photo1.gif" style="padding-top:5px;" title="Добавьте фото, мы платим 100 руб." alt="Добавьте фото, мы платим 100 руб." />
					</a--> 
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

    </div> <!-- Окончание блока большой фотографии -->

	<!-- Правее блок с настройками размера/носителя/цвета и кнопка в корзину -->
<form action="" method="post" id="good_fomr">
	<div class="good-settings">
		<div class="b-radio-manwomen">
			<div class="radio-input selected-man" id="input_man_woman">
				<a class="type-select" id="male" href="#!/select-man"  rel="nofollow"></a>
				<a class="type-select" id="female" href="#!/select-woman"  rel="nofollow"></a>
				<a class="type-select" id="kids" href="#!/select-kids"  rel="nofollow"></a>
				<input id="good_gender" type="hidden" name="gender" value="">
			</div>
			<!-- a class="link anchor" name="tab-delivery" href="#" >Доставка</a -->
		</div>
		<!-- Добавить в избранное -->		
		
		<div class="b-2favorite" style="visibility:hidden;">
			<div class="votFavorite" id="wtb-outer">
				{if !$good_add_to_selected} 
					<div class="del_selected6" id="favor-star" style="cursor: pointer;" name="22271"></div>
				{else} 
					<div class="selected6" id="favor-star" style="cursor: pointer;" name="22271"></div>
				{/if}				
				<span class="favoriteCount"><i></i>{$favoriteCount}</span>
			</div>
		</div>
		
		{if !$sale}	
		<div class="b-smlgallery">
			<a rel="nofollow" href="#!/all-t-shirts" class="btn-switch-gall one-photo-view" id="all_t-shirs-popup">Все сразу<span class="all-gall-hint"> </span></a>
		</div>
		{/if}
		
        <div class="left-part">
			
			<div class="tshirt-name">
				<h3><span id="style_name" class="style_name">Футболка мужская Regular</span>. <a href="#" class="anchor" name="tab-sizes"  rel="nofollow">Таблица размеров</a></h3>
				{* <span>Прямая цифровая печать. Ручная стирка.</span> *} 
			</div>

			<!-- Выбор размера -->			
			<div class="select-size-box">
				<div class="error-order" id="size-error">Выберите размер!</div>
				<input id="good_sizes" type="hidden" name="size" value="">
			</div>
			
			
			<!-- Выбор цвета -->
			<div class="b-color-select">
				<div class="error-order" id="color-error">Выберите цвет!</div>
				<input id="good_color" type="hidden" name="color" value="">
			</div>
			
			<a href="#!/show-delivery-calc" id="show-calc" style="visibility:hidden; background-color:#FFF" class="show-calc" rel="nofollow"><span>Калькулятор доставки</span></a>
			
			{if $USER->authorized && $USER->user_delivered_orders >= 1}
			
				<div id="one_click_order_block">
					<a href="/order/step/confirm/{$basket->id}/" id="one_click_order" rel="nofollow"  style=" white-space:nowrap">Купить быстро в 1 клик</a>
					<!--span class="help2 clr help_size" id="one_click_order_help" style="color:#FFF;"><a rel="nofollow" href="/faq/142/?TB_iframe=true&amp;height=500&amp;width=600" class="thickbox" style="font-size: 9px;color:#FFF; border-color:#FFF">?</a></span-->
				</div>
				
			{elseif $USER->user_delivered_orders == 0}
				
				<div id="one_click_order_block">
					<a href="#" id="one_step_order" rel="nofollow">Купить в 1 шаг</a>
					<!--span class="help2 clr help_size" id="one_click_order_help" style="color:#FFF;"><a rel="nofollow" href="/faq/141/?TB_iframe=true&amp;height=500&amp;width=600" class="thickbox" style="font-size: 9px;color:#FFF; border-color:#FFF">?</a></span-->
				</div>
				
			{/if}
			
			{if $sale}
				<div class="b-good-price-submit" style="background:url('/images/catalog/sale-bg.jpg') no-repeat;width:378px;height:92px;right:11px;top:236px;">
					<div style="position:relative;width:100%;height:100%;">
						<a id="other_tshirt_sale" href="/sale/sex,female;size,m/" style="color:white;position:absolute;top:12px;right:12px;">Остальные футболки SALE, размер 3XL</a>
						<div style="position:absolute;right:10px;bottom:5px;">
							<div class="good-price">
								<div class="current-price"  style="color:white;float:right;width:102px;">--- руб.</div>
								<div class="old-price" style="color:white;float:right;">--</div>
							</div>
							
							<input type="submit" id="submit" style="height:38px;" title="В корзину" value="" class="not_select_size" id="to_basket"> <!--  onclick="return checkAddToCartForm();" -->
							
							<a class="success_message" href="http://www.maryjane.ru/basket" id="go2basket"  rel="nofollow">В корзине:  +1</a>
							<div style="clear:both;"></div>
						</div>
					</div>
				</div>
			{else}
				<div class="b-good-price-submit">
					<div class="good-price">
						<div class="old-price">--</div>
						<div class="current-price">--- руб.</div>
					</div>
					
					<input type="submit" id="submit" title="В корзину" value="" class="not_select_size" id="to_basket"> <!--  onclick="return checkAddToCartForm();" -->
					
					<a class="success_message" href="http://www.maryjane.ru/basket" id="go2basket"  rel="nofollow">В корзине:  +1</a>
				</div>			
			{/if}
				<div class="price-hint">
					<span class="price_back_holder">Вам вернется <span id="good_price_back">0</span> руб. на следующий заказ </span>
					<!--span class="help2 clr help_size"><a class="thickbox" rel="nofollow" href="http://www.maryjane.ru/faq/group,13/?TB_iframe=true&amp;height=500&amp;width=600" style="font-size: 9px;line-height:35px">?</a></span-->
				</div>
			{if $good.good_discount <= 5}
			<div class="b-good-discount">
				<div class="y-dics">скидка 5%</div>
				<div class="wb-disc">скидка 5%</div>				
			</div>
			{/if}
        </div>
        <div class="b-tshirttype">
			<input type="hidden" id="good_type" name="type" value="" />
			<a href="#" id="tshirt-currrent" class="some-tshirt active-tshirt-type" style="display:none;" title="" rel="nofollow"><i style="margin:-55px -10px;"></i>Футболка</a>
			<a href="#" id="tshirt-futbolki" name="futbolki" class="some-tshirt" title="Футболка" style="display:none;"  rel="nofollow">Футболка</a>
			<a href="#" id="tshirt-tolstovki" name="tolstovki" class="some-tshirt active-tshirt-type" title="Толстовка" style="display:none;"  rel="nofollow">Толстовка</a>
			<a href="#" id="tshirt-futbolki-s-rukavom" name="futbolki-s-rukavom" class="some-tshirt" title="Футболка с рукавом" style="display:none;"  rel="nofollow">Футболка с рукавом</a>			
			<a href="#" id="tshirt-mayki-alkogolichki" name="mayki-alkogolichki" class="some-tshirt" title="Майка" style="display:none;"  rel="nofollow">Майка</a>
			
			<!-- Детские носители -->
			<a href="#" id="tshirt-kid"   name="kid"   class="some-tshirt" title="Футболки для детей от рождения и до 1-го года" style="display:none;"  rel="nofollow">1-12 мес.</a>
			<a href="#" id="tshirt-child" name="child" class="some-tshirt" title="Футболки для детей от 1 до 9 лет" style="display:none;"  rel="nofollow">1-9 лет</a>
			<a href="#" id="tshirt-pupil" name="pupil" class="some-tshirt" title="Футболки для детей от 10 до 16 лет" style="display:none;"  rel="nofollow">10-16 лет</a>
			
			<a href="#" id="tshirt-sumki" name="sumki" class="some-tshirt active-tshirt-type" title="Сумки" style="display:none;"  rel="nofollow">Сумки</a>
			<a href="#" id="tshirt-platya" name="platya" class="some-tshirt" title="Сумки" style="display:none;"  rel="nofollow">Платье</a>
        </div>

	</div>
</form>
	
   <div class="page-bottom">

		<!-- Левый блок с вкладками -->
	   <div class="tabs-block" style="margin-top:-5px;">
			<ul class="tabs-block_titles">
				<li class="tabs-tab tab-sizes"><a class="tabs-link"  href="#!/sizes" title="sizes"  rel="nofollow">Размеры</a></li>
				<li class="tabs-tab"><a class="tabs-link"  href="#!/composition" title="composition"  rel="nofollow">Состав и Уход</a></li>
				<li class="tabs-tab tab-delivery"><a class="tabs-link"  href="#!/delivery" title="delivery"  rel="nofollow">Доставка</a></li>
				<li class="tabs-tab"><a class="tabs-link"  href="#!/moneyback" title="moneyback"  rel="nofollow">Возврат/Обмен</a></li>
				<li class="tabs-tab"><a class="tabs-link active-tab"  href="#!/comments" title="comments" rel="nofollow">Комментариев ({$good.comments})</a></li>
			</ul>
		<div class="tabs-block_content">
			<div id="tab-sizes" class="one-tab-content" title="Размеры" style="display:none;"></div>
			<div id="tab-composition" class="one-tab-content" title="Состав" style="display:none;"></div>
			<div id="tab-delivery" class="one-tab-content" title="Доставка" style="display:none;"></div>
			<div id="tab-moneyback" class="one-tab-content" title="Взврат денег" style="display:none;"></div>
			<div id="tab-comments" class="one-tab-content" title="Комментарии" style="display:block; margin-top:-22px; background:url('http://printshop.maryjane.ru/img/reborn/thickbox/loading.gif') no-repeat scroll 50% 105px transparent;">
				<div class="b-one-comment auth_comment" style=" background-color:#E5F6ED">
					<a class="b-one-comment_auth" href="/profile/{$good.user_id}/" title="{$good.user_login}"  rel="nofollow">{$good.user_avatar}</a>
					<div class="b-comm_content">
						<div class="b-auth_link">
							<a class="a" href="/profile/{$good.user_id}/" title="{$good.user_login}" style="color:#00a851; text-decoration:underline" rel="nofollow">{$good.user_login}</a> {$user.user_designer_level}
							<em class="comment-date">{$good.good_date}</em>
						</div>
						<div class="comment-text">
						{if $good.good_description}
							{$good.good_description} <br />
						{/if}
						
						{if $good.good_vote_visible == '1' && $good.good_src_picture > 0}
						<a href="/voting/view/{$good.good_id}/" style="font-size:10px; color:#999">голосование за работу завершено</a>
						{/if}
						</div>				
					</div>
				</div>
				<div class="comments-content"></div>
				
			</div>
		</div>		
	   </div>

	
	<!-- Правый блок "Рекомендуем" -->
        <div class="b-we-recomended">
            
            {if $temporaryNotice}            
			<div style="" class="clearfix clr" id="temporaryNotice">				
				<p>СООБЩИТЬ О НАРУШЕНИИ	<a href="#" id="temporaryClose" style="float:right">закрыть</a></p>
				<p class="clearfix clr">Друзья, просим вас сообщать нам о всех фактах нарушения авторских прав, как по размещению работ наших авторов, на других сайтах, так и о работах размещённых на сайте maryjane.ru, противоречащих законодательству РФ.<br />
				<a href="/feedback/?height={if $USER->authorized}430{else}550{/if}&width=300" class="showFeedback thickbox">Пожаловаться</a>
				</p>
				<p>ОТВЕТСТВЕННОСТЬ ЗА<br />
				НАРУШЕНИЕ АВТОРСКИХ ПРАВ</p>
				<p>За незаконное использование произведений или их частей виновные лица несут ответственность, предусмотренную действующим гражданским, административным и уголовным законодательством, в частности, в отношении них могут быть применены:
				<ul>
				<li>Меры уголовного воздействия (до 6 лет лишения свободы в соответствии со статьей 146 УК РФ );</li>
				<li>Взыскание компенсации за нарушение авторских прав в размере до 5 миллионов рублей за каждое допущенное нарушение (статья 1301 ГК РФ );</li>
				<li>Ликвидация юридического лица за неоднократные нарушения авторских прав (статья 1253 ГК РФ );</li>
				<li>Конфискация оборудования и любых устройств и материалов, используемых для нарушений авторских прав (пункт 5 статьи 1252 ГК РФ ).</li>
				</ul>
				</p>
			</div>
			{/if}
            
             <h3>Рекомендуем:</h3>
            <div id="recomendet-content"></div>
			
        </div>
	</div>
</div>


{literal}

<script type="text/javascript">				
var design = '';
var good_id = 0;
	design = {/literal}{$jstyles}{literal};
	good_id = {/literal}{$good.good_id}{literal};
	good_comments = '{/literal}{$good.comments}{literal}';
var	default_sex = '{/literal}{$default.sex}{literal}';
	default_category = '{/literal}{$default.category}{literal}';
	default_color = '{/literal}{$default.color}{literal}';
</script>

{/literal}