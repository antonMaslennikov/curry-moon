<style type="text/css">#rightcol{ margin-top:60px }</style>

{if $USER->meta->mjteam && $USER->meta->mjteam != "fired"}
<p style="padding:10px;text-align: center;border:1px dashed orange">
	<a href="{$PAGE->url}{if !$smarty.get.show}?show=full{/if}">{if !$smarty.get.show}Развернуть всё!{else}Свернуть всё!{/if}</a>
</p>
{/if}

{if $faq_title|count > 0}
	<style>#rightcol {  margin-top:{if $PAGE->reqUrl.1 == "designers"}40{else}95{/if}px }</style>
	<a href="/design/create/futbolka/" style="" class="Goto-tender-track-link" rel="nofollow" target="_blank">
		<img width="255" height="210" title="Требуется помощь" alt="Требуется помощь" src="/images/help_diz_faq1.gif">
	</a>
	<br/><br/>

	{if !$PAGE->reqUrl.2 && $PAGE->reqUrl.1 != 'search'}
	<div class="tabz clearfix head_blockQuestionAnswer-tabz">
		<a href="/{$PAGE->module}/buyers/" {if $PAGE->reqUrl.1 == "buyers" || $PAGE->reqUrl.1 == ""}class="active"{/if}>Покупателям</a>
		<a href="/{$PAGE->module}/designers/" {if $PAGE->reqUrl.1 == "designers"}class="active"{/if}>Дизайнерам</a>
	</div>
	{/if}

	<div class="block blockQuestionAnswer border moduletable">	
		<div class="content">
			<ul>
				{foreach from=$faq_title key="k" item="fg"}
				<li >
					&mdash;&nbsp;<a href="{if $fgroup}/{$module}/group/{$k}/{else}#{$fg.slug}{/if}" style="font-size:12px">{$fg.name}</a>
				</li>
				{/foreach}
			</ul>
		</div>
	</div>

	<br><br>
{/if}

<div class="module-green" style="border:1px solid #04A84F">
	<div>
		<div>
			<div>
				<h3 style="font-size:100%;text-transform:uppercase;border-bottom:2px solid #e3e3e3;padding-bottom:5px">Купить футболку Maryjane.ru</h3>
				Вы можете заказать футболку в нашем интернет магазине с доставкой по России:<br/>
				В нашем <a href="/catalog/"  target="_blank">магазине футболок</a>, Вы можете <a href="/stock/"  target="_blank">заказать футболку</a> и выбрать в принтшопе одну из <a href="http://www.maryjane.ru/prikol/" target="_blank">прикольныx футболок</a>.
				<h6 class="smallcomment">И забрать заказ самостоятельно со склада.</h6>
				<h5 class="bluetext">Время работы склада:</h5>
				<h6 style="text-align: right;width:195px;}">{$operation_time}{*<b>Пн&mdash;Пт. с 10 до 21 часа</b><br><b>Сб. с 11 до последнего клиента</b><br>Вс. выходной*}</h6>
				м. Бауманская<br/>
				Москва, ул. Малая Почтовая<br />
				д.12, стр.20, 5-й этаж.<br/>
				<a href="/about/" rel="nofollow">Посмотреть на карте</a>
			</div>
		</div>
	</div>
</div>

<div class="сlr clearfix"></div>