{literal}
<!-- catalog-fittingroom-tolstovki.html -->
<style type="text/css">
.top-banner {display:none}
#apokalypsis-basnner {display:none;}
.loader-zoom-img {height:20px; padding:15px 0; top:220px; }
.tolstovki-page .select-size-box .one-size {font-size: 12px; padding:7px 1px 4px; border-color:#D5D7D7;}
.primary-color .select-size-box {margin-top:0;}
.b-zoom-popup {background-color: #EFEFEF; border-radius:4px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.25); height:438px; left: 0; overflow: hidden; padding: 20px; position: absolute; top: 0; width: 425px; z-index: 1000;}
.b-zoom-popup #close_btn {cursor: pointer; position: absolute; right: 3px; top: 4px;}
.b-good-foto .b-foto_center {overflow:visible;}
#sale-2012-banner {display:none;}
.tolstovki-page .b-titleblock {height:50px;}
.btn-switch-gall {background: url("/images/buttons/btn-switch-gall-grey.gif") no-repeat scroll 0 0 transparent; width:104px; height: 31px;}
.btn-switch-gall.many-photo-view {background-position:0 -31px;}
.c-loader-img {background:url("/images/buttons/ajax-loader-img.gif") no-repeat scroll 50% 50% transparent;}

.b-top-sett { width: auto; }
.b-top-sett .select-size-box { width:auto; min-width: auto; height:auto;margin-left:18px; }
.b-top-sett .b-color-select { float:left; margin:5px 0px 0px 10px !important;padding:0px;}

.b-good-price-submit .price-hint { float: none; width: auto; margin: 0 0 10px; text-align: right; }
#one_click_order, #one_step_order { padding: 6px 0px 5px 20px; }

.b-good-price-submit .good-price { width:180px; }
.good-price .current-price { text-align: right; width:120px; }
.good-price .old-price { text-align: right; color: #ddddde; }

.b-good-bottom.tolstovki_v2 .b-good-price .b-good-nametype {
	padding: 0 18px 0 256px;
	width: 200px;
}

.btn-switch-gall .all-gall-hint {
	background: url(/images/buttons/all-gall-hint-right.gif) no-repeat 0 0;
	bottom: -9px;
	right: -118px;
}
</style>
{/literal}

<script type="text/javascript" src="/js/jquery.cookie.min.js"></script>
<script type="text/javascript" src="/js/des2011/goods_script.js"></script>	<!-- Скрипт для работы с формой товара -->
<script type="text/javascript" src="/js/cloud-zoom.1.0.2.js"></script>
<!-- script type="text/javascript" src="/js/cloud-zoom.1.0.2.js"></script -->

<div class="b-good-content tolstovki-page primary-color">
	<div class="top-des-block">
		<!-- Заголовок с названием работы, тегами и ссылкой на автора -->
		<div class="b-titleblock" style="height:95px;width:618px">
			
			<h1 style="width:100%;height:45px">
				<span id="style_name" style="text-decoration:none">
	            	{if $filters.category == "zakazat_futbolku"}
						Футболки на заказ
					{elseif $filters.category == "futbolki"}
						Купить дизайнерские футболки
					{elseif $filters.category == "mayki-alkogolichki"}
						Майки алкоголички / борцовки
					{elseif $filters.category == "tolstovki"}
						Купить толстовки с капюшоном
					{elseif $filters.category == "kid"}
						Детская футболка
					{elseif $filters.category == "child"}
						Детская футболка
					{elseif $filters.category == "pupil"}
						Детская футболка
                    {elseif $filters.category == "sumki"}
                     	Сумка через плечо
                    {elseif $filters.category == "platya"}
                     	Платья-футболки
                    {elseif $filters.category == "mayki"}
                     	Платья-футболки
					{/if}
					
					<span class="h1_sex" id="h1_sex_male" style="display: {if $filters.sex == 'male' || !$filters.sex} block {else} none {/if}">мужские</span>
					<span class="h1_sex" id="h1_sex_female" style="display: {if $filters.sex == 'female'} block {else} none {/if}">женские</span>
				</span>
			</h1> 			
            <div class="b-top-sett">
				<!-- Кнопки выбора пола -->
				<div id="input_man_woman" class="b-radio-manwomen radio-input selected-woman" >
					<a rel="nofollow" href="#!/select-man" 	 id="male" class="type-select"></a>
					<a rel="nofollow" href="#!/select-woman" id="female" class="type-select"></a>
					<a rel="nofollow" href="#!/select-kids"  id="kids" class="type-select"></a>
					<input type="hidden" value="" name="gender" id="good_gender">
				</div>
				<!-- Различные размеры носителей -->
				<div class="select-size-box">
					<input type="hidden" value="" name="size" id="good_sizes">					
				</div>	
				<!-- Различные цвета в зависимости от размера -->			
				<div class="b-color-select">
					<input type="hidden" value="76" name="color" id="good_color">			
				</div>
				
			</div>			
		</div>		
		
		<!-- Галерея вверху и справа -->
		
		<div class="b-good-price-submit" style="width:350px;margin-top:30px;background:none">

			<div class="price-hint" style="visibility: visible;">
				<span class="price_back_holder">Вам вернется 65 руб. на следующий заказ</span>
			</div>
		
			<div class="good-price">
				<div class="old-price">-</div>
				<div class="current-price">--- руб.</div>
			</div>

			{if $user_orders >= 1}
			
				<a href="/order/confirm/{$basket->id}/" id="one_click_order" rel="nofollow">
					<div id="one_click_order_block" style="width:150px; height:20px; margin-top:-6px;background-color:#00a851; font-size:18px; text-decoration:underline;font-style:italic;padding: 7px 5px 13px 5px; text-align:center">
					Купить в 1 клик
					<!--span class="help2 clr help_size" id="one_click_order_help"><a rel="nofollow" href="http://www.maryjane.ru/faq/142/?TB_iframe=true&amp;height=500&amp;width=600" class="thickbox" style="font-size: 9px;color:#FFF; border-color:#FFF">?</a></span-->
					</div>		
				</a>
					
			{elseif $user_orders == 0}
			
				<a href="#" id="one_step_order" rel="nofollow" style="width:150px; height:20px;padding: 7px 5px 13px 5px; font-size:18px; text-decoration:underline;font-style:italic;background-color:#00a851;display:block;float:left">
					<div id="one_click_order_block" style="margin-top:23px;background-color:#00a851;text-align: center">
					Купить в 1 шаг
					<!--span class="help2 clr help_size" id="one_click_order_help"><a rel="nofollow" href="http://www.maryjane.ru/faq/141/?TB_iframe=true&amp;height=500&amp;width=600" class="thickbox" style="font-size: 9px;color:#FFF; border-color:#FFF">?</a></span-->
					</div>
				</a>
				
			{/if}
			
		</div>
	</div>
	
			

	<form action="" method="post" id="good_form">	

	<div class="b-good-foto">
		<div class="b-good-topset">			
			<div class="b-top-settings">
								
			</div>
		</div>
		{if $filters.category == "futbolki"}
		<a "#!/" class="b-foto b-foto_left" style="background:url(/images/_tmp/bg-mayki-left.jpg) no-repeat 50%;"  rel="nofollow">			
			<img height="476" align="right" alt="" src="/images/empty.gif" id="big-img-prev" style="display: block; visibility:hidden;">
		</a>
		{elseif $filters.category == "zakazat_futbolku"}
		<a "#!/" class="b-foto b-foto_left" style="background:url(/images/_tmp/bg-mayki-left.jpg) no-repeat 50%;"  rel="nofollow">			
			<img height="476" align="right" alt="" src="/images/empty.gif" id="big-img-prev" style="display: block; visibility:hidden;">
		</a>
		{elseif $filters.category == "mayki-alkogolichki"}
		<a "#!/" class="b-foto b-foto_left" style="background:url(/images/_tmp/bg-mayki-left.jpg) no-repeat 50%;"  rel="nofollow">			
			<img height="476" align="right" alt="" src="/images/empty.gif" id="big-img-prev" style="display: block; visibility:hidden;">
		</a>
		{elseif $filters.category == "tolstovki"}
		<a "#!/" class="b-foto b-foto_left" style="background:url(/images/_tmp/bg-tolstovki-left.jpg) no-repeat 50%;"  rel="nofollow">			
			<img height="476" align="right" alt="" src="/images/empty.gif" id="big-img-prev" style="display: block; visibility:hidden;">
		</a>
		{elseif $filters.category == "kid"}
		<a "#!/" class="b-foto b-foto_left" style="background:url(/images/_tmp/bg-mayki-left.jpg) no-repeat 50%;"  rel="nofollow">			
			<img height="476" align="right" alt="" src="/images/empty.gif" id="big-img-prev" style="display: block; visibility:hidden;">
		</a>
		{elseif $filters.category == "pupil" ||  $filters.category == "child"}
		<a "#!/" class="b-foto b-foto_left" style="background:url(/images/_tmp/bg-mayki-left.jpg) no-repeat 50%;"  rel="nofollow">			
			<img height="476" align="right" alt="" src="/images/empty.gif" id="big-img-prev" style="display: block; visibility:hidden;">
		</a>
		{elseif $filters.category == "sumki"}
		<a href="#!/" class="b-foto b-foto_left" style="background:url(/images/_tmp/bg-sumki-left.jpg) no-repeat 50%;"  rel="nofollow">			
			<img height="476" align="right" alt="" src="/images/empty.gif" id="big-img-prev" style="display: block; visibility:hidden;">
		</a>
		{elseif $filters.category == "platya"}
		<a href="#!/" class="b-foto b-foto_left" style="background:url(/images/_tmp/bg-platye-left.jpg) no-repeat 50%;"  rel="nofollow">			
			<img height="476" align="right" alt="" src="/images/empty.gif" id="big-img-prev" style="display: block; visibility:hidden;">
		</a>
		{elseif $filters.category == "mayki"}
		<a href="#!/" class="b-foto b-foto_left" style="background:url(/images/_tmp/bg-maika2-left.jpg) no-repeat 50%;"  rel="nofollow">			
			<img height="476" align="right" alt="" src="/images/empty.gif" id="big-img-prev" style="display: block; visibility:hidden;">
		</a>
		{/if}
		
		
		<!-- Кнопка скрола влево -->
		<a href="#!/" class="btn-move-left"  rel="nofollow"></a>
		
		<div class="b-foto b-foto_center c-loader-img">
			<div id="loader-img" class="loader-img"></div>
			<div id="loader-zoom-img" class="loader-zoom-img"></div>
			<a id="big-img-wrap" href="{$good.big}" style="text-align:center;" onclick="return false;" class="cloud-zoom" rel="position: 'inside' , showTitle: false, zoomWidth:'648', zoomHeight:'518'">
				<img id="big-img" class="big-img-pos" src="/images/empty.gif" alt="" height="479" width="467" />
				<span class="b-orden" id="good_orden"></span>
			</a>
			
			

		</div>
		
		<!-- Кнопка скрола вправо -->
		<a href="#!/" class="btn-move-right" rel="nofollow"></a>
        {if $filters.category == "futbolki"}
        <a "#!/" class="b-foto b-foto_rigth" style="background:url(/images/_tmp/bg-mayki-right.jpg) no-repeat 50%;" rel="nofollow">			
			<img height="476" align="left" alt="" src="/images/empty.gif" id="big-img-next" style="display: block; visibility:hidden;">
		</a>
        {elseif $filters.category == "zakazat_futbolku"}
		<a href="/{$module}/sex,female;category,{$category}/" class="b-foto b-foto_rigth" style="background:url(/images/_tmp/bg-mayki-right.jpg) no-repeat 50%;" rel="nofollow">			
			<img height="476" align="left" alt="" src="/images/empty.gif" id="big-img-next" style="display: block; visibility:hidden;">
		</a>
		{elseif $filters.category == "mayki-alkogolichki"}
		<a href="#!/" class="b-foto b-foto_rigth" style="background:url(/images/_tmp/bg-mayki-right.jpg) no-repeat 50%;" rel="nofollow">			
			<img height="476" align="left" alt="" src="/images/empty.gif" id="big-img-next" style="display: block; visibility:hidden;">
		</a>
		{elseif $filters.category == "tolstovki"}
		<a href="#!/" class="b-foto b-foto_rigth" style="background:url(/images/_tmp/bg-tolstovki-right.jpg) no-repeat 50%;" rel="nofollow">			
			<img height="476" align="left" alt="" src="/images/empty.gif" id="big-img-next" style="display: block; visibility:hidden;">
		</a>
		{elseif $filters.category == "kid"}
		<a "#!/" class="b-foto b-foto_rigth" style="background:url(/images/_tmp/bg-mayki-right.jpg) no-repeat 50%;" rel="nofollow">			
			<img height="476" align="left" alt="" src="/images/empty.gif" id="big-img-next" style="display: block; visibility:hidden;">
		</a>
		{elseif $filters.category == "pupil" || $filters.category == "child"}
		<a "#!/" class="b-foto b-foto_rigth" style="background:url(/images/_tmp/bg-mayki-right.jpg) no-repeat 50%;" rel="nofollow">			
			<img height="476" align="left" alt="" src="/images/empty.gif" id="big-img-next" style="display: block; visibility:hidden;">
		</a>
		{elseif  $filters.category == "sumki"}
		<a href="#!/" class="b-foto b-foto_rigth" style="background:url(/images/_tmp/bg-sumki-right.jpg) no-repeat 50%;" rel="nofollow">			
			<img height="476" align="left" alt="" src="/images/empty.gif" id="big-img-next" style="display: block; visibility:hidden;">
		</a>
		{elseif $filters.category == "platya"}
		<a href="#!/" class="b-foto b-foto_left" style="background:url(/images/_tmp/bg-platye-right.jpg) no-repeat 50%;"  rel="nofollow">			
			<img height="476" align="right" alt="" src="/images/empty.gif" id="big-img-prev" style="display: block; visibility:hidden;">
		</a>
		{elseif $filters.category == "mayki"}
		<a href="#!/" class="b-foto b-foto_left" style="background:url(/images/_tmp/bg-maika2-right.jpg) no-repeat 50%;"  rel="nofollow">			
			<img height="476" align="right" alt="" src="/images/empty.gif" id="big-img-prev" style="display: block; visibility:hidden;">
		</a>
		{/if}
		
	</div>
		
	<div class="b-good-bottom tolstovki_v2" style="margin-bottom:10px">
		<!-- кнопка в корзину -->
		<div class="b-good-price" style="margin-bottom:20px">
			<div class="b-good-nametype">
				<div class="b-good-name" id="style_name_title" style="margin-bottom:0;"></div> 
				<div class="b-good-name" id="good_name" style="margin-top:0;"></div>
			</div>
			
			<div class="b-topgallery b-smlgallery" style="float:left !important;margin-top: 11px;">
				<a id="all_t-shirs-popup" class="btn-switch-gall one-photo-view" href="#!/all-t-shirts" rel="nofollow">Все сразу<span class="all-gall-hint"> </span></a>			
			</div>
			
			<!-- Кнопка сабмита и цена -->
			
			<!--div class="b-good-discount">
				<div class="y-dics">скидка 5%</div>
				<div class="wb-disc">скидка 5%</div>				
			</div-->
		
	  </div>
		
	</div>		
		<br />
		<br />
        <br />
	</form>

	{include file="catalog/good.fittingroom.seotexts.tpl"}
    

</div>

<div id="size_greed" style="display:none">
	<p>Способ определения своего точного размера.</p>
</div>

{literal}
<script type="text/javascript">

$('#one_click_order').click(function() {
	
	//trackUser('В корзину [fittingroom]','fittingroom','ОК');
	
	trackUser("Заказ одной кнопкой", "Нажатие", "Ок");

	//var pr = goodForm.designData[goodForm.curGender][goodForm.curType].sizes[goodForm.curSize].colors[goodForm.curColor].price;
	var pr = $('.good-price .current-price').text();	
	//pr = /[\d\.]+/.exec(pr)[0];
	
	var confirm_text = goodForm.designData[goodForm.curGender][goodForm.curType].style_name + ', размер ' + goodForm.designData[goodForm.curGender][goodForm.curType].sizes[goodForm.curSize].ru + ' (' + goodForm.designData[goodForm.curGender][goodForm.curType].sizes[goodForm.curSize].en + ')' + ', цвет - ' + goodForm.designData[goodForm.curGender][goodForm.curType].sizes[goodForm.curSize].colors[goodForm.curColor].color_name + ', цена - ' + pr + " \nПодтверждаете?"
	
	
	if (!confirm(confirm_text)) {
		trackUser("Заказ одной кнопкой", "Ответили ОК", "Ок");
		return false;
	} else {
		trackUser("Заказ одной кнопкой", "Ответили Отмена", "Ок");
	}
	
	var gsId   = goodForm.designData[goodForm.curGender][goodForm.curType].sizes[goodForm.curSize].colors[goodForm.curColor].id;
	var button = this;
	
	$.get("/ajax/one_click_order/", {'good_id': good_id, 'good_stock_id': gsId, quantity: 1, r: new Date().getTime() }, function(r) {
		if (r == 'ok')
			location.href = $(button).attr('href');
	});
	
	return false;
});


$('#one_step_order').click(function() {
	
	trackUser("Заказ в один шаг", "Нажатие", "Ок");
	
	var gsId   = goodForm.designData[goodForm.curGender][goodForm.curType].sizes[goodForm.curSize].colors[goodForm.curColor].id;
	var button = this;
	
	tb_show('Заказ в один шаг', '/ajax/one_step_order/?good_id=' +  good_id + '&good_stock_id=' + gsId + '&width=390&height=430');

	return false;
});

var get_your_size = '<a href="#TB_inline?height=400&width=400&inlineId=size_greed" name="tab-sizes" title="Таблица размеров:" class="get-your-size anchor thickbox" rel="nofollow">Уточните свой размер</a>';

var curr_design_num = 0;		// порядковый номер дизайна в массиве дизайнов
// Распознаем хеш урла
if (document.location.hash != '') {	var design = document.location.hash.substr(3).split(':'); if (design.length == 2) {	curr_design_num = design[1]*1;}}

var jgoods = {/literal}{$jgoods}{literal};
var jstyles = {/literal}{$jstyles}{literal};
var jbig = {/literal}{$big}{literal};
//var styles = {/literal}{$styles}{literal};

var jgoods_imgs = {/literal}{$goods-style-cache}{literal};
var default_sex 	= '';
var default_color 	= 56;

//var default_category= 'tolstovki';
var default_category= false;
var allready_loaded = 0;
var allready_loaded_big = 0;
var curr_design 	= jgoods[curr_design_num];
var good_id = curr_design.good_id;
var jstyles_disabled = {/literal}{$jstyles-disabled}{literal};

var preloadImg = [];
var flagGallery = false;
function initSizeHelp(){
	 $(".get-your-size").click(function(){
		tb_show('Таблица размеров:','#TB_inline?height=400&width=400&inlineId=size_greed')
	 });
}

//кеширрование соседних картинк для листания.
cachedGoods = [];
function cacheImg(cur) {
	var cache = true;
	var cacheImgCount = 4;	
	
	var i = 1;
	while(i <= cacheImgCount) {		
		try {
			if ((cur + i) < jgoods.length && jgoods.length > 0 && jgoods[cur + i]) 
				goodForm.preLoad(jgoods[cur + i].good_id);
			if ((cur - i) > 0 && jgoods.length > 0 && jgoods[cur - i]) 
				goodForm.preLoad(jgoods[cur - i].good_id);
		} finally{ i++; }
	}
}

$(document).ready(function(){
	//1. инициализируем форму толсовки
	//goodForm.init(jstyles);
	goodForm.init(jstyles);
	
	$("body").click(function(){ $(".success_message").hide()});	
	var effect_duration = 500;
	
	//кэшируем соседние картинки
	cacheImg(typeof curr_design_num == 'undefined'?0:curr_design_num);
		
	$('.cloud-zoom').CloudZoom(); 
	
	// Иницаилизируем клики влево в право на стрелках возле главной картинки
	$("a.btn-move-left, a.b-foto_left").click(function(){
		goodForm.hideZoom();
		$("#big-img").attr("src", $("#big-img-prev").attr("src"));		
		$("#big-img-prev").attr("src", "");						
		curr_design_num--;
		if ( curr_design_num<0 ) { curr_design_num = jgoods.length-1;}
		curr_design 	= jgoods[curr_design_num];
		good_id 		= curr_design.good_id;
		document.location.hash = '#!/'+good_id+':'+curr_design_num;
		
		//$("#big-img-wrap").attr("href", 'http://www.maryjane.ru/pictures2/printshop/designs/'+good_id+'/big.png').attr('rel', 'nofollow');

		/*//зум по умолчанию
		var big = 'http://www.maryjane.ru/pictures2/printshop/designs/'+good_id+'/big.png';

		//дефолтный зум из массива jgoods
		if (typeof jgoods != 'undefined' && typeof curr_design_num != 'undefined' && jgoods[curr_design_num])
			big = jgoods[curr_design_num].big;
		
		//дефолтный зум по цветам
		if (typeof jbig != 'undefined') {
			if (jbig[good_id]) {
				try{
					var group = jstyles[goodForm.curGender][goodForm.curType].sizes[goodForm.curSize].colors[goodForm.curColor].group;
					if (jbig[good_id][group])
						big = jbig[good_id][group];
				}catch(e){}
			}
		}
		
		$("#big-img-wrap").attr("href", big);*/
		
		cacheImg(curr_design_num);
		goodForm.buildFirst();
		
		try{
			$('.cloud-zoom').data('zoom').destroy();
			$('.cloud-zoom').CloudZoom(); 
		}catch(e){}
		
		return false;
	});
	// клик вправо
	$("a.btn-move-right, a.b-foto_rigth").click(function(){
		goodForm.hideZoom();
		// делаем исчезновение за полсекунды, потом все переприсваиваем и показываем назад
		$("#big-img").attr("src", $("#big-img-next").attr("src"));
		$("#big-img-next").attr("src", "");
		curr_design_num++;
		if (curr_design_num > jgoods.length-1) {curr_design_num = 0;}
		curr_design 	= jgoods[curr_design_num];		
		good_id 		= curr_design.good_id;		
		document.location.hash = '#!/'+good_id+':'+curr_design_num;
		//$("#big-img-wrap").attr("href", 'http://www.maryjane.ru/pictures2/printshop/designs/'+good_id+'/big.png').attr('rel', 'nofollow');
		
		/*//goodForm.reloadImg();
		//зум по умолчанию
		var big = 'http://www.maryjane.ru/pictures2/printshop/designs/'+good_id+'/big.png';

		//дефолтный зум из массива jgoods
		if (typeof jgoods != 'undefined' && typeof curr_design_num != 'undefined' && jgoods[curr_design_num])
			big = jgoods[curr_design_num].big;
		
		//дефолтный зум по цветам
		if (typeof jbig != 'undefined') {
			if (jbig[good_id]) {
				try{
					var group = jstyles[goodForm.curGender][goodForm.curType].sizes[goodForm.curSize].colors[goodForm.curColor].group;
					if (jbig[good_id][group])
						big = jbig[good_id][group];
				}catch(e){}
			}
		}
		
		$("#big-img-wrap").attr("href", big);*/
		
		cacheImg(curr_design_num);
		goodForm.buildFirst();

		try{
			$('.cloud-zoom').data('zoom').destroy();
			$('.cloud-zoom').CloudZoom(); 
		}catch(e){}

		return false;
	});
	
	//Показать галерею со всеми рисунками
	//popupGallB.init({show_gall_btn: '#all_t-shirs-popup', gall_data: jgoods});
	pupGall_v2.init({show_gall_btn: '#all_t-shirs-popup', gall_data: jgoods, dataObj: goodForm, top_pos:210});	
	// При загрузке страницы подгружаем с
	//setTimeout(function(){ preload10Img(); }, 1000);
	
});

/**
 *	Алгоритм работы на 09-02-2012
 * - при загрузке страницы сразу подгружаем 10 футболок вправо и в это время показываем лоадер. 
 * Стрелки переключения в это время не активны 
 * когда 10 штук прощелкали - снова показываем лоадер и подгружаем 10 штук
 * кнопки переключения в этот момент должны быть не активны
 * TODO: включить в логику класса работы со всей формой футболок
 */
function preload10Img () {
	allready_loaded_big = preloadImg.length;	
	if (goodForm.curGender && goodForm.curType && goodForm.curColor && goodForm.curSize && allready_loaded_big+10<=jgoods.length){
		for (var i = allready_loaded_big; i<10+allready_loaded_big; i++) {
			var nnn = goodForm.designData[goodForm.curGender][goodForm.curType].sizes[goodForm.curSize].colors[goodForm.curColor].id; //"71433";
			preloadImg[i] = new Image();			
			preloadImg[i].src =	'/ajax/?good_id='+jgoods[i].good_id+'&gsId='+nnn+'&side=front&action=generatePreview';
		}
	}
}

// Инициализируем действия по клику на рисунке галереи
function initClickSmallPrew(){
	$(".b-topgallery .o_good").click(function(){ 
		$(".o_good").removeClass("active_good");
		$(this).addClass("active_good");			
		good_data = $(this).attr("name").split(':');
		good_id 		= good_data[0]; // айди дизайна
		curr_design_num = good_data[1]; // порядковый номер в массиве дизайнов
		curr_design 	= jgoods[curr_design_num];
		goodForm.buildFirst();		
		$("#big-img-wrap").attr("href", 'http://www.maryjane.ru/pictures2/printshop/designs/'+good_id+'/big.png').attr('rel', 'nofollow'); // меняем картинку для зума
		
	});
}

function initPopupGallery(){
	$(".gallery-conteiner .o_good").click(function(){
		//$(".o_good").removeClass("active_good");
		//$(this).addClass("active_good");
		good_data = $(this).attr("name").split(':');
		good_id 		= good_data[0]; // айди дизайна
		curr_design_num = good_data[1]; // порядковый номер в массиве дизайнов
		curr_design 	= jgoods[curr_design_num];
		$(".b-gallery-popup, .b-freezen-bg").hide();
		goodForm.buildFirst();
		$("#big-img-wrap").attr("href", 'http://www.maryjane.ru/pictures2/printshop/designs/'+good_id+'/big.png').attr('rel', 'nofollow');// меняем картинку для зума
		
		return false;
	});
}

</script>
{/literal}