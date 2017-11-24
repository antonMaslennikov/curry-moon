{if BASKETS && $BASKETS.0.user_basket_delivery_type == "IMlog_self"}

	<input type="hidden" name="map-city" value="{$BASKETS.0.city_name} " />
	<input type="hidden" name="map-address" value="{$BASKETS.0.self_delivery_point} " />
	
	{literal}
	
	<script type='text/javascript' src='/js/jquery.js'></script>
	<script type="text/javascript" src="http://api-maps.yandex.ru/2.0-stable/?load=package.standard&lang=ru-RU"></script>
	
	<script>
	
		$(document).ready(function(){
	
			var city    = $('input[name=map-city]').val();
			var address = $('input[name=map-address]').val();
			//var service = $('input[name=service]').val();
			
			ymaps.ready(function(){
			
				ymaps.geocode(city + address, {results:1}).then(function(res)
				{
					var firstGeoObject=res.geoObjects.get(0);
		
					window.pickupMap=new ymaps.Map("YMapsID-3420",{center:firstGeoObject.geometry.getCoordinates(),zoom:16});
					pickupCollection=new ymaps.GeoObjectCollection();
					
					ymaps.geocode(city + ' ' + address, {boundedBy:pickupMap.getBounds(),results:1}).then(function(res){var coordinates=res.geoObjects.get(0).geometry.getCoordinates();
					var placemark=new ymaps.Placemark(coordinates,{balloonContentHeader:address});
					pickupMap.geoObjects.add(placemark);});
					
					pickupMap.controls.add("zoomControl",{top:80,left:5});
				});
				
			});
			
		});
	</script>
	{/literal}
{else}
	{literal}
	<script src="http://api-maps.yandex.ru/1.1/?key=AJIn9UwBAAAAjhdKKwIAX8yXsoDpgeWwQXlDzID8bHxpp6EAAAAAAAAAAACfDZGShPAhoH12Lj8ggiH0cXLMig==&wizard=constructor" type="text/javascript"></script>
	
	<script type="text/javascript">
	    YMaps.jQuery(window).load(function () {
	        var map = new YMaps.Map(YMaps.jQuery("#YMapsID-3420")[0]);
	        map.setCenter(new YMaps.GeoPoint(37.68463,55.772289), 16, YMaps.MapType.MAP);
	        map.addControl(new YMaps.Zoom());
	        map.addControl(new YMaps.ToolBar());
	        map.addControl(new YMaps.TypeControl());
	
	        YMaps.Styles.add("constructor#pmlbmPlacemark", {
	            iconStyle : {
	                href : "http://api-maps.yandex.ru/i/0.3/placemarks/pmlbm.png",
	                size : new YMaps.Point(28,29),
	                offset: new YMaps.Point(-8,-27)
	            }
	        });
	
	
	        YMaps.Styles.add("constructor#FF3732c85Polyline", {
	            lineStyle : {
	                strokeColor : "FF3732c8",
	                strokeWidth : 5
	            }
	        });
	       map.addOverlay(createObject("Placemark", new YMaps.GeoPoint(37.681594,55.771533), "constructor#pmlbmPlacemark", "Макдональдс"));
	       map.addOverlay(createObject("Placemark", new YMaps.GeoPoint(37.685349,55.771769), "constructor#pmlbmPlacemark", "Cупермаркет Перекрёсток"));
	       map.addOverlay(createObject("Placemark", new YMaps.GeoPoint(37.687538,55.773347), "constructor#pmlbmPlacemark", "Указатели автосервис WV, BMW"));
	       map.addOverlay(createObject("Placemark", new YMaps.GeoPoint(37.688858,55.772749), "constructor#pmlbmPlacemark", "Ручной шлагбаум, большие серые ворота, охране сказать что в Мэриджейн. Ищите на территории зеленые стрелочки Maryjane --->><br/>"));
	       map.addOverlay(createObject("Placemark", new YMaps.GeoPoint(37.690145,55.771956), "constructor#pmlbmPlacemark", "Вы пришли!. Это не так сложно как казалось! ул. Малая Почтовая д.12, стр.20, 5-й этаж.  (На Яндекс Картах это д.12 стр. 3)"));
	       map.addOverlay(createObject("Polyline", [new YMaps.GeoPoint(37.678902,55.772317),new YMaps.GeoPoint(37.681048,55.771422),new YMaps.GeoPoint(37.686133,55.77164),new YMaps.GeoPoint(37.68742,55.773358),new YMaps.GeoPoint(37.688837,55.772704),new YMaps.GeoPoint(37.689395,55.77268),new YMaps.GeoPoint(37.689309,55.772317),new YMaps.GeoPoint(37.689995,55.772317),new YMaps.GeoPoint(37.690028,55.771985)], "constructor#FF3732c85Polyline", "10-15 минут пешком от метро"));
	        
	        function createObject (type, point, style, description) {
	            var allowObjects = ["Placemark", "Polyline", "Polygon"],
	                index = YMaps.jQuery.inArray( type, allowObjects),
	                constructor = allowObjects[(index == -1) ? 0 : index];
	                description = description || "";
	            
	            var object = new YMaps[constructor](point, {style: style, hasBalloon : !!description});
	            object.description = description;
	            
	            return object;
	        }
	    });
	</script>
	{/literal}
{/if}

{literal}
<style type="text/css">
#content{width:100%!important;}
#rightcol {display:none;}
.box-about-map {float:left; width:710px; color:#504f4f;}
.box-about-map .main-title, .b-about-foot .main-title {font-size:32px; font-family:'MyriadPro-CondIt'; margin:28px 0 33px;}

.about-map-sidebar {float:right; width:230px;}
.btn-print {display:block; background:url(/images/about/btn-print.gif) no-repeat 0 2px; padding: 2px 0 2px 21px; margin:38px 0;}
.about-map-sidebar .b-title-g {font-size:24px; font-weight:normal; font-family:'MyriadPro-CondIt'; margin:12px 0;}
.about-map-sidebar .b-text-about {font-size:14px; color:#504f4f; margin:0 0 20px;}

.box-about-map .b-address-box {float: left;	width:220px; margin:0 15px 0 0; padding: 0 0 0 8; font-size:14px;}
.box-about-map .b-address-box.contact-tel-email{width: 210px;margin: 0 10px 0 7px;}

.b-address-box .b-tlt {display:block; margin:0 0 15px;}
.b-address-box .b-descr {display:block;}
.box-about-map .vremy-raboti {width:245px; margin:0}
.box-about-map .vremy-raboti .b-descr{text-align: right;}

.b-descr a {color:#504f4f; text-decoration:none;}
.b-descr a:hover {text-decoration:underline;}
.b-descr .link-soc {display:block; padding:5px 0 5px 22px; border-top:1px solid #e3e3e3; text-transform:uppercase; color:#535758; text-decoration:underline;}
.b-descr .rss-link {background:url(/images/social/rss.gif) no-repeat 0 5px;}
.b-descr .lj-link {background:url(/images2/social/jj.gif) no-repeat 0 5px;}
.b-descr .tw-link {background:url(/images2/social/twitter.gif) no-repeat 0 5px;}
.b-descr .fb-link {background:url(/images/social/fcb.gif) no-repeat 0 5px; border-bottom:1px solid #e3e3e3;}
.b-descr .img-download { float:left; margin:35px 71px 0;}

.box-about-map dd {margin-left:0}

/* Футер Абоут */
.b-about-foot {float:left; width:100%; margin:30px 0 0; color:#504f4f; font-size:12px; }
.b-about-foot .oneof3-col {float:left; width:300px; margin: 0 40px 0 0;}
.b-about-foot .third-col {margin:0;}
.b-about-foot .first-col p {padding:0 0 0 15px;}

/* Стили для печати */
@media print{
	.p-head-cont,.mainmenu-wrap, .tabz, .b-footer_v2, .need-hepl { display:none; }
	#content{color:#000!important;}
}
</style>
{/literal}

<div class="box-about-map pa10">
	
	<h2 class="main-title">Как к нам добраться</h2>
	
	<dl class="b-address-box">
		<dt class="b-tlt">Адреса:</dt>
		<dd class="b-descr">
			{if BASKETS && $BASKETS.0.user_basket_delivery_type == "IMlog_self"}
				г. {$BASKETS.0.city_name}, {$BASKETS.0.self_delivery_point}
			{else}
				г.&nbsp;Москва, ул.&nbsp;Малая Почтовая, д.&nbsp;12, стр.&nbsp;3, корп.&nbsp;20 5-й этаж. <br/>
		        Вам на 5-й этаж, лифт работает до 21:00, офис до 21:00/ 
				Перед приездом проверьте приходила ли Вам смс о готовности.
			{/if}
		</dd>
	</dl>
	
	<dl class="b-address-box contact-tel-email">
		<dt class="b-tlt">Контакты:</dt>
		<dd class="b-descr">
		Тел.:.........+7 (495) 229-30-73<br/>
		Skype:......<a href="skype:maryjane_ru?chat" title="Открыть чат в Skype">maryjane_ru</a><br/>
		Почта:......<a href="mailto:info@maryjane.ru" title="Написаьт письмо на электронную почту Мериджей">info@maryjane.ru</a>		
		</dd>
	</dl>
	
	<dl class="b-address-box vremy-raboti">
		<dt class="b-tlt">Время работы склада:</dt>
		<dd class="b-descr">
		{$operation_time}
		</dd>
	</dl>
	
	<div id="YMapsID-3420" style="width:710px; height:450px; float:left; margin: 40px 0;"></div>
</div>

<br clear="all">

{if BASKETS && $BASKETS.0.user_basket_delivery_type == "IMlog_self"}
{else}
	<div class="pa10">
	<table width="700" border="0">
	  <tr>
	    <td width="60%"><h3 class="b-title-g">Пешком</h3>
		<p class="b-text-about">850 м от метро.</p>
		<p class="b-text-about">Ориентиры:</p>
		<p class="b-text-about">1. Макдональдс</p>
		<p class="b-text-about">2. Супермаркет "Перекрёсток" <br/>За супермаркетом перейти дорогу, повернуть налево</p>
		<p class="b-text-about">3. Проходная бизнес-центр "МЗАТЕ" <br/>Охране сказать "в Мериджейн". Далее на территории будут зеленые таблички указатели.</p>
		<p class="b-text-about">4. Высокое серое здание это и есть строение 3 корп. 20. Вам на 5-й этаж.</p>
		<p class="b-text-about">У нас тут страшно и темно, поэтому девочки заказывайте доставку курьером -)</p></td>
	    <td>&nbsp;&nbsp;</td>
	    <td valign="top"><h3 class="b-title-g">На автомобиле</h3>
		<p class="b-text-about"><strong>Внимание!</strong> Заезд на территорию МЗАТЕ-2 с <strong>ул.&nbsp;Большая Почтовая д.1 стр.33</strong><br/>
		Проезжаете на территорию через "ручной" шлагбаум, говорите охране "в Мериджейн" и далее по зеленым табличкам до самого высокого серого здания на территории. Вам на 5-й этаж.</p></td>
	  </tr>
	</table>
	</div>
{/if}
	

{foreach from=$BASKETS item="b"}
<h2>Заказ #{$b.user_basket_id}</h2>
<style>
	.order tr th, .order tr td {ldelim} border-bottom: 1px solid #000; vertical-align:middle {rdelim}
</style>

<div class="moduletable pa10">
	<h3>Заказанные продукты</h3>
	<table width="100%"cellpadding="5" cellspacing="0" class="order">
	<tr>
		<th colspan="2" align="left">Продукт</th>
		<th align="left">Носитель</th>
		<th width="60">Размер</th>
		<th width="60">Цена</th>
		<th width="60">Скидка</th>
		<th width="50">Кол-во</th>
		<th width="190">Цена со скидкой</th>
	</tr>
	{foreach from=$b.goods item="g"}
	<tr class="list_goods_tr">
		<td width="100"><img src="{$g.imagePath}" width="90" alt="{$g.goodName}" /></td>
		<td style="text-align:left;"><h3>{$g.goodName}</h3></td>
		<td>{$g.styleName}</td>
		<td><h3 style="text-align: center">{$g.size}</h3></td>
		<td align="center">{$g.price}.-</td>
		<td><h3 style="text-align: center">{$g.discount} %</h3></td>
		<td align="center">{$g.quantity}</td>
		<td><h2 class="priceTotal">{$g.priceTotal}.-</h2></td>
	</tr>
	{/foreach}
	
	{foreach from=$b.gifts item="g"}
	<tr class="list_gift_tr">
		<td style="text-align:left;"><img src="{$g.picture_path}" alt="{$g.gift_name}" /></td>
		<td>{$g.gift_name}</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>{$g.price}.-</td>
		<td>&nbsp;</td>
		<td>{$g.user_basket_good_quantity}</td>				
		<td><h2 class="priceTotal">{$g.priceTotal}.-</h2></td>
	</tr>
	{/foreach}
	</table>
	<div style="font-size:110%;margin-top:25px" align="right">
		<strong>
		Подитог: {$b.total.totalPriceWithoutDisc}<br>
		Cкидка: {$b.total.totalDiscount}<br />
		Оплачено бонусами: {$b.total.particalPay}<br />
		Стоимость доставки: {$b.total.deliveryPrice}<br />
		Итого: {$b.total.totalPrice}<br>
		</strong>
	</div>
	<!-- END total -->
{/foreach}