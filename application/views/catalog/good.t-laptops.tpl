<div class="good-tpl-laptop">
	<ul>
	</ul>
	<div class="laptop-params clearfix">
		<div class="clearfix" style="display:none">
			<div class="round-corner">
				<div class="laptop-border-round"><input id="silicon-bumper" type="radio" name="laptop-border" checked="checked" class="options" value="round"></div>
				<label for="silicon-bumper">{if $modal}{$L.GOOD_round_corner}{else}<br replaces="{$L.GOOD_round_corner}"/>{/if}</label>
			</div>
			<div class="square-corner">
				<div class="laptop-border-square"><input id="silicon-bumper1" type="radio" name="laptop-border" value="square"></div>
				<label for="silicon-bumper1">{if $modal}{$L.GOOD_square_corner}{else}<br replaces="{$L.GOOD_square_corner}"/>{/if}</label>
			</div>
		</div>
		<div>
			 <div id="laptops-select"><!--noindex-->
				<select class="laptop-list">
					{assign var="prev" value=""}					
					{foreach from=$styles.laptops item="p" name="laptop"}
						<option value="{$p.style_id}" hash="{$p.style_slug}" id="{$p.style_id}">{if $modal}{$p.style_name}{/if}</option>
					{/foreach}
				</select><!--/noindex-->
			</div>
			<div class="laptop-sizes clearfix">
				<!--noindex--><ul>
					{foreach from=$styles.laptops item="p" name="laptop"}
						{foreach from=$p.sizes item="size" key="size_id"}
						<li class="auto-active" laptop="{$p.style_id}" value="{$size_id}" style="display:none">
							<span>{if $modal}{$size.en}{else}<br replaces="{$size.en}"/>{/if}</span>
						</li>
						{/foreach}
					{/foreach}
				</ul><!--/noindex-->
			</div>
			<!--noindex-->
			<div class="laptop-size clearfix" style="margin:-5px 0 0 20px;width: 350px">
				<div style="color:red; visibility:hidden;" id="laptops-sizes-error">{if $modal}{$L.GOOD_specify_size_alert}{else}<br replaces="{$L.GOOD_specify_size_alert}"/>{/if}</div>				
				<div class="label">{if $modal}{$L.GOOD_specify_size}*{else}<br replaces="{$L.GOOD_specify_size}*"/>{/if}</div>
				<div class="two-inputs clearfix">						
					<input name="width" id="exact_width" maxlength="4" style="color: rgb(128, 128, 128);text-align:center;" placeholder="{$L.GOOD_width}" onfocus="$(this).attr('placeholder',null);" onblur="$(this).attr('placeholder','{$L.GOOD_width}');" />
					<i>X</i>
					<input name="height" id="exact_height" maxlength="4" style="color: rgb(128, 128, 128);text-align:center;" placeholder="{$L.GOOD_height}" onfocus="$(this).attr('placeholder',null);" onblur="$(this).attr('placeholder','{$L.GOOD_height}');" />					
				</div>
			</div><!--/noindex-->
		</div>
	</div>
</div>

{*
<script type="text/javascript">
	$(document).ready(function(){
		
		function RebuildLaptop() { 
			
			if ($('.good-tpl-laptop .laptop-sizes li.laptop-active').length==0)
				$('.good-tpl-laptop .laptop-sizes li:first:visible').addClass('laptop-active');
				
			goodForm.curGender = $('.good-tpl-laptop select.laptop-list').val();
			goodForm.curSize = $('.good-tpl-laptop .laptop-sizes li.laptop-active').attr('value');

			if (goodForm.curCategory == "laptops" && goodForm.curGender == "229")
				$('.good-tpl-laptop .laptop-size').show();
			else $('.good-tpl-laptop .laptop-size').hide();
			
            //goodForm.buildFirst();
			goodForm.changePrice();
			goodForm.makeBreadCrumps();
			
			var data = goodForm.getDesignData();
			if (data) {
				var d = $('.good-tpl-laptop');
				d.find('ul:first').remove();
				d.prepend(data.style_composition);
				//строим меню подарков
				if((typeof data['gifts'] != "undefined") && (data['gifts'].length)){
					var cont = $('#gifts_list').empty();
					if(!cont.length){
						cont = $('<div id="gifts_list">').insertBefore($('#laptops-select').parent());
					}
					for(var i in data['gifts']){
						var elem = $('<input type="checkbox" '+(data['gifts'][i]['checked']?'checked="checked"':'')+' style="margin:5px;" value="'+data['gifts'][i]['gift_id']+'"><span>'+data['gifts'][i]['gift_name']+' '+data['gifts'][i]['gift_price']+' руб.</span><br>');
						elem.filter('input').change((function(gift){
							return function(){
								if($(this).attr('checked')){
									gift['checked'] = true;
								}
								else{
									delete gift['checked'];
								}
								
								for(var i in goodForm.designData['laptops']){
									for(var a in goodForm.designData['laptops'][i]['sizes']){
										for(var b in goodForm.designData['laptops'][i]['sizes'][a]['colors']){
											if(typeof goodForm.designData['laptops'][i]['sizes'][a]['colors'][b]['temp_price'] != "undefined"){
												goodForm.designData['laptops'][i]['sizes'][a]['colors'][b]['price'] = parseFloat(goodForm.designData['laptops'][i]['sizes'][a]['colors'][b]['temp_price']);
												delete goodForm.designData['laptops'][i]['sizes'][a]['colors'][b]['temp_price'];
											}
											if(typeof goodForm.designData['laptops'][i]['sizes'][a]['colors'][b]['temp_price_old'] != "undefined"){
												goodForm.designData['laptops'][i]['sizes'][a]['colors'][b]['price_old'] = parseFloat(goodForm.designData['laptops'][i]['sizes'][a]['colors'][b]['temp_price_old']);
												delete goodForm.designData['laptops'][i]['sizes'][a]['colors'][b]['temp_price_old'];
											}
										}
									}
								}
								
								var data = goodForm.getDesignData();
								var price_gifts = 0;
								for(var i in data['gifts']){
									if((typeof data['gifts'][i]['checked'] != "undefied") && data['gifts'][i]['checked']){
										price_gifts += data['gifts'][i]['price'];
									}
								}
								for(var s in data['sizes']){
									data['sizes'][s]['colors']['0']['temp_price_old'] = parseFloat(data['sizes'][s]['colors']['0']['price_old']);
									data['sizes'][s]['colors']['0']['price_old'] = price_gifts + data['sizes'][s]['colors']['0']['temp_price_old'];
									data['sizes'][s]['colors']['0']['temp_price'] = parseFloat(data['sizes'][s]['colors']['0']['price']);
									data['sizes'][s]['colors']['0']['price'] = price_gifts + data['sizes'][s]['colors']['0']['temp_price'];
								}
								goodForm.changePrice();
							}
						})(data['gifts'][i]));
						cont.append(elem);
					}
				}
				else{
					$('#gifts_list').remove();
				}
			}
		}
		
		//debugger;
		if($('.good-tpl-laptop select.laptop-list').length > 0){
			$('.good-tpl-laptop select.laptop-list').unbind('change').bind('change',function(){
				
				$('.good-tpl-laptop .laptop-sizes li').removeClass('laptop-active').hide();
				$('.good-tpl-laptop .laptop-sizes li[laptop='+$(this).val()+']').show();
				
				$('.good-tpl-laptop .laptop-sizes li[laptop='+$(this).val()+']:first').addClass('laptop-active');
				
				RebuildLaptop();
			});
			if (goodForm.curCategory == 'laptops') $('.good-tpl-laptop select.laptop-list').change();
			
			$('.good-tpl-laptop .laptop-sizes li').unbind('click').click(function(){
				$(this).parent().find('li').removeClass('laptop-active');
				$(this).addClass('laptop-active');
				
				RebuildLaptop();
			});
			//RebuildLaptop();
		}
		
	});
</script>*}