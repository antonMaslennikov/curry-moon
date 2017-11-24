<div class="enduro_top_filter">
	Выберите Ваш {if $filters.category == 'jetski'}гидроцикл{elseif $filters.category == 'atv'}квадроцикл{elseif $filters.category=='snowmobile'}снегоход{else}мотоцикл{/if}
	<select class="manufacturer">
		{if $filters.category == 'enduro'}							
			<option value="/catalog/enduro/bmw/" {if $manufacturer == 'bmw'}selected="selected"{/if}>BMW</option>
			<option value="/catalog/enduro/gas_gas/" {if $manufacturer == 'gas_gas'}selected="selected"{/if}>Gas Gas</option>
			<option value="/catalog/enduro/honda/" {if $manufacturer == 'honda'}selected="selected"{/if}>Нonda</option>
			<option value="/catalog/enduro/husaberg/" {if $manufacturer == 'husaberg'}selected="selected"{/if}>husaberg</option>
			<option value="/catalog/enduro/husqvarna/" {if $manufacturer == 'husqvarna'}selected="selected"{/if}>husqvarna</option>
			<option value="/catalog/enduro/kawasaki/" {if $manufacturer == 'kawasaki'}selected="selected"{/if}>kawasaki</option>			
			<option value="/catalog/enduro/ktm/" {if $manufacturer == 'ktm'}selected="selected"{/if}>KTM</option>
			<option value="/catalog/enduro/sherco/" {if $manufacturer == 'sherco'}selected="selected"{/if}>sherco</option>			
			<option value="/catalog/enduro/suzuki/" {if $manufacturer == 'suzuki'}selected="selected"{/if}>suzuki</option>
			<option value="/catalog/enduro/tm/" {if $manufacturer == 'tm'}selected="selected"{/if}>tm</option>
			<option value="/catalog/enduro/yamaha/" {if $manufacturer == 'yamaha'}selected="selected"{/if}>yamaha</option>			
		{elseif $filters.category == 'jetski'}
			<option value="/catalog/jetski/hsr-benelli/" {if $manufacturer == 'hsr-benelli'}selected="selected"{/if}>hsr-benelli</option>
			<option value="/catalog/jetski/hydrospace/" {if $manufacturer == 'hydrospace'}selected="selected"{/if}>hydrospace</option>
			<option value="/catalog/jetski/kawasaki/" {if $manufacturer == 'kawasaki'}selected="selected"{/if}>kawasaki</option>
			<option value="/catalog/jetski/sea-doo/" {if $manufacturer == 'sea-doo'}selected="selected"{/if}>sea-doo</option>
			<option value="/catalog/jetski/yamaha/" {if $manufacturer == 'yamaha'}selected="selected"{/if}>yamaha</option>
		{elseif $filters.category == 'atv'}
			<option value="/catalog/atv/apex/" {if $manufacturer == 'apex'}selected="selected"{/if}>apex</option>
			<option value="/catalog/atv/artic/" {if $manufacturer == 'artic'}selected="selected"{/if}>artic</option>
			<option value="/catalog/atv/arctic_cat/" {if $manufacturer == 'arctic_cat'}selected="selected"{/if}>arctic cat</option>			
			<option value="/catalog/atv/bombardier/" {if $manufacturer == 'bombardier'}selected="selected"{/if}>bombardier</option>
			<option value="/catalog/atv/cannondale/" {if $manufacturer == 'cannondale'}selected="selected"{/if}>cannondale</option>
			<option value="/catalog/atv/can-am/" {if $manufacturer == 'can-am'}selected="selected"{/if}>Can-Am</option>			
			<option value="/catalog/atv/cobra/" {if $manufacturer == 'cobra'}selected="selected"{/if}>cobra</option>
			<option value="/catalog/atv/drr/" {if $manufacturer == 'drr'}selected="selected"{/if}>drr</option>
			<option value="/catalog/atv/honda/" {if $manufacturer == 'honda'}selected="selected"{/if}>honda</option>
			<option value="/catalog/atv/kawasaki/" {if $manufacturer == 'kawasaki'}selected="selected"{/if}>kawasaki</option>
			<option value="/catalog/atv/ktm/" {if $manufacturer == 'ktm'}selected="selected"{/if}>ktm</option>
			<option value="/catalog/atv/lem/" {if $manufacturer == 'lem'}selected="selected"{/if}>lem</option>
			<option value="/catalog/atv/pitster/" {if $manufacturer == 'pitster'}selected="selected"{/if}>pitster</option>
			<option value="/catalog/atv/polaris/" {if $manufacturer == 'polaris'}selected="selected"{/if}>polaris</option>
			<option value="/catalog/atv/suzuki/" {if $manufacturer == 'suzuki'}selected="selected"{/if}>suzuki</option>
			<option value="/catalog/atv/w-tec/" {if $manufacturer == 'w-tec'}selected="selected"{/if}>w-tec</option>
			<option value="/catalog/atv/yamaha/" {if $manufacturer == 'yamaha'}selected="selected"{/if}>yamaha</option>
		{elseif $filters.category == 'snowmobile'}
			<option value="/catalog/snowmobile/arctic_cat/" {if $manufacturer == 'arctic_cat'}selected="selected"{/if}>arctic cat</option>		
			<option value="/catalog/snowmobile/lynx/" {if $manufacturer == 'lynx'}selected="selected"{/if}>lynx</option>
			<option value="/catalog/snowmobile/polaris/" {if $manufacturer == 'polaris'}selected="selected"{/if}>polaris</option>			
			<option value="/catalog/snowmobile/skidoo/" {if $manufacturer == 'skidoo'}selected="selected"{/if}>skidoo</option>
			<option value="/catalog/snowmobile/timbersled/" {if $manufacturer == 'timbersled'}selected="selected"{/if}>timbersled</option>			
			<option value="/catalog/snowmobile/yamaha/" {if $manufacturer == 'yamaha'}selected="selected"{/if}>yamaha</option>
			<option value="/catalog/snowmobile/brp/" {if $manufacturer == 'brp'}selected="selected"{/if}>BRP</option>
		{/if}		
	</select>

	<span name="model" class="FAKEselectbox">
		<div class="select" style="">
			<div class="text"></div>
			<b class="trigger"><i class="arrow"></i></b>
		</div>
		<div class="dropdown" style="">
			<ul>
				{*<li class="selected">Модель</li>*}
				{*foreach from=$menu item="m" key="mkey"}
					<li value="{if !$m.years}{$m.id}{/if}" {if $m.years}group="{$mkey}"{/if}>{$m.name}</li>
				{/foreach*}
			</ul>
		</div>
	</span>

	<span name="year" class="FAKEselectbox" style="display:none;">
		<div class="select" style="">
			<div class="text"></div>
			<b class="trigger"><i class="arrow"></i></b>
		</div>
		<div class="dropdown" style="">
			<ul>
				{*<li>1915</li>
				<li class="selected">1945</li>
				{foreach from=$menu item="m" key="mkey"}
					{if $m.years}
						{foreach from=$m.years item="y"}
						<li value="{$y.id}" group="{$mkey}">{if $y.year}{$y.year}{else}&nbsp;{/if}</li>
						{/foreach}
					{/if}
				{/foreach*}
			</ul>
		</div>
	</span>
	
	<div id="send_all_stickers" class="input"></div>
	
	<div style="display:none;">
		<form id="send_form" method="post" action="#">
			<input type="hidden" value="626" name="style_id">
			<input type="hidden" value="7" name="work_type">
			<input type="hidden" value="" name="description">
			<input type="submit" title="Заказать дизайн" value=" " name="">
		</form>
	</div>
</div>
<script>
	{literal}
	var models = {};
	(function(){
	{/literal}
		var temp_models = [];
		
		{foreach from=$menu item="m" key="mkey"}
			{if $m.years}
				{foreach from=$m.years item="y"}
		temp_models.push([{$y.id},{$mkey}]);
				{/foreach}
			{/if}
		{/foreach}
	{literal}
		if(temp_models.length){
			for(var i = 0; i < temp_models.length; i++){
				for(var a = 0; a < temp_models[i][1].length; a++){
					if(typeof models[temp_models[i][1][a][0]] == "undefined")
						models[temp_models[i][1][a][0]] = [];
					models[temp_models[i][1][a][0]].push([temp_models[i][0],temp_models[i][1][a][1]]);
				}
			}
		}
	})();
	{/literal}
</script>
{literal}
<script>
	$('.enduro_top_filter select.manufacturer').change(function(){
		if ($(this).val().length > 0)
			location.href =$(this).val(); 
	});
	$('#send_all_stickers').click(function(){
		$('input[name=enduro_order_description]').val($('#send_form').find('input[name=description]').val());
			
		$('#enduroZakazBlock > .downloadPrice').show();
		$('#enduroZakazBlock > .downloadPrice-successful').hide();
		
		{/literal}
		tb_show('', '/#TB_inline?width={if !$MobilePageVersion}570{else}290{/if}&height={if !$MobilePageVersion}487{else}400{/if}&inlineId=enduroZakazBlock');
		{literal}
		return false;
	});
	$('.enduro_top_filter select').selectbox();
	
	$(document).ready(function(){
		window.filterStickers = function(nums){
			var s1 = $('.m12:first').find('input[name=description]').val();
			s1 = s1.substring(0,s1.indexOf('['));
			var s = [];
			if(typeof nums[0] != "undefined"){
				$('.m12').hide();
				for(var i in nums){
					var temp = $('.m12[im_id='+nums[i]+']').show().find('input[name=description]').val();
					s.push(temp.substring(temp.indexOf('[')+1,temp.indexOf(']')));
				}
			}else{
				$('.m12').each(function(){
					var temp = $(this).find('input[name=description]').val();
					s.push(temp.substring(temp.indexOf('[')+1,temp.indexOf(']')));
				});
				$('.m12').show();
			}
			$('#send_form').find('input[name=description]').val(s1+'['+s.join(',')+']');
		}
		window.setModel = function(key){
			$('span[name=year] ul li').remove();
			//заполняем годами из нашей модели
			var s = '';//<li title="title" class="selected">Выберите год</li>';
			$('span[name=year] div div.text').text('Выберите год');
			//парсим года в массив, сортируем и создаем список
			var years = [];
			var nums = [];
			var years_key = {};
			if(typeof key != "undefined")
			for(var i in models[key]){
				nums.push(models[key][i][0]);
				if(models[key][i][1] != ""){
					if(models[key][i][1].indexOf('-') > 0){ //диапазон
						var temp = models[key][i][1].split('-');
						for(var a = parseInt(temp[0]); a <= parseInt(temp[1]); a++){
							if(typeof years_key[a.toString()] == "undefined")
								years_key[a.toString()] = [];
							years_key[a.toString()].push(models[key][i][0]);
							years.push(a.toString());
						}
					}else{ //один год
						if(typeof years_key[models[key][i][1]] == "undefined")
							years_key[models[key][i][1]] = [];
						years_key[models[key][i][1]].push(models[key][i][0]);
						years.push(models[key][i][1]);
					}
				}else{
					if(typeof years_key['all'] == "undefined")
						years_key['all'] = [];
					years_key['all'].push(models[key][i][0]);
					years.push('all');
				}
			}
			years.sort(function(a,b){return parseInt(a)-parseInt(b);});
			for(var i in years){
				var val = '';
				if(typeof years_key[years[i]] != "undefined"){
					s += '<li val="'+years_key[years[i]].join(';')+'">'+(years[i]!='all'?years[i]:'Все года')+'</li>';
					delete years_key[years[i]];
				}
			}
			$('span[name=year] ul').append($(s));
			filterStickers(nums);
			if($('span[name=year] ul li').length == 1)
				$('span[name=year] ul li').click();
		}
		var s = '';
		for(var k in models){
			s += "<li val='"+k+"'>"+(k!='all'?k:'Универсальные')+"</li>";
		}
		$('span[name=model] ul').append($(s));
		$('span[name=model] div.text').text('Модель');
		$('span[name=model] .dropdown li').live('click',function(){
			$('span[name=year]').show();
			$(this).parent().find('li').removeClass('selected');
			$(this).addClass('selected');
			$(this).parents('span').find('div div.text').text($(this).text());
			setModel($(this).attr('val'));
		});
		$('span[name=year] .dropdown li').live('click',function(){
			//$(this).parent().find('li[title]')
			$(this).parent().find('li').removeClass('selected');
			$(this).addClass('selected');
			$(this).parents('span').find('div div.text').text($(this).text());
			if(typeof $(this).attr('val') != "undefined"){
				filterStickers($(this).attr('val').split(';'));
			}else{
				setModel($($('span[name=model] .dropdown li.selected')).attr('val'));
			}
		});
		filterStickers([]);
	});
	
</script>{/literal}