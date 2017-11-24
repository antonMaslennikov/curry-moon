<link rel="stylesheet" href="/css/catalog/styles.css" type="text/css" media="all"/>
<style type="text/css">

.b-catalog_v2 {
	margin-top: 0px;
}
.pageTitle{
	margin-bottom: 3px;
}
.pageTitle h1 {
	font-size: 24px;
	padding-left: 2px;
}

/*пол*/
.b-radio-manwomen {
	clear: both;
	width:139px;
	height: 32px;
	margin: 23px 0;
}
.b-radio-manwomen .selected-man {
	background-position: 0 0;
}
.b-radio-manwomen .radio-input {
	float: left;
	width:auto;
	height: 32px;
	background: url(/images/buttons/radio-manwomen.png) no-repeat 0 0;
	color: #8A8A8A;
	cursor: pointer;
}
.b-radio-manwomen .radio-input a {
	color: #8A8A8A;
	text-decoration: underline;
	background: none;
}
.b-radio-manwomen .selected-woman {
	background-position: 0 -32px;
}
.b-radio-manwomen .type-select {
	width: 70px;
}
/*выбор размера
-------------------------*/
.select-size-box {
	float: left;
	width: 240px;
	height: 100%;
	margin-bottom: 13px;
}
.select-size-box .one-size{
	font-family: arial;
	font-size: 12px;
	font-weight: bold;
	width: 106px;
	/*line-height: 13px;
	margin: 0 4px 5px 0 !important;*/
	padding: 18px 1px 16px 0;
	border: 1px solid #CCCCCC;
}
.select-size-box .one-size{
	text-decoration: underline !important;
}
.select-size-box .one-size:hover, .select-size-box .selected-size {
	text-decoration: none !important;
}
/*выбор цвета
---------------------------*/
.b-color-select {
	width: 212px;
	float: left;
	margin: 0px 0 10px;
	height: 100%;
}
.b-color-select .selected-color, .b-color-select .one-color:hover {
	border: none;
}
.b-color-select .one-color {
	width:33px;
	height:33px;
	border:none;
	padding:0;
	margin-bottom:0px;
}
.b-color-select .selected-color, .b-color-select .one-color:hover {
	border-color: #00A851;
}
.b-color-select .selected-color .it, .b-color-select .one-color:hover .it, .b-color-select .selected-color .it.white {
	background-position: -2px -37px;
}
.b-color-select .one-color .white {
	background-position: -2px -72px;
}
.one-color .it {
	background: url("/images/buttons/color-mask.png") no-repeat scroll -2px -2px transparent;
	width: 33px;
	height: 33px;
	border: none;
	float: left;
}
/*название категорий
----------------------------*/
.title-info{
	font-family: MyriadPro-CondIt;
	display: block;
	font-size: 22.98px;	
	color: #858585;
	/*font-style: italic;
    padding-bottom: 8px;*/
	float: left;
	width: 210px;
	padding-left:3px;
}
/*выбор носителя
----------------------------------*/
.b-tshirttype {
	float: left;
	width:215px;
	margin-top: 10px;
	margin-left: 4px;
}
.b-tshirttype span {
	float: left;
	height: 54px;
	padding-bottom: 14px;
	/*background: url(/images/catalog/nav_bline.jpg) no-repeat left bottom;*/
	display: block;
}
.b-tshirttype a.some-tshirt {
	width: 70px;
	height: 54px;
	padding: 0;
	border: 0px;
	background: url(/images/buttons/sprite-category_v2.png) no-repeat left top;
	text-align: center;
	text-decoration: none;
	font-size: 11px;
	line-height: 12px;
	color: #8A8A8A;
}
.b-tshirttype a.male#tshirt-futbolki {
	background-position: -1px -13px;
}
.b-tshirttype a.male#tshirt-futbolki:hover, .b-tshirttype a#tshirt-futbolki.male.active-tshirt-type {
	background-position: -83px -13px;
	position: relative;
	left: -1px;
}
.b-tshirttype a#tshirt-tolstovki {
	background-position: -179px -68px;
}
.b-tshirttype a#tshirt-tolstovki:hover, .b-tshirttype a#tshirt-tolstovki.active-tshirt-type {
	background-position: -255px -68px;
	position: relative;
	left: -1px;
}
.b-tshirttype a#tshirt-phones {
	background-position: -179px -343px;
}
.b-tshirttype a#tshirt-phones:hover, .b-tshirttype a#tshirt-phones.active-tshirt-type {
	background-position: -255px -343px;
	position: relative;
	left: -1px;
}
.b-tshirttype a#tshirt-laptops {
	background-position: -1px -288px;
}
.b-tshirttype a#tshirt-laptops:hover, .b-tshirttype a#tshirt-laptops.active-tshirt-type {
	background-position: -83px -288px;
	position: relative;
	left: -1px;
}
.b-tshirttype a#tshirt-touchpads {
	background-position: -1px -233px;
}
.b-tshirttype a#tshirt-touchpads:hover, .b-tshirttype a#tshirt-touchpads.active-tshirt-type {
	background-position: -83px -233px;
	position: relative;
	left: -1px;
}
/*Дополнительно
------------------------------*/
.dop-info{
	display:block;
	float:left;
	margin-top:3px;
	margin-left: 1px;
}
.b-good-calc-deliv li {
	list-style: none;
	height: 30px;
	font-size: 12px;
}
.b-good-calc-deliv a, a.details{
	text-decoration: none;
	border-bottom: 1px dashed #535758;
	color: #737373;
}
/*очистить фильтры
--------------------------------*/
.filtr-cler{
    margin: 20px 0 87px;
	text-align: center;
	width: 187px;
	float: left;
	display: block;
	padding: 10px 0 10px 8px;
	border-bottom: 1px solid #CCCCCC;
	border-top: 1px solid #CCCCCC;
}
.filtr-cler a{
	color: #737373;
}

/*контент
-----------------------------*/
.catalog_goods_list {
	min-height: 850px;
}
.catalog_goods_list .list_wrap {
	margin: 0px;
}
.list_wrap ul{
	list-style: none;
}
.list_wrap ul li.m122 {
	float: left;
	width: 225px;
	min-height: 250px;
	padding: 0px;
	margin:0 10px 6px 0;
	border: 1px solid #D7D7D7;
}
.list_wrap ul li.m122 a.item{
	padding-top: 1px;
	min-height: 225px;
	display: block;
	text-align: center;
	position: relative;
	overflow: hidden;
}
.list_wrap ul li.m122 a.item img{
	width: 221px;
}
.list_wrap ul li.m122 div.item{
	border-top: 1px solid #D7D7D7;
	height: 32px;
	display: block;
	text-align: right;
	padding: 10px 7px 0 0;
	color: #A8A8A8;
	overflow: hidden;
}
.list_wrap ul li.m122 div.item a, .list_wrap ul li.m122 div.item span{
	font-family:arial;
	font-size: 16px;
	/*font-style: italic;
	font-weight: bold;*/
	text-decoration: none;
	color: #A8A8A8;	
}
/*классы  на определенные категории*/
.list_wrap ul li.m122.phones a.item,
.list_wrap ul li.m122.ipodmp3 a.item,
.list_wrap ul li.m122.touchpads a.item,
.list_wrap ul li.m122.laptops a.item{
	height: 229px;
	width: 225px;
}
.list_wrap ul li.m122.phones a.item img{
	width: auto;
	max-width: 221px;
	max-height: 243px;
}
.list_wrap ul li.m122.ipodmp3 a.item img{
	width: auto;
	max-width: 221px;
	margin-top: 14px;
}
.list_wrap ul li.m122.touchpads a.item img{
	width: auto;
	max-width: 221px;
	max-height: 238px;
}
.list_wrap ul li.m122.laptops a.item img{	
	margin-top: 34px;
	width: auto;
	max-width: 221px;
	max-height: 243px;
}
	/*==================================================
	после теста переместить в глобальные стили
====================================================*/

/*Кнопки фильтра*/
}
.b-filter_tsh a{
	float: left;
	height: 30px;
	font-size: 0;
	background: url(/images/buttons/btn-filters-list2.gif) no-repeat 0 0;
}
.b-filter_tsh a{
	background-image:url(/images/buttons/btn-filters-list2.gif)!important;
}

/*==========================================*/
/*пасивные*/
.b-filter_tsh a.new-filter {
	width: 67px;
	background-position: 0 -132px;
}
.b-filter_tsh a.pop-filter {
	width: 88px;
	background-position: -67px -132px;
}
.b-filter_tsh a.score-filter {
	width: 77px;
	background-position: -155px -132px;
}
.b-filter_tsh a.similar-filter {
	width: 77px;
	background-position: -232px -132px;
}

/*активные*/
.b-filter_tsh a.new-filter.active {
	width: 67px;
	background-position: 0 0 !important;
}
.b-filter_tsh a.pop-filter.active {
	background-position: -67px 0 !important;
}
.b-filter_tsh a.score-filter.active {
	background-position: -155px 0 !important;
}
.b-filter_tsh a.similar-filter.active {
	background-position: -232px 0 !important;
}

/*наведены*/
.b-filter_tsh a.new-filter:hover {
	background-position: 0 -88px;
}
.b-filter_tsh a.pop-filter:hover {
	background-position: -67px -88px;
}
.b-filter_tsh a.score-filter:hover {
	background-position: -155px -88px;
}
.b-filter_tsh a.similar-filter:hover {
	background-position: -232px -88px;
}

/*нажатые но не отжатые*/
.b-filter_tsh a.new-filter:active {
	background-position: 0 0;
}
.b-filter_tsh a.pop-filter:active {
	background-position: -67px -44px;
}
.b-filter_tsh a.score-filter:active {
	background-position: -155px -44px;
}
.b-filter_tsh a.similar-filter:active {
	background-position: -232px -44px;
}
/*конец кнопок*/
</style>
<div class="b-catalog_v2">
	
	<div class="pageTitle" style="width:710px;">
		<h1>
		{if $filters.sex == 'female'}
			Женское
		{else}
			Мужское
		{/if}
		</h1>
		
		<!--изменить-->
		{if $user && ($USER->user_id == $user_id || $USER->user_id == 27278 || $USER->user_id == 6199 || $USER->user_id == 86455)}
		<div class="change" style="float:left;line-height:40px;margin-left:15px">
			<a href="#" title="изменить">изменить</a>
		</div>
		{/if}
					
		<div class="b-filter_tsh" style="margin-right:3px;margin-top:0px;float:right;padding-top:9px;">
			<a href="/{$module}/sex,{$filters.sex}/new/ajax/" rel="nofollow" class="new-filter {if $orderBy == 'new'}active{/if}">Новое</a>
			<a href="/{$module}/sex,{$filters.sex}/popular/ajax/" rel="nofollow" class="pop-filter {if $orderBy == 'popular'}active{/if}">Популярное</a>
			<a href="/{$module}/sex,{$filters.sex}/grade/ajax/" rel="nofollow" class="score-filter {if $orderBy == 'grade'}active{/if}">По оценке</a>
		</div>
		
		<!-- Скрипт для работы этого фильтра  -->
		{literal}
		<script type="text/javascript">
		$(document).ready(function(){
			$(".b-filter_tsh a").click(function(){
				if (!$(this).hasClass('active')) {
					$(".catalog_goods_list .list_wrap").append('<div class="loadding-cover" id="loadding-cover"></div>');
					setTimeout(function(){$("#loadding-cover").remove();}, 3000);
					
					$(".b-filter_tsh a").removeClass('active');
					$(this).addClass('active');
					var gall_link = $(this).attr('href');
					$.get(gall_link, function(content){
						$(".list_wrap ul").html(content);
						$("#loadding-cover").remove();
						initShowMore();
					});
				}				
				return false;
			});
		});
		</script>		
		{/literal}
	</div>
	
	<!-- выбор пола -->	
	<div class="b-radio-manwomen" style="width:139px;margin:-42px 105px 0 0;float:right;overflow: hidden;">
		<div class="radio-input {if $filters.sex == 'female'}selected-woman{else}selected-man{/if}" id="input_man_woman">
			<a class="type-select {if $filters.sex == 'male'}active{/if}" id="male" href="/{$module}/sex,male/" rel="nofollow" style="visibility: visible;"></a>
			<a class="type-select {if $filters.sex == 'female'}active{/if}" id="female" href="/{$module}/sex,female/" rel="nofollow" style="visibility: visible;width: 69px;"></a>
			<input id="good_gender" type="hidden" name="gender" value="">
		</div>
	</div>
		
	<div style="clear:both"></div>
	
	<div class="catalog_goods_list" style="border:0;width:714px;margin-right:19px;margin-top:0px;">
		<script type="text/javascript" src="/js/2012/autoscroll.js"></script>
		<script type="text/javascript" src="/js/2012/button_to_top.js"></script>
		<script type="text/javascript">
			var countElements = {if $count}{$count}{else}0{/if};
			var pageLoaded = {$page};
			var REQUEST_URI = '{$PAGE_URI}';
		</script>		
		<script type="text/javascript">		
			$(document).ready(function() { 			
				//инициализируем автоподгрузку страниц
				$(window).bind('scroll', pScroll);
			});
		</script>
		
		{assign var=SEX value=$filters.sex}
		
		<div class="list_wrap {$category}" style="width:717px;">		
		<ul>

			{include file="catalog/list.goods.gender.$SEX.tpl"}
		
		</ul>
		</div>
	</div>
	
	<!-- Фильтр в правом сайдбаре -->
	<div style="display: block;height: 100%;width: 240px;float: left;">
		<!--Выберите размер-->
		<div class="select-size-box" style="">
			<div class="error-order" id="size-error">Выберите размер!</div>
			<input id="good_sizes" type="hidden" name="size" value="2">	
			<a class="one-size {if !$fsize_not_selected && $filters.size == 2}selected-size{/if}" id="size-item2" name="2" href="/{$module}/sex,{$filters.sex}/{if !$fcolor_not_selected}color,{$filters.color}{/if}{if $filters.size != 2};size,2{/if}/">XS(36-38)</a>
			<a class="one-size {if !$fsize_not_selected && $filters.size == 3}selected-size{/if}" id="size-item3" name="3" href="/{$module}/sex,{$filters.sex}/{if !$fcolor_not_selected}color,{$filters.color}{/if}{if $filters.size != 3};size,3{/if}/">S (38-40)</a>
			<a class="one-size {if !$fsize_not_selected && $filters.size == 4}selected-size{/if}" id="size-item4" name="4" href="/{$module}/sex,{$filters.sex}/{if !$fcolor_not_selected}color,{$filters.color}{/if}{if $filters.size != 4};size,4{/if}/">M (40-42)</a>
			<a class="one-size {if !$fsize_not_selected && $filters.size == 5}selected-size{/if}" id="size-item5" name="5" href="/{$module}/sex,{$filters.sex}/{if !$fcolor_not_selected}color,{$filters.color}{/if}{if $filters.size != 5};size,5{/if}/">L (42-44)</a>
			<a class="one-size {if !$fsize_not_selected && $filters.size == 6}selected-size{/if}" id="size-item6" name="6" href="/{$module}/sex,{$filters.sex}/{if !$fcolor_not_selected}color,{$filters.color}{/if}{if $filters.size != 6};size,6{/if}/">XL (44-46)</a>
			<a class="one-size {if !$fsize_not_selected && $filters.size == 7}selected-size{/if}" id="size-item7" name="7" href="/{$module}/sex,{$filters.sex}/{if !$fcolor_not_selected}color,{$filters.color}{/if}{if $filters.size != 7};size,7{/if}/">2XL (46-48)</a>
			{if $filters.sex == 'male'} 
			<a class="one-size {if !$fsize_not_selected && $filters.size == 8}selected-size{/if}" id="size-item8" name="8" href="/{$module}/sex,{$filters.sex}/{if !$fcolor_not_selected}color,{$filters.color}{/if}{if $filters.size != 8};size,8{/if}/">3XL (46-48)</a>
			{/if}
		</div>
		
		<h1 class="title-info">Выберите цвет</h1>
		<div class="b-color-select">
			<input id="good_color" type="hidden" name="color" value="1">
			{foreach from=$Fcolors item="c"}
			<div class="one-color {$c.class}" name="{$c.id}" group="{$c.group}" title="{$c.name}" id="color{$c.id}">
				<a href="/{$module}/sex,{$filters.sex}/{if !$fsize_not_selected}size,{$filters.size};{else}{/if}color,{$c.id}/"><span class="it {$c.name_en}" style="background-color:#{$c.hex};"></span></a>
			</div>
			{/foreach}
		</div>

		<h1 class="title-info">Выберите носитель</h1>
		<div class="b-tshirttype">
			<input type="hidden" id="good_type" name="type" value="futbolki">
			<span style=""><a href="/{$module}/category,futbolki{if !$fsize_not_selected};size,{$filters.size}{/if}{if !$fcolor_not_selected};color,{$filters.color}{/if}/{if $filters.sex == 'female'}female/{/if}" id="tshirt-futbolki" like-name="ps_src" name="futbolki" class="some-tshirt male" title="Футболка" rel="nofollow">&nbsp;</a></span>
			<span style=""><a href="/{$module}/category,tolstovki{if !$fsize_not_selected};size,{$filters.size}{/if}{if !$fcolor_not_selected};color,{$filters.color}{/if}/{if $filters.sex == 'female'}female/{/if}" id="tshirt-tolstovki" like-name="ps_src" name="tolstovki" class="some-tshirt male" title="Толстовка" rel="nofollow">&nbsp;</a></span>
			<span style=""><a href="/{$module}/category,phones/" id="tshirt-phones" like-name="phones" name="phones" class="some-tshirt female" title="Телефоны" rel="nofollow">&nbsp;</a></span>
			<span style=""><a href="/{$module}/category,laptops/" id="tshirt-laptops" like-name="laptops" name="laptops" class="some-tshirt female" title="Ноутбуки" rel="nofollow">&nbsp;</a></span>
			<span style=""><a href="/{$module}/category,touchpads/" id="tshirt-touchpads" like-name="touchpads" name="touchpads" class="some-tshirt female" title="Планшеты" rel="nofollow">&nbsp;</a></span>
			<span style="margin-left:5px;"><a href="#" id="tshirt-touchpads" like-name="touchpads" name="touchpads" class="some-tshirt female" title="Планшеты" rel="nofollow">&nbsp;</a></span>
		</div>
		
		
		<div class="filtr-cler">
			<a href="/{$module}/sex,{$filters.sex}/"  title="" rel="nofollow">отменить все фильтры</a>
		</div>
		
		<h1 class="title-info">Дополнительно</h1>
		<div class="dop-info">
			<ul class="b-good-calc-deliv">
				<li><a href="#!/show-delivery-calc" id="show-calc" rel="nofollow" style="visibility: visible;">калькулятор доставки</a></li>
				<li><a href="#!/show-composition-maintenance" id="show-composition-maintenance" title="composition" rel="nofollow">состав и уход</a></li>
				<li><a href="#!/show-delivery" id="show-delivery" title="delivery" rel="nofollow">доставка</a></li>
				<li><a href="#!/show-return-exchange" id="show-return-exchange" title="moneyback" rel="nofollow">возврат-обмен</a></li>		
				<li><a href="#!/show-comments" id="show-comments" title="comments" rel="nofollow">комментариев (4)</a></li>
			</ul>
		</div>
	</div>
	<div style="clear:both"></div>
	{*include file="catalog/list.sidebar.tpl"*}
	<span style="margin:100px 0 11px;width:750px;display:block;font-size:12px;">Для прекрасных дам Maryjane приготовила лучшие женские футболки с прикольными надписями и авторскими изображения, с<br/>помощью которых вы сможете эффектно подчеркнуть свою красоту и быть в центре внимания окружающих мужчин. Кроме<br/>этого, если мужчина хочет как-то по особенному показать своей девушке отношение к ее красоте и талантам, то лучше всего<br/>купить женские футболки, которые скажут все за вас.</span>
	<a href="#"  title="" class="details" rel="nofollow">Подробно</a>
	
	{if !$user_id}
		<!-- Кнопка ВВерх -->
		<div id="button_toTop"><div>Наверх</div></div>
	{/if}
	
</div>