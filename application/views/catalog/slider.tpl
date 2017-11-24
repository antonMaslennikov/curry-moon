<div class="bottom-sliderG" offset="0">
	<!-- Кнопка скрола влево -->
	<a href="#!/" class="btn-move-left" rel="nofollow"></a>
	<div class="slider-container">
		<!--div class="left-opacity"></div-->
		<div class="slider-items-container">
			<div class="slider-items">
				{foreach from=$gallery item="g"} 
					<a href="{$g.link}" class="slider-item" rel="nofollow"><img title="{$g.good_name}" alt="{$g.good_name}" width="180" height="184" src="{$g.picture_path}"></a>
				{/foreach}
				
			</div>
		</div>
		<!--div class="right-opacity"></div-->
	</div>
	<!-- Кнопка скрола вправо -->
	<a href="#!/" class="btn-move-right" rel="nofollow"></a>
</div>

{literal}
<script type="text/javascript">

$(document).ready(function() {
	
	//слайдеры
	$('.bottom-sliderG').each(function(){
		this.index = 0;
		var w = 0;
		$(this).find('.slider-items a').each(function(){ w += $(this).width()+5; });
		$(this).find('.slider-items').css('width', (w+4)+'px');
		
		$(this).find('.btn-move-left, .btn-move-right').click(function(){
			var slider = $(this).parents('.bottom-sliderG')[0];
			var index = slider.index;
			if (this.className.indexOf('left') > 0)
				index--;
			else
				index++;
			
			if (index >= 0 && index <= $(slider).find('.slider-items a').length) {
				var pos = $(slider).find('.slider-items a:eq('+index+')').position();
				var w  = $(slider).find('.slider-items-container').width();
				var w1 = 0;
				$(slider).find('.slider-items a').each(function(){ w1 += $(this).width(); });
				
				var offset = $(slider).attr('offset');
				
				if (w1 - pos.left + 131 > w) {
					$(slider).find('.slider-items').animate({ left: '-'+(pos.left+(offset?parseInt(offset):0))+'px' }, 300);
					slider.index = index;
				}
			}
		
			return false;
		});
	});
});
</script>
{/literal}