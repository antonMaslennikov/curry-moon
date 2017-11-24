{literal}
<!-- catalog-fittingroom-sumki.html -->
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
.tolstovki-page .b-titleblock {height:60px;}
.btn-switch-gall {background: url("/images/buttons/btn-switch-gall-grey.gif") no-repeat scroll 0 0 transparent; width:104px; height: 31px;}
.btn-switch-gall.many-photo-view {background-position:0 -31px;}
.c-loader-img {background:url("/images/buttons/ajax-loader-img.gif") no-repeat scroll 50% 50% transparent;}

.tolstovki-page .b-titleblock { width:100%;}
.b-good-price-submit {width:385px;float:right;margin-top:0px !important;}

#one_step_order {
	float: right;
	width: 140px;
	height: 28px;
	background: url(/images/buttons/broun-submit.gif) no-repeat 0 0;
	border: none;
	cursor: pointer;
	margin: 1px 20px 0 3px;
}
#one_step_order:hover {
	background: url(/images/buttons/broun-submit-hover.gif) no-repeat 0 0;
}
.b-good-price-submit .good-price { text-align:right; color:#009966;}

.b-good-bottom.tolstovki_v2 {clear:left;padding:5px 0px;}

/*Кнопки фильтра*/
.b-filter_tsh { width: 232px; margin: 0 auto; clear:left; height: 30px; }
.b-filter_tsh a {
	float: left;
	height: 30px;
	font-size: 0;
	background: url(/images/buttons/btn-filters-list.gif) no-repeat 0 0;
}
.b-filter_tsh a.new-filter {
	width: 67px;
	background-position: 0 -44px;
}
.b-filter_tsh a.pop-filter {
	width: 88px;
	background-position: -67px 0;
}
.b-filter_tsh a.score-filter {
	width: 77px;
	background-position: -155px 0;
}
.b-filter_tsh a.new-filter.active {
	width: 67px;
	background-position: 0 0;
}
.b-filter_tsh a.pop-filter.active {
	background-position: -67px -44px;
}
.b-filter_tsh a.score-filter.active {
	background-position: -155px -88px;
}
/*Слайдер*/
.bottom-slider {
	width:100%;
	height:185px;
	margin: 10px 0px;
}
.bottom-slider .slider-container {
	float:left;
	width:793px;
	height:100%;
	position: relative;
}

.bottom-slider .btn-move-left, .bottom-slider .btn-move-right {height:185px;}

.bottom-slider .left-opacity, .bottom-slider .right-opacity {
	height:185px;
	width:30px;
	position: absolute;
	top:0px;
	z-index:100;
}
.bottom-slider .left-opacity {
	left: -4px;
	background: url(/images/catalog/sprite-gr-opasity.png) repeat-y -30px 0;
}
.bottom-slider .right-opacity {
	right: -4px;
	background: url(/images/catalog/sprite-gr-opasity.png) repeat-y 0px 0;
}

.bottom-slider .slider-items-container {height: 185px;overflow:hidden;background-color:white;position:relative;}
.bottom-slider .slider-items {height:100%;width:1154px;position:absolute;left:-100px;top:0px;/*background-color:#BBB;*/}
.bottom-slider .slider-item {/*height:185px;width:185px;*/float:left;border-right:4px solid white;cursor:pointer;}
/*.bottom-slider .slider-item:hover {border:1px solid silver;}*/
.bottom-slider .slider-item:first-child {margin-left:0px;}

/*медальку в левый угол*/
#big-img-wrap .b-orden {
	float:left;
	right:none;
	left:5px;
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
		<div class="b-titleblock">
			
			<h1>
				<span id="style_name" style="text-decoration:none">
	            	{if $filters.category == "zakazat_futbolku"}
						Футболки на заказ
					{elseif $filters.category == "futbolki"}
						Купить дизайнерские<br />футболки
					{elseif $filters.category == "mayki-alkogolichki"}
						Майки алкоголички / борцовки
					{elseif $filters.category == "tolstovki"}
						Толстовки с капюшоном
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
					{/if}
					
					<span class="h1_sex" id="h1_sex_male" style="display: {if $filters.sex == 'male' || !$filters.sex} block {else} none {/if}">мужские</span>
					<span class="h1_sex" id="h1_sex_female" style="display: {if $filters.sex == 'female'} block {else} none {/if}">женские</span>
				</span>
			</h1> 			
			{*<!-- Кнопки выбора пола -->
            <div class="b-top-sett">
				<div id="input_man_woman" class="b-radio-manwomen radio-input selected-woman" >
					<a rel="nofollow" href="#!/select-man" 	 id="male" class="type-select"></a>
					<a rel="nofollow" href="#!/select-woman" id="female" class="type-select"></a>
					<a rel="nofollow" href="#!/select-kids"  id="kids" class="type-select"></a>
					<input type="hidden" value="" name="gender" id="good_gender">
				</div>
			<!-- Различные размеры носителей -->
			<div class="select-size-box" style="width:auto; height:auto;">
				<input type="hidden" value="" name="size" id="good_sizes">					
			</div>	
				
			</div>*}
			
			<!-- Различные цвета в зависимости от размера -->			
			<div style="width:355px;height:auto;float:left;text-align:right;">
				<input type="hidden" value="76" name="color" id="good_color">
				<div class="b-color-select" style="clear:both;float: right;margin-right: 20%;"></div>
			</div>

			<!-- Кнопка сабмита и цена -->
			<div class="b-good-price-submit">
				<div class="good-price">
					<div class="old-price">-</div>
					<div class="current-price">--- руб.</div>
				</div>
				<!--input type="submit" class="not_select_size" value="" title="В корзину" id="submit"--> <!--  onclick="return checkAddToCartForm();" -->				
				<div class="success_message"><a id="go2basket" href="http://www.maryjane.ru/basket/" rel="nofollow">В корзине:  +1</a></div>
			

				{if $USER->user_delivered_orders >= 1}
				<a href="http://www.maryjane.ru/order/confirm/{$basket->id}/" id="one_click_order" rel="nofollow">
					<div id="one_click_order_block" style="width:150px; height:20px; margin-top:-6px;background-color:#00a851; padding:10px; text-align:center">
					Заказ в один клик
					<!--span class="help2 clr help_size" id="one_click_order_help"><a rel="nofollow" href="http://www.maryjane.ru/faq/142/?TB_iframe=true&amp;height=500&amp;width=600" class="thickbox" style="font-size: 9px;color:#FFF; border-color:#FFF">?</a></span-->
					</div>		
				</a>		
				{/if}
				
				{if $USER->user_delivered_orders == 0}
				<a href="#" id="one_step_order" rel="nofollow" ></a>
				{/if}
			</div>
			
			{*<div class="price-hint" style="visibility: visible;">
				<span class="price_back_holder">Вам вернется 65 руб. на следующий заказ</span>
				<!-- span class="help2 clr help_size"><a style="font-size: 9px;line-height:35px" href="http://www.maryjane.ru/faq/group,13/?TB_iframe=true&amp;height=500&amp;width=600" rel="nofollow" class="thickbox">?</a></span-->
			</div>*}
			
		</div>		
		
		{*<!-- Галерея вверху и справа -->
		<div class="b-topgallery b-smlgallery">
			<a id="all_t-shirs-popup" class="btn-switch-gall one-photo-view" href="#!/all-t-shirts" rel="nofollow">Все сразу<span class="all-gall-hint"> </span></a>			
		</div>*}
	</div>

	<form action="" method="post" id="good_form">	

	<div class="b-good-foto center-foto">
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
		{/if}
		
	</div>
		
	<div clear:both;></div>
	<div class="b-good-bottom tolstovki_v2">
		<!-- Кнопки -->
		<div class="b-filter_tsh">
			<a href="#new" rel="nofollow" class="new-filter ">Новое</a>
			<a href="#popular" rel="nofollow" class="pop-filter active">Популярное</a>
			<a href="#grade" rel="nofollow" class="score-filter ">По оценке</a>
		</div>			

		<!-- Слайдер -->
		<div class="bottom-slider b-good-foto">
			<!-- Кнопка скрола влево -->
			<a href="#!/" class="btn-move-left" rel="nofollow"></a>
			<div class="slider-container">
				<div class="left-opacity"></div>
				<div class="slider-items-container">
					<div class="slider-items"></div>
				</div>
				<div class="right-opacity"></div>
			</div>
			<!-- Кнопка скрола вправо -->
			<a href="#!/" class="btn-move-right" rel="nofollow"></a>
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
	
	var confirm_text = goodForm.designData[goodForm.curGender][goodForm.curType].style_name + ', размер ' + goodForm.designData[goodForm.curGender][goodForm.curType].sizes[goodForm.curSize].ru + ' (' + goodForm.designData[goodForm.curGender][goodForm.curType].sizes[goodForm.curSize].en + ')' + ', цвет - ' + goodForm.designData[goodForm.curGender][goodForm.curType].sizes[goodForm.curSize].colors[goodForm.curColor].color_name + ', цена - ' + goodForm.designData[goodForm.curGender][goodForm.curType].sizes[goodForm.curSize].colors[goodForm.curColor].price + " р. \nПодтверждаете?"
	
	
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

Slider = {
	category: 'popular',
	data: [],
	imgs: [],
	//pos: 0,
	handler: null,
	
	clickLeft: ".bottom-slider a.btn-move-left, .bottom-slider a.b-foto_left",
	clickRight: ".bottom-slider a.btn-move-right, .bottom-slider a.b-foto_right",
	btns: ".b-filter_tsh a",
	btnNew: ".bottom-slider .b-filter_tsh a.new-filter",
	btnPopular: ".bottom-slider .b-filter_tsh a.pop-filter",
	btnGrade: ".bottom-slider .b-filter_tsh a.score-filter",
	
	//получение списка группы для слайдера
	init: function (){
		var self = this;
		
		//данные по этой категории
		this.getData();
		
		//клик в слайдере влево
		$(this.clickLeft).click(function(){
			self.prev();
			return false;
		});
		//клик в слайдере вправо
		$(this.clickRight).click(function(){
			self.next();
			return false;
		});
		//клик по кнопке "Новые"
		$(this.btns).click(function(){
			$(self.btns).removeClass('active');
			$(this).addClass('active');
			self.category = /#([\w]+)$/.exec(this.href)[1];
			self.pos=0;
			self.getData();
			return false;
		});
	},

	prev: function(){
		var d = this.data[this.category].pos;
		if (this.getPos(-3) != d) {
			this.data[this.category].pos-=3;
			this.build();
		}
	},

	next: function(){
		var d = this.data[this.category].pos;
		if (this.getPos(3) != d) {
			this.data[this.category].pos+=3;
			this.build();
		}
	},
	
	getPos: function(df){
		var d = this.data[this.category].pos;
		if (df) d += df;
		if (d < 0) d = 0;
		if (d > this.data[this.category].length) d = this.data[this.category].length-1;
		return d;
	},
	
	getData: function(){
		var self = this;
		{/literal}
		if (!this.data[this.category]) {
			$.getJSON('/{$module}/category,{$filters.category}/'+this.category+'/',function(d) {
				self.data[self.category] = d;
				self.data[self.category].pos = 0;				
				self.build();
			});
		} else this.build();
		{literal}	
	},
	
	//построение слайдера
	build: function() {
		var self = this;
		var imgCount = 6;
		var i = 0;
		var pos = this.getPos();
		
		if (pos == this.getPos(1)) return;
		while(i < imgCount) {		
			if (!this.imgs[i]) {
				this.imgs[i] = new Image();
				this.imgs[i].className = 'slider-item';				
				this.imgs[i].onclick = function() { if (self.handler) self.handler('click', this.good); }
				$('.bottom-slider .slider-items').append(this.imgs[i]);
			}
			if (this.data[this.category][pos + i]) { 
				this.imgs[i].good = this.data[this.category][pos + i];
				$(this.imgs[i]).css({ backgroundColor: '#'+this.imgs[i].good.bg });
				this.imgs[i].src = this.data[this.category][pos + i].picture_path;
			}
			i++;
		}
	}

}

$(document).ready(function(){
	//1. инициализируем форму толсовки
	//goodForm.init(jstyles);
	goodForm.init(jstyles);
	
	$("body").click(function(){ $(".success_message").hide()});	
	var effect_duration = 500;
		
	//инициализация слайдера
	Slider.init();
	Slider.handler = function(event, good){ 
		for(var i=0;i<jgoods.length;i++)
			if (parseInt(jgoods[i].good_id) == parseInt(good.good_id)) {
				curr_design_num = i;
				
				goodForm.hideZoom();
				$("#big-img").attr("src", $("#big-img-next").attr("src"));
				$("#big-img-next").attr("src", "");				
				curr_design 	= jgoods[curr_design_num];		
				good_id 		= curr_design.good_id;		
				document.location.hash = '#!/'+good.good_id+':'+curr_design_num;
				$("#big-img-wrap").attr("href", 'http://www.maryjane.ru/pictures2/printshop/designs/'+good.good_id+'/big.png');
				
				cacheImg(curr_design_num);
				goodForm.buildFirst();

				$('.cloud-zoom').data('zoom').destroy();
				$('.cloud-zoom').CloudZoom(); 
				
				return;
			}
	}
	
	//кэшируем соседние картинки
	cacheImg(typeof curr_design_num == 'undefined'?0:curr_design_num);
		
	$('.cloud-zoom').CloudZoom(); 
	
	// Иницаилизируем клики влево в право на стрелках возле главной картинки
	$(".center-foto a.btn-move-left, .center-foto a.b-foto_left").click(function(){
		goodForm.hideZoom();
		$("#big-img").attr("src", $("#big-img-prev").attr("src"));		
		$("#big-img-prev").attr("src", "");						
		curr_design_num--;
		if ( curr_design_num<0 ) { curr_design_num = jgoods.length-1;}
		curr_design 	= jgoods[curr_design_num];
		good_id 		= curr_design.good_id;
		document.location.hash = '#!/'+good_id+':'+curr_design_num;
		
		$("#big-img-wrap").attr("href", 'http://www.maryjane.ru/pictures2/printshop/designs/'+good_id+'/big.png');

		cacheImg(curr_design_num);
		goodForm.buildFirst();
		
		$('.cloud-zoom').data('zoom').destroy();
		$('.cloud-zoom').CloudZoom(); 
		
		return false;
	});
	// клик вправо
	$(".center-foto a.btn-move-right, .center-foto a.b-foto_rigth").click(function(){
		goodForm.hideZoom();
		// делаем исчезновение за полсекунды, потом все переприсваиваем и показываем назад
		$("#big-img").attr("src", $("#big-img-next").attr("src"));
		$("#big-img-next").attr("src", "");
		curr_design_num++;
		if (curr_design_num > jgoods.length-1) {curr_design_num = 0;}
		curr_design 	= jgoods[curr_design_num];		
		good_id 		= curr_design.good_id;		
		document.location.hash = '#!/'+good_id+':'+curr_design_num;
		$("#big-img-wrap").attr("href", 'http://www.maryjane.ru/pictures2/printshop/designs/'+good_id+'/big.png');
		
		cacheImg(curr_design_num);
		goodForm.buildFirst();

		$('.cloud-zoom').data('zoom').destroy();
		$('.cloud-zoom').CloudZoom(); 

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
		$("#big-img-wrap").attr("href", 'http://www.maryjane.ru/pictures2/printshop/designs/'+good_id+'/big.png'); // меняем картинку для зума
		
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
		$("#big-img-wrap").attr("href", 'http://www.maryjane.ru/pictures2/printshop/designs/'+good_id+'/big.png');// меняем картинку для зума
		
		return false;
	});
}

</script>
{/literal}