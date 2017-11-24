{literal}
	<style>
	.catalog_goods_list .list_wrap {float: none;width: 980px;}
	.list_wrap ul li.m12 {padding: 5px 3px 17px 7px;}
	.m12 .item span {float:none;}
	.m12 .price_o{position: absolute;
			background-image: url('/images/odnocvet/bg_price_mim.png');
            background-repeat: repeat;
            background-position: 0 0;
            height: 25px;line-height: 25px;padding-left: 9px;
            width: 55px;
            left: 0; bottom: 80px;font-family: 'MyriadPro-CondIt','arial';color:#ffffff;font-size:16px;
		}
	</style>
{/literal}
<div class="catalog_goods_list" style="clear:both;width:778px;border-bottom:0;margin: 25px 0 15px;">
	<div class="list_wrap {$default.sex}">
		<ul>
			{if $good.good_id !=25144} 
			{*
			<li class="m12" style="">
				<a rel="nofollow" class="item" title="Опыт и Алкоголь Всегда" href="/sale/view/25144/category,futbolki;sex,male;color,21;size,2/">
					<span class="list-img-wrap">
						<img src="/images/odnocvet/min_175/25144.jpg" width="175" height="" alt="Опыт и Алкоголь Всегда">
					</span>
				</a>
				<span class="price_o" style="">390 руб</span>
				<div class="item">
					<a href="/sale/view/25144/category,futbolki;sex,male;color,21;size,2/" rel="nofollow" class="title" title="Опыт и Алкоголь Всегда">Опыт и Алкоголь Всегда</a>
					<!--noindex--><span class="author">
					<a rel="nofollow" title="автор EXAR" href="/catalog/EXAR/">EXAR</a></span><!--/noindex-->	
				</div>
			</li>
			*}
			{/if}
			{if $good.good_id != 28766 || ($good.good_id == 28766 && $filters.sex =='male')}
			<li class="m12" style="">
				<a rel="nofollow" class="item" title="Летнее, велосипедное" href="/sale/view/28766/category,futbolki;sex,female;color,21;size,3/">
					<span class="list-img-wrap">
						<img src="/images/odnocvet/min_175/28766.jpg" width="175" alt="Летнее, велосипедное">
					</span>
				</a>
				<span class="price_o" style="">150 руб</span>
				<div class="item">
					<a href="/sale/view/28766/category,futbolki;sex,female;color,21;size,3/" rel="nofollow" class="title" title="Летнее, велосипедное">Летнее, велосипедное</a>
					<!--noindex--><span class="author">
					<a rel="nofollow" title="автор l-and-ms" href="/catalog/l-and-ms/">l-and-ms</a></span><!--/noindex-->	
				</div>
			</li>
			{/if}			
			{if $good.good_id !=57591} 
			<li class="m12" style="">
				<a rel="nofollow" class="item" title="Govorun" href="/sale/view/57591/category,futbolki;sex,female;color,21;size,3/">
					<span class="list-img-wrap">
						<img src="/images/odnocvet/min_175/57591.jpg" width="175" height="" alt="Govorun">
					</span>
				</a>
				<span class="price_o" style="">150 руб</span>
				<div class="item">
					<a href="/sale/view/57591/category,futbolki;sex,female;color,21;size,3/" rel="nofollow" class="title" title="Govorun">Govorun</a>
					<!--noindex--><span class="author">
					<a rel="nofollow" title="автор Sodesign" href="/catalog/Sodesign/">Sodesign</a></span><!--/noindex-->	
				</div>
			</li>
			{/if}
			{if $good.good_id !=17318}
			<li class="m12" style="">
				<a rel="nofollow" class="item" title="Сумка" href="/sale/view/17318/category,sumki/">
					<span class="list-img-wrap">
						<img src="/images/odnocvet/min_175/17318.jpg" width="175" height="" alt="Сумка">
					</span>
				</a>
				<span class="price_o" style="">150 руб</span>
				<div class="item">
					<a href="/sale/view/17318/category,sumki/" rel="nofollow" class="title" title="Сумка">Сумка</a>
					<!--noindex--><span class="author">
					<a rel="nofollow" title="автор Maryjane" href="/catalog/Maryjane/">Maryjane</a></span><!--/noindex-->	
				</div>
			</li>
			{/if}
			{if $good.good_id !=19891}
			<li class="m12" style="">
				<a rel="nofollow" class="item" title="Клуб пыльные гантели" href="/sale/view/19891/category,futbolki;sex,male;color,21;size,2/">
					<span class="list-img-wrap">
						<img src="/images/odnocvet/min_175/19891.jpg" width="175" height="" alt="Клуб пыльные гантели">
					</span>
				</a>
				<span class="price_o" style="">150 руб</span>
				<div class="item">
					<a href="/sale/view/19891/category,futbolki;sex,male;color,21;size,2/" rel="nofollow" class="title" title="Клуб пыльные гантели">Клуб пыльные гантели</a>
					<!--noindex--><span class="author">
					<a rel="nofollow" title="автор Maryjane" href="/catalog/Maryjane/">Maryjane</a></span><!--/noindex-->	
				</div>
			</li>
			{/if}
			{if $good.good_id != 28766 || ($good.good_id == 28766 && $filters.sex =='female')}
			<li class="m12" style="">
				<a rel="nofollow" class="item" title="Летнее, велосипедное" href="/sale/view/28766/category,futbolki;sex,male;color,21;size,2/">
					<span class="list-img-wrap">
						<img src="/images/odnocvet/min_175/28766_m.jpg" width="175" height="" alt="Летнее, велосипедное">
					</span>
				</a>
				<span class="price_o" style="">150 руб</span>
				<div class="item">
					<a href="/sale/view/28766/category,futbolki;sex,male;color,21;size,2/" rel="nofollow" class="title" title="Летнее, велосипедное">Летнее, велосипедное</a>
					<!--noindex--><span class="author">
					<a rel="nofollow" title="автор L-and-Ms" href="/catalog/L-and-Ms/">L-and-Ms</a></span><!--/noindex-->	
				</div>
			</li>
			{/if}
			{*
			<li class="m12" style="">
				<a rel="nofollow" class="item" title="Наклейка на ipad Govorun" href="/catalog/sodesign/57591/category,touchpads/#ipad-4-retina">
					<span class="list-img-wrap">
						<img src="/J/static-cache/fittingroom/touchpads/483/57591.jpeg" width="175" height="" alt="Наклейка на ipad Govorun">
					</span>
				</a>
				<div class="item">
					<a href="/catalog/sodesign/57591/category,touchpads/#ipad-4-retina" rel="nofollow" class="title" title="Летнее, велосипедное">Наклейка на ipad Govorun</a>
					<!--noindex--><span class="author">
					<a rel="nofollow" title="автор " href="/catalog/">автор</a></span><!--/noindex-->	
				</div>
			</li>			
			<li class="m12" style="">
				<a rel="nofollow" class="item" title="Чехол для ipad mini Летнее, велосипедное" href="/catalog/l-and-ms/28766/category,touchpads/#case-ipad-mini/">
					<span class="list-img-wrap">
						<img src="/J/static-cache/fittingroom/touchpads/482/28766.jpeg" width="175" height="" alt="Чехол для ipad mini Летнее, велосипедное">
					</span>
				</a>
				<div class="item">
					<a href="/catalog/l-and-ms/28766/category,touchpads/#case-ipad-mini/" rel="nofollow" class="title" title="Чехол для ipad mini Летнее, велосипедное">Чехол для ipad mini Летнее, велосипедное</a>
					<!--noindex--><span class="author">
					<a rel="nofollow" title="автор " href="/catalog/">автор</a></span><!--/noindex-->	
				</div>
			</li>
			*}			
		</ul>
	</div>
</div>










</div>
