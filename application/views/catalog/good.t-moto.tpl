<div class="good-tpl-moto">
	<ul>
		<li>Авторская наклейка на бак, защищена  слоем пленки-ламината</li>
		<li>Защитит лакокрасочное покрытие от царапин</li>
		<li>Придает уникальный вид мотоциклу</li>		
		{*<li>Придайте уникальный вид Вашему мотоциклу</li>
		<li>Наклейка на бак, защитит лакокрасочное покрытие  бака от царапин и придает ему уникальный вид</li>
		<li>Наклейка защищена толстым слоем пленки-ламината!</li>*}
		<li>Клеится без пузырьков, удаляется без следа</li>
		<li>Гарантия 5 лет</li>
	</ul>
	<div class="moto-params clearfix">
		<!-- Слайдер -->
		<div class="moto_slider">
			<!-- Кнопка скрола влево -->
			<a href="#!/" class="btn-move-left" rel="nofollow"></a>
			<div class="slider-container">
				<div class="left-opacity"></div>
				<div class="slider-items-container">
					<div class="slider-items">
						<a href="#fuel-tank-sticker-7" _sid="467" rel="nofollow" class="slider-item s10 {*on*}" title=""></a>
						<a href="#fuel-tank-sticker" _sid="492" rel="nofollow" class="slider-item s1 " title=""></a>						
						<a href="#fuel-tank-sticker-1" _sid="569" rel="nofollow" class="slider-item s2 " title=""></a>
						<a href="#fuel-tank-sticker-2" _sid="570" rel="nofollow" class="slider-item s3" title=""></a>
						<a href="#fuel-tank-sticker-3" _sid="571" rel="nofollow" class="slider-item s4" title=""></a>
						<a href="#fuel-tank-sticker-4" _sid="572" rel="nofollow" class="slider-item s5" title=""></a>
						<a href="#fuel-tank-sticker-5" _sid="573" rel="nofollow" class="slider-item s6" title=""></a>
						{*<a href="#fuel-tank-sticker-6" _sid="574" rel="nofollow" class="slider-item s7" title=""></a>*}
						<a href="#fuel-tank-sticker-9" _sid="590" rel="nofollow" class="slider-item s8" title=""></a>
						<a href="#fuel-tank-sticker-8" _sid="589" rel="nofollow" class="slider-item s9" title=""></a>						
					</div>
				</div>
				<div class="right-opacity"></div>
			</div>
			<!-- Кнопка скрола вправо -->
			<a href="#!/" class="btn-move-right" rel="nofollow"></a>
		</div>

		<div class="moto-size clearfix"><!--noindex-->
			<div style="color:red;visibility:hidden" id="moto-sizes-error">{if $modal}Неверный размер{else}<br replaces="Неверный размер"/>{/if}</div>				
			<div class="label">{if $modal}Максимальная высота 24 см.{else}<br replaces="Максимальная высота 24 см."/>{/if}</div>
			<div class="inputs">						
				<input name="width" id="exact_width" maxlength="4" disabled="disabled" readonly="readonly" placeholder="{$L.GOOD_width_top}" onfocus="$(this).attr('placeholder',null);" onblur="$(this).attr('placeholder','{$L.GOOD_width_top}');" />
				<i>X</i>
				<input type="text" name="height" id="exact_height" maxlength="4"  placeholder="{$L.GOOD_height}" onfocus="$(this).attr('placeholder',null);" onblur="$(this).attr('placeholder','{$L.GOOD_height}');" data-default="210" value="210" min="180" max="240" />
					<span class="g-co" style="display: inline;">
						<a class="change_input one-more" href="#">+</a>
						<a class="change_input one-less" href="#">-</a>
					</span>
				<i>X</i>
				<input name="width2" id="exact_width2" maxlength="4" disabled="disabled" readonly="readonly" placeholder="{$L.GOOD_width_bottom}" onfocus="$(this).attr('placeholder',null);" onblur="$(this).attr('placeholder','{$L.GOOD_width_bottom}');" />
				<div style="clear:left"></div>
			</div>
			<div class="inputs-text-bottom">
				<div>{if $modal}{$L.GOOD_width_top}{else}<br replaces="{$L.GOOD_width_top}"/>{/if}</div>
				<div class="h">{if $modal}{$L.GOOD_height}{else}<br replaces="{$L.GOOD_height}"/>{/if}</div>
				<div>{if $modal}{$L.GOOD_width_bottom}{else}<br replaces="{$L.GOOD_width_bottom}"/>{/if}</div>
			</div>
			<!--/noindex-->
		</div>
	</div>
</div>

{*<script type="text/javascript">
	$(document).ready(function(){
		
		goodForm.callback.push('setcategory', function(){
			if (goodForm.curCategory == 'moto' || goodForm.curGender == 'moto') {
				$('#big-img-canvas').show();
			} else {
				$('#big-img-canvas').hide();
			}
		});
		
		function drawSize(w,h,w1){
			var canvas = $('#big-img-canvas');
			if (canvas.length == 0) {
				var img = $('#big-img');
				canvas = $('<canvas id="big-img-canvas" width="'+img.width()+'" height="'+img.height()+'"></canvas>').css({ zIndex: '10', position: 'absolute', left: '0px', top: '0px' });
				$('#big-img').parent().append(canvas);
			}
			canvas = canvas[0];
			if (canvas.getContext){
				var ctx = canvas.getContext('2d');
			 
				var top = 50;
				var bottom = 10;
				var textLength = 60;
				var range = { w: 300, h: 400, w1: 150 };
				var pik = 10;
				
				ctx.beginPath();

				ctx.clearRect(0, 0, canvas.width, canvas.height);
				ctx.lineWidth = 1;
				ctx.textAlign = 'center';
				ctx.textBaseline ='middle'; 
				
				// ******** верхняя линия ******** //
				//верх - левый пик
				ctx.moveTo(canvas.width/2 - range.w/2, top - pik/2);
				ctx.lineTo(canvas.width/2 - range.w/2, top + pik/2);

				//верх - линия
				ctx.moveTo(canvas.width/2 - range.w/2, top);
				ctx.lineTo(canvas.width/2 - textLength/2, top);
				ctx.fillText(w + ' мм', canvas.width/2, top);
				ctx.moveTo(canvas.width/2 + textLength/2, top);
				ctx.lineTo(canvas.width/2 + range.w/2, top);
				
				//верх - правый пик
				ctx.moveTo(canvas.width/2 + range.w/2, top - pik/2);
				ctx.lineTo(canvas.width/2 + range.w/2, top + pik/2);

				// ******** нижняя линия ******** //
				//низ - левый пик
				ctx.moveTo(canvas.width/2 - range.w1/2, canvas.height - bottom - pik/2);
				ctx.lineTo(canvas.width/2 - range.w1/2, canvas.height - bottom + pik/2);

				//низ - линия
				ctx.moveTo(canvas.width/2 - range.w1/2, canvas.height - bottom);
				ctx.lineTo(canvas.width/2 - textLength/2, canvas.height - bottom);
				ctx.fillText(w1 + ' мм', canvas.width/2, canvas.height - bottom);
				ctx.moveTo(canvas.width/2 + textLength/2, canvas.height - bottom);
				ctx.lineTo(canvas.width/2 + range.w1/2, canvas.height - bottom);
				
				//низ - правый пик
				ctx.moveTo(canvas.width/2 + range.w1/2, canvas.height - bottom - pik/2);
				ctx.lineTo(canvas.width/2 + range.w1/2, canvas.height - bottom + pik/2);
				
				var marginTop = 32;
				var marginLeft = 10;
				// ******** правая линия ******** //
				//правый - верхний пик
				ctx.moveTo(canvas.width/2 + range.w/2 - pik/2 + marginLeft, canvas.height/2 - range.h/2 + marginTop);
				ctx.lineTo(canvas.width/2 + range.w/2 + pik/2 + marginLeft, canvas.height/2 - range.h/2 + marginTop);

				//правый - линия
				ctx.moveTo(canvas.width/2 + range.w/2 + marginLeft, canvas.height/2 - range.h/2 + marginTop);
				ctx.lineTo(canvas.width/2 + range.w/2 + marginLeft, canvas.height/2 - textLength/2 + marginTop);
				ctx.fillText(h + ' мм', canvas.width/2 + range.w/2 + marginLeft, canvas.height/2 + marginTop);
				ctx.moveTo(canvas.width/2 + range.w/2 + marginLeft, canvas.height/2 + textLength/2 + marginTop);
				ctx.lineTo(canvas.width/2 + range.w/2 + marginLeft, canvas.height/2 + range.h/2 + marginTop);
				
				//правый - нижний пик
				ctx.moveTo(canvas.width/2 + range.w/2 - pik/2 + marginLeft, canvas.height/2 + range.h/2 + marginTop);
				ctx.lineTo(canvas.width/2 + range.w/2 + pik/2 + marginLeft, canvas.height/2 + range.h/2 + marginTop);
				
				ctx.closePath();
				ctx.stroke();
			}
		}
		
		//при изменении высоты, остальные поля считаем по пропорции
		var f = function(e){
			var data = goodForm.getDesignData();
			if (data) {
				var val = parseFloat($(this).val());
				var def = parseFloat($(this).data('default'));
				var max = parseFloat($(this).attr('max'));
				var min = parseFloat($(this).attr('min'));
				
				if (e.type == 'change') {
					if (isNaN(val) || val < min) { val = this.value = min; }
					if (isNaN(val) || val > max) { val = this.value = max; }
					if (isNaN(val)) { val = this.value = def; }
				}
				
				if (!isNaN(val) && val > 0 && data.top_ratio >= 0 && data.bottom_ratio >= 0) {
					var w = (val * (data.top_ratio>0?data.top_ratio:1)).toFixed(0);//было toFixed(1)
					var w1 = (val * (data.bottom_ratio>0?data.bottom_ratio:1)).toFixed(0);//было toFixed(1)
					$('.good-tpl-moto .inputs #exact_width').val(w);
					$('.good-tpl-moto .inputs #exact_width2').val(w1);
					drawSize(w, val, w1);
				}/* else {
					$('.good-tpl-moto .inputs #exact_width').val('');
					$('.good-tpl-moto .inputs #exact_width2').val('');
				}*/
			}
		}
		$('.good-tpl-moto .inputs #exact_height').change(f).keyup(f).change();
		
		$('.good-tpl-moto .one-more, .good-tpl-moto .one-less').click(function(){
			var d = parseFloat($('.good-tpl-moto .inputs #exact_height').val());
			d += ($(this).hasClass('one-more')?1:-1);
			$('.good-tpl-moto .inputs #exact_height').val(d).change();
			//f({ type: 'change' });
			return false;
		});
		
		//инициализируем слайдер
		$('.moto_slider .btn-move-left, .moto_slider .btn-move-right').click(function(){
			var slider = $(this).parent('.moto_slider');
			var items = slider.find('.slider-items');
			var diraction = ($(this).hasClass('btn-move-left')?'left':'right');
			
			//if (slider.attr('blocked') == '1') return;

			//прокручиваемый элемент
			var index = items.attr('index');
			if (typeof index == 'undefined' || index == null) index=3; 
			else index = parseInt(index);
			
			//направление прокрутки
			if (diraction == 'right') index++; else index--;
			if (index < 0) index = 0;
			if (index > slider.find('.slider-items a').length - 4)
				index = parseInt(slider.find('.slider-items a').length-4);
			items.attr('index', index);

			/*if (diraction == 'right' && slider.find('.slider-items a').length - index - 4 < 2) {
				//setTimeout(function(){ loadMore.check(); }, 10);
			}*/
			
			var p = Math.abs(parseInt(items.css('left')));
			if (diraction == 'left' && p == 0) {
				w = 0;
				items.attr('index', -1);
			} else {
				var w = -1; //-131
				var _index = index;
				slider.find('.slider-items a').each(function(){
					if (index-- > 0) {
						//debugger;
						w += $(this).width()+3;
					} else return;
				});
			}
			//console.log(_index+'_'+(w*-1)+'px_'+index); 
			items.stop().animate({ 'left' : (w*-1)+'px' }, 1000, 'swing', function(){
				/*if (diraction == 'left' && _index < 3) { 
					//setTimeout(function(){ loadMore.check(true); }, 10);
				}*/	
			});
			
			return false;
		});	
		
		$('.moto_slider .slider-items a').click(function(){
			$('.slider-items a').removeClass('on');
			$(this).addClass('on');
			
			goodForm.curGender = 'moto';
			goodForm.curCategory = $(this).attr('_sid');
			goodForm.setCategory(goodForm.curCategory);
			$('.good-tpl-moto .inputs #exact_height').change();
			
			return false;
		});
		
		if ($('.moto_slider .slider-items').attr('index') != null)
			$('.moto_slider .btn-move-right').click();
	});
</script>*}