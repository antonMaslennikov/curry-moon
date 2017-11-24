<!--noindex-->
<div class="calcdeliv">
	<a class="show-link" rel="nofollow" href="#!/calculate-delivery">Калькулятор доставки</a>
	<div class="b-calc-form" style="display:none;">
		<div class="form-title">Кальтулятор доставки</div>
		<input type="text" class="input q-delivery-city-search" value="{$USER->city}" />
		<input type="hidden" class="input q-delivery-city" value="{$USER->city}" />
		<input type="hidden" name="good" value="" />
		<div class="delivery-details">
			<div class="line deliv_1" style="display:none"><b>Курьер (на след. день)</b><span>200 руб.</span></div>
			<div class="line deliv_2" style="display:none"><b>Самовывоз<u></u></b><span>200 руб.</span></div>
			<div class="line deliv_3" style="display:none"><b>В метро (Кольцевая)</b><span>100 руб.</span></div>
			<div class="line deliv_6" style="display:none"><b b="Курьером IML">Курьером IML</b><span>0 руб.</span></div>
			<div class="line deliv_7" style="display:none"><b b="Курьер до квартиры">Курьер до квартиры</b><span>0 руб.</span></div>
			<div class="line deliv_5" style="display:none"><b>Почта России (1-3 недели)</b><span>0 руб.</span></div>						
			<div class="line deliv_4" style="display:none"><b>Курьерская доставка DPD (<u></u>)</b><span>0 руб.</span></div>
			<div class="line deliv_8" style="display:none"><b b="Самовывоз из пункта выдачи">Самовывоз из пункта выдачи<u></u></b><span>0 руб.</span></div>
		</div>
	</div>
	<div style="clear:both"></div>
</div>
<!--/noindex-->
<style>
	.calcdeliv { margin-bottom: 15px; }
	.calcdeliv .b-calc-form { position:relative; }
	.calcdeliv .b-calc-form .input {
		width: 304px !important;
		color: #35A644;
	}
	.calcdeliv .gorod-r {
		position: absolute;
		right: 25px;
		top: 37px;
		color: #BCBCBC;
		border-bottom: 1px dotted #BCBCBC;
		font-size: 12px;text-decoration: none;
		line-height: 12px!important;
	}
</style>

<script>
delivCalc = {
	
	resultSort: function(){
		var rsort = [];
		$('.calcdeliv .b-calc-form .line:visible').each(function(){
			rsort.push({ self: $(this), sum: parseFloat($(this).find('span').text())});
		});
		rsort.sort(function(a,b){ return a.sum < b.sum; });
		for(var i=0;i<rsort.length;i++) {
			$('.calcdeliv .b-calc-form .delivery-details').prepend(rsort[i].self);
		}
	},
	
	resultSearchCity: function(item){
		
			$('.calcdeliv .q-delivery-city').val(item.id);
				
				$('.calcdeliv .b-calc-form .line').hide();
				
				if (parseInt(item.id) == 1) { // это Москва...
					$('.calcdeliv .deliv_3').show();
				} else $('.calcdeliv .deliv_3').hide();
				
				//IM-Logistics
				var amtime	= (item.IMlog && item.IMlog.time?item.IMlog.time:'');
				var amcost	= (item.IMlog && item.IMlog.cost?item.IMlog.cost:'0');
				if (parseFloat(amcost) > 0 || amtime.length > 0) {
					$(".calcdeliv .deliv_6 b").text($(".calcdeliv .deliv_6 b").attr('b')+(amtime.length>0?' ('+amtime+')':''));
					$(".calcdeliv .deliv_6 span").text(amcost+' руб.');
					$(".calcdeliv .deliv_6").show();
				} else $(".calcdeliv .deliv_6").hide();

				//Курьер до квартиры
				var balticktime	= (item.baltick && item.baltick.time?item.baltick.time:'');
				var baltickcost	= (item.baltick && item.baltick.cost?item.baltick.cost:'0');
				if (parseFloat(baltickcost) > 0 || balticktime.length > 0) {
					$(".calcdeliv .deliv_7 b").text($(".calcdeliv .deliv_7 b").attr('b')+(balticktime.length>0?' ('+balticktime+')':''));
					$(".calcdeliv .deliv_7 span").text(baltickcost+' руб.');
					$(".calcdeliv .deliv_7").show();
				} else $(".calcdeliv .deliv_7").hide();
				
				//Курьер
				var deliveryboytime = (item.deliveryboy && item.deliveryboy.time?item.deliveryboy.time:'');
				var deliveryboycost = (item.deliveryboy && item.deliveryboy.cost?item.deliveryboy.cost:'0');
				if (parseFloat(usercost) > 0 || deliveryboytime.length > 0) {
					$(".calcdeliv .deliv_1 u").text((deliveryboytime.length>0?' ('+deliveryboytime+')':''));
					$(".calcdeliv .deliv_1 span").text(deliveryboycost+' руб.');
					$(".calcdeliv .deliv_1").show();
				} else $(".calcdeliv .deliv_1").hide();

				//Самовывоз				
				var usertime = (item.user && item.user.time?item.user.time:'');
				var usercost = (item.user && item.user.cost?item.user.cost:'0');
				if (parseFloat(usercost) > 0 || usertime.length > 0) {
					$(".calcdeliv .deliv_2 span").text(usercost+' руб.');
					$(".calcdeliv .deliv_2 u").text((usertime.length>0?' ('+usertime+')':''));
					$(".calcdeliv .deliv_2").show();
				} else $(".calcdeliv .deliv_2").hide();

				//Cамовывоз из пункта выдачи
				var IMlog_selftime = (item.IMlog_self && item.IMlog_self.time?item.IMlog_self.time:'');
				var IMlog_selfcost = (item.IMlog_self && item.IMlog_self.cost?item.IMlog_self.cost:'0');
				if (parseFloat(IMlog_selfcost) > 0 || IMlog_selftime.length > 0) {
					$(".calcdeliv .deliv_8 u").text((IMlog_selftime.length>0?' ('+IMlog_selftime+')':''));
					$(".calcdeliv .deliv_8 span").text(IMlog_selfcost+' руб.');
					$(".calcdeliv .deliv_8").show();
				} else $(".calcdeliv .deliv_8").hide();
				
				
				//Курьерская доставка DPD
				var dpdtime = (item.dpd && item.dpd.time?item.dpd.time:'');
				var dpdcost = (item.dpd && item.dpd.cost?item.dpd.cost:'0');
				if (parseFloat(dpdcost) > 0 || dpdtime.length > 0) {
					$(".calcdeliv .deliv_4 b u").text(dpdtime);
					$(".calcdeliv .deliv_4 span").text(dpdcost+' руб.');
					$(".calcdeliv .deliv_4").show();
				} else $(".calcdeliv .deliv_4").hide();
				
				//Почта России (1-3 недели)
				var posttime = (item.post && item.post.time?item.post.time:'');
				var postcost = (item.post && item.post.cost?item.post.cost:'0');
				if (parseFloat(postcost) > 0 || posttime.length>0) {
					$(".calcdeliv .deliv_5 b u").text(posttime);
					$(".calcdeliv .deliv_5 span").text(postcost+' руб.');
					$(".calcdeliv .deliv_5").show();
				} else $(".calcdeliv .deliv_5").hide();
				
				delivCalc.resultSort();
				try {
					$.cookie("basket-city-delivery", item.name, { expires: 7, path: '/' } );
				} catch(e) {}
	
	},
	initClac: function(){
		$(".calcdeliv .q-delivery-city-search").autocomplete('/ajax/?action=city_search'+($('.calcdeliv input[name="good"]').val().length>0?'&good='+$('.calcdeliv input[name="good"]').val():''),{
			parse: function(data){ 
				var tmp = eval(data); 
				parsed_data = []; 
				   for (i=0; i < tmp.length; i++) {
					 obj = tmp[i]; 
					 parsed_data[i] = { data: obj , value: obj.id, result: obj.name }; 
				   } 
				   return parsed_data;
			},
			formatItem: function(response, i, max) { return response.name; },
			formatResult: function(response) { return response.name; }	
		}).result( function(event, item) {
			
			if (typeof(yaCounter265828) != "undefined")
				yaCounter265828.reachGoal('Customize_ND_change_city','калькулятор в табе');//трекинг события яша
			
			delivCalc.resultSearchCity(item);
		});
	}
}
	
$(document).ready(function(){
	
	if (typeof(goodForm) != "undefined" && typeof(goodForm.curCategory) != "undefined")
		$('.calcdeliv input[name="good"]').val(goodForm.curCategory);

	$(".calcdeliv .q-delivery-city-search").on(delivCalc.initClac());
	
	/*
	$(".calcdeliv .q-delivery-city-search").autocomplete('/ajax/?action=city_search',{
		parse: function(data){ 
			var tmp = eval(data); 
			parsed_data = []; 
			   for (i=0; i < tmp.length; i++) {
				 obj = tmp[i]; 
				 parsed_data[i] = { data: obj , value: obj.id, result: obj.name }; 
			   } 
			   return parsed_data;
		},
		formatItem: function(response, i, max) { return response.name; },
		formatResult: function(response) { return response.name; }	
	}).result( function(event, item) {
		
		if (typeof(yaCounter265828) != "undefined")
			yaCounter265828.reachGoal('Customize_ND_change_city','калькулятор в табе');//трекинг события яша
				
		delivCalc.resultSearchCity(item);
	});
	*/
	
	
	
	$('.calcdeliv a.show-link:first').click(function(){
		$(this).hide();
		$('.calcdeliv .b-calc-delivery').hide();
		$('.calcdeliv .b-calc-form').show();
//	
		//грузим первый раз
		$.get('/ajax/?action=city_search&q='+$('.calcdeliv .q-delivery-city-search').val()+($('.calcdeliv input[name="good"]').val().length>0?'&good='+$('.calcdeliv input[name="good"]').val():''),function(item){ if (typeof item == 'string') item = eval(item); if (item) delivCalc.resultSearchCity(item[0]); });
		//Санкт-петербург
		$('.calcdeliv .gorod-r').unbind('click').click(function(e){ 
			$('.calcdeliv .q-delivery-city-search').val($(this).text()); 
			$.get('/ajax/?action=city_search&q='+$(this).text(),function(item){ if (typeof item == 'string') item = eval(item); if (item) delivCalc.resultSearchCity(item[0]); });
			return false; 
		});
		return false;
	});
	
	$('.calcdeliv a.show-link:first').click();
});
</script>