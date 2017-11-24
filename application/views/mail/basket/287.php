<p>Здравствуйте, %userNameLogin%!</p>
<p>Ваш заказ передан в службу доставки DPD. В скором времени курьер принесет  посылку вам в руки!</p>
<p>
Вашей посылке присвоен номер <strong><?= $code ?></strong><br />
Отследить посылку можно здесь <a href="http://dpd.ru/ols/trace/">http://dpd.ru/ols/trace/</a>
</p>
<? if ($bonusBack): ?>
<p>И не забудьте, что через 2 недели вам будут начислены <?= $bonusBack ?> руб., за этот заказ.<br /></p>
<? endif; ?>

<table style="width: 100%;">
    <tr>
        <td width="33%" style="vertical-align: top;" valign="top">
            <p style=" font-style: normal; font-size: 12px; font-variant: normal; font-weight: normal; line-height: 12px; font-family: Arial, Tahoma, Verdana, sans-serif;line-height: 18px;">Адрес</p>
            <p style="line-height: 18px;"><span style=" font-style: normal; font-size: 12px; font-variant: normal; font-weight: normal; line-height: 12px; font-family: Arial, Tahoma, Verdana, sans-serif;">
             г.Москва, ул.&nbsp;Малая Почтовая, д.&nbsp;12, стр.&nbsp;3, корп.&nbsp;20, 5-й&nbsp;этаж.<br/> Перед приездом предупредите.</span></p>
        </td>

        <td width="33%" style="vertical-align: top;" valign="top">
        
            <p style=" font-style: normal; font-size: 12px; font-variant: normal; font-weight: normal; line-height: 12px; font-family: Arial, Tahoma, Verdana, sans-serif;line-height: 18px;">Контакты</p>

            <table cellpadding="0" cellspacing="0" width="90%" height="20" >
            <tr>
                <td style="text-align: left; width: 30px;" align="left" valign="bottom" ><span style=" font-style: normal; font-size: 12px; font-variant: normal; font-weight: normal; line-height: 12px; font-family: Arial, Tahoma, Verdana, sans-serif;">Телефон</span>  </td>
                <td valign="bottom" ><div style="display: block; border-bottom-color: #333; border-bottom-width: 1px; border-bottom-style: dotted; line-height: 13px;">&nbsp;</div></td>
                <td style="text-align: left; width: 106px;white-space: nowrap;" align="left" valign="bottom" ><span style=" font-style: normal; font-size: 12px; font-variant: normal; font-weight: normal; line-height: 12px; font-family: Arial, Tahoma, Verdana, sans-serif;">+7 (495) 229-30-73</span></td>
            </tr>
            </table>
            
            <table cellpadding="0" cellspacing="0" width="90%" height="20" >
            <tr>
                <td style="text-align: left; width: 30px;" align="left" valign="bottom" ><span style=" font-style: normal; font-size: 12px; font-variant: normal; font-weight: normal; line-height: 12px; font-family: Arial, Tahoma, Verdana, sans-serif;">Skype</span>    </td>
                <td valign="bottom" ><div style="display: block; border-bottom-color: #333; border-bottom-width: 1px; border-bottom-style: dotted; line-height: 13px;">&nbsp;</div></td>
                <td style="text-align: left; width: 70px;" align="left" valign="bottom" ><span style=" font-style: normal; font-size: 12px; font-variant: normal; font-weight: normal; line-height: 12px; font-family: Arial, Tahoma, Verdana, sans-serif;">maryjane_ru</span></td>
            </tr>
            </table>

            <table cellpadding="0" cellspacing="0" width="90%" height="20"  >
            <tr>                
                <td style="text-align: left; width: 30px;" align="left" valign="bottom" ><span style=" font-style: normal; font-size: 12px; font-variant: normal; font-weight: normal; line-height: 12px; font-family: Arial, Tahoma, Verdana, sans-serif;">Почта</span></td>
                <td  valign="bottom" ><div style="display: block; border-bottom-color: #333; border-bottom-width: 1px; border-bottom-style: dotted; line-height: 13px;">&nbsp;</div></td>
                <td style="text-align: left; width: 90px;" align="left"  valign="bottom" ><span style=" font-style: normal; font-size: 12px; font-variant: normal; font-weight: normal; line-height: 12px; font-family: Arial, Tahoma, Verdana, sans-serif; color:#6F6E6E;"><a href="mailto:info@maryjane.ru" target="_blank"  style="color:#6F6E6E !important; text-decoration:none;">info@maryjane.ru</a></span></td>
            </tr>
            </table>
            
        </td>

        <td width="33%" style="vertical-align:top;text-align: right;white-space: nowrap;" valign="top">

            <p style=" font-style: normal; font-size: 12px; font-variant: normal; font-weight: normal; line-height: 12px; font-family: Arial, Tahoma, Verdana, sans-serif;line-height: 18px;">Время работы склада</p>

            <table cellpadding="0" cellspacing="0" width="100%" >
            <tr>                
                <td style="text-align: left; width: 50px;" align="left"  valign="bottom" ><span style=" font-style: normal; font-size: 12px; font-variant: normal; font-weight: normal; white-space: nowrap;line-height: 12px; font-family: Arial, Tahoma, Verdana, sans-serif;">Пн.-Птн.</span></td>
                <td  valign="bottom" ><div style="display: block; border-bottom-color: #333; border-bottom-width: 1px; border-bottom-style: dotted; line-height: 13px;">&nbsp;</div></td>
                <td style="text-align: left; width: 83px;" align="left"  valign="bottom" ><span style=" font-style: normal; font-size: 12px; font-variant: normal; font-weight: normal; line-height: 12px; font-family: Arial, Tahoma, Verdana, sans-serif;white-space: nowrap;">%operation_time_1%</span></td>
            </tr>
            </table>

            <table cellpadding="0" cellspacing="0" width="96%" height="20"  >
            <tr>                
                <td style="text-align: left; width: 20px;" align="left"  valign="bottom" ><span style=" font-style: normal; font-size: 12px; font-variant: normal; font-weight: normal; line-height: 12px; font-family: Arial, Tahoma, Verdana, sans-serif;">Сб</span></td>
                <td  valign="bottom" ><div style="display: block; border-bottom-color: #333; border-bottom-width: 1px; border-bottom-style: dotted; line-height: 13px;">&nbsp;</div></td>
                <td style="text-align: left; width: 60px;" align="left"  valign="bottom" ><span style=" font-style: normal; font-size: 12px; font-variant: normal; font-weight: normal; line-height: 12px; font-family: Arial, Tahoma, Verdana, sans-serif;white-space: nowrap;">%operation_time_2%</span></td>
            </tr>
            </table>

            <table cellpadding="0" cellspacing="0" width="96%" height="20"  >
            <tr>
                <td style="text-align: left; width: 20px;" align="left"  valign="bottom" ><span style=" font-style: normal; font-size: 12px; font-variant: normal; font-weight: normal; line-height: 12px; font-family: Arial, Tahoma, Verdana, sans-serif;">Вс</span></td>
                <td  valign="bottom" ><div style="display: block; border-bottom-color: #333; border-bottom-width: 1px; border-bottom-style: dotted; line-height: 13px;">&nbsp;</div></td>
                <td style="text-align: left; width: 60px;" align="left" valign="bottom" ><span style="font-style: normal; font-size: 12px; font-variant: normal; font-weight: normal; line-height: 12px; font-family: Arial, Tahoma, Verdana, sans-serif;white-space: nowrap;">%operation_time_3%</span></td>
            </tr>
            </table>
        </td>
    </tr>

    <tr><td style="vertical-align: top;" valign="top" colspan="3" style="height: 20px;" height="20px">&nbsp;</td></tr>

    <tr>
        <td colspan="3" >
            <table style="width: 100%;">
                <tr>
                  <td colspan="3" style="border-top-width: 1px; border-top-color: #444; border-top-style: solid; vertical-align: top;" valign="top">
                    <div style="height:0px; line-height:5px; margin:0px; padding:0px;">&nbsp;</div>
                        <p style=" font-style: normal; font-size: 12px; font-variant: normal; font-weight: normal; line-height: 12px; font-family: Arial, Tahoma, Verdana, sans-serif;line-height: 18px; text-shadow: 1px 1px #fff;">КАЖДЫЙ ДЕНЬ НОВЫЙ ДИЗАЙН, ПОТОМУ ЧТО КАЖДЫЙ ДЕНЬ НОВЫЙ 
                        <br />
                        &copy; <a href="%quickLoginLink%&next=http://www.maryjane.ru" style=" font-style: normal; font-size: 12px; font-variant: normal; font-weight: normal; line-height: 12px; font-family: Arial, Tahoma, Verdana, sans-serif;color: #555; text-shadow:none;">Магазин футболок №1 Maryjane.ru</a></p>
                  </td>
                </tr>
            </table>
        </td>
    </tr>
</table>