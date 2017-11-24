<div id="selfdelivery-map" style="width:600px; height:500px"></div>

<input type="hidden" name="map-city" value="{$city_name} " />
<input type="hidden" name="map-address" value="{$point.address} " />
<input type="hidden" name="service" value="{$service}" />

{if $point.schema != ''}
	<p>
	<b>Как добраться</b>:<br />
	{$point.schema}
	</p>
{/if}

{literal}
<script>

	$(document).ready(function(){

		var city    = $('input[name=map-city]').val();
		var address = $('input[name=map-address]').val();
		var service = $('input[name=service]').val();
		
		ymaps.ready(function(){
		
			ymaps.geocode(city + address, {results:1}).then(function(res)
			{
				var firstGeoObject=res.geoObjects.get(0);
	
				window.pickupMap=new ymaps.Map("selfdelivery-map",{center:firstGeoObject.geometry.getCoordinates(),zoom:{/literal}{if $point.address == ''}10{else}15{/if}{literal}});
				pickupCollection=new ymaps.GeoObjectCollection();
				
				{/literal}
				{foreach from=$points item="p" key="k"}
				{literal}
					ymaps.geocode(city + " {/literal}{$p.address}{literal}",{boundedBy:pickupMap.getBounds(),results:1}).then(function(res){var coordinates=res.geoObjects.get(0).geometry.getCoordinates();
					var placemark=new ymaps.Placemark(coordinates,{balloonContentHeader:"{/literal}{$p.name}{literal}",balloonContentBody:"<p style='padding:8px;text-align:center'><a class='choose_point' data-id='{/literal}{$p.id}{literal}' href='#'>Забрать отсюда</a></p>",balloonContentFooter:"{/literal}{$p.address}{literal}"});
					pickupMap.geoObjects.add(placemark);});
				{/literal}
				{/foreach}
				{literal}
				
				pickupMap.controls.add("zoomControl",{top:80,left:5});
			});
			
		});
		
		$(".choose_point").live("click",function(){
			point_id=$(this).attr("data-id");
			$("select[name=" + service + "_point] option[point_id="+point_id+"]").attr("selected","selected");
			//$("select[name=" + service + "_point]").trigger("change");
			$('#' + service + '_point_address').show().children('span').text($('select[name=' + service + '_point]').children(':selected').attr('address'));
			tb_remove();
		});
	
	});
</script>
{/literal}
