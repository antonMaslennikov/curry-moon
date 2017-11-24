{literal}
<script type="text/javascript">
	
	$(document).ready(function(){ 
		
		//инициализируем лайканье для работ
		$('.vote').each(function(){
			if (!this.vote) this.vote = new vote({ div: this, id: parseInt($(this).attr('_id')) });
		});
		
		if(!ismobiledevice){
			//показ цены при наведение на работу
			$('.m12').hover(
				function(){ 
					var btn = $(this).find('.btn_editing').css('visibility','visible').show();				
					if (btn.length == 0) {
						var d = $(this).find('.price');
						if (!d.hasClass('gold'))
							d.css('visibility','visible').show(); 
					}
				}, 
				function(){ 
					$(this).find('.btn_editing').css('visibility','hidden').hide();
					var d = $(this).find('.price');
						if (!d.hasClass('gold'))
							d.css('visibility','hidden').hide();
				}
			);
		}
		
		//инициализируем "Показать ещё"
		initShowMore();
	
	});
</script>

{/literal}

{foreach from=$styles key="gk" item="g"}
	
	{assign var=def_color value=$BASECATS.$gk.def_color.$SEX}

	{foreach from=$g.colors item="c" key="ck" name="cForeach"}
	<li class="m122 {$gk}">
		<a rel="nofollow" class="item" title="" href="/{$module}/{$g.good.user_login}/{$g.good.good_id}/category,{$gk}{if $filters.color};color,{$filters.color}{/if}{if $filters.size};size,{$filters.size}{/if}/{if $filters.sex == 'female'}female/{/if}">
			<img title="" alt="" src="{$c.pic}">
		</a>
		<div class="item">
			<a rel="nofollow" title="" href="/{$module}/{$g.user_login}/{$g.good_id}/category,{$gk}/{if $filters.sex == 'female'}female/{/if}">{$g.category}</a> <span class="">{if $g.price_old > $g.price}<strike>{$g.price_old}</strike>{/if} {$g.price}&nbsp;руб.</span>
		</div>
	</li>
	{/foreach}

{/foreach}