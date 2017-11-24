<div class="good-tpl-stickerset">
	<div id="stickerset_icon_descr"></div>
	<div class="icon clearfix">	<!--noindex-->
		<label class="pl activ clearfix">
			<div class="ic"></div>	
			<div class="textI">
				{*<input type="radio" name="data67075" checked="checked" class="radio" value="743160" _oldprice="200" _price="230" _returned="9" _author="65">*}
				<font>{if $modal}230 руб{else}<br replaces="230 руб"/>{/if}</font><br>
				<span>{if $modal}самоклеющаяся<br>пленка{else}<br replaces="самоклеющаяся<br>пленка"/>{/if}</span>
			</div>
		</label>
		<label class="za clearfix">
			<div class="ic"></div>	
			<div class="textI">	
				{*<input type="radio" name="data67075" class="radio" value="743679" _oldprice="530" _price="530" _returned="34" _author="265">*}
				<font>{if $modal}530 руб{else}<br replaces="530 руб"/>{/if}</font><br>
				<span>{if $modal}с защитным<br>слоем{else}<br replaces="с защитным<br>слоем"/>{/if}</span>
			</div>
		</label>
		<!--/noindex-->
	</div>	
</div>

{*literal}
<script type="text/javascript">
	$(document).ready(function(){
		window.setCurrentStickerSet = function(c){
			var key = 'pl';
			var gender = '537';
			switch(c){
				case 'pl':
					key = 'pl';
					gender = '537';
					break;
				case 'za':
					key = 'za';
					gender = '584';
					break;
			}
			$('.good-tpl-stickerset label').removeClass('activ');
			$('.good-tpl-stickerset label.'+key).addClass('activ');
			goodForm.curGender = gender;
			var data = goodForm.getDesignData();
			for(var i in data['sizes']){
				goodForm.curSize = i;
				for(var a in data['sizes'][i]['colors']){
					goodForm.curColor = a;
					break;
				}
				break;
			}
			$('#stickerset_icon_descr').html(data.style_description);
			//console.log(c,key,goodForm.curGender,goodForm.curSize,goodForm.curColor);
			goodForm.changePrice();
		}
		if(goodForm.curCategory == 'stickerset'){
			setCurrentStickerSet('pl');
		}
		$('.good-tpl-stickerset label').click(function(){
			setCurrentStickerSet($(this).hasClass('pl')?'pl':'za');
		});
	});
</script>
{/literal*}