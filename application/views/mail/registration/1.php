<?php include __DIR__ . '/../header.php' ?>
  
<div style="background:#f2f2f2;word-wrap:break-word">
    <div style="background-color:#e6e6e6" width="100%">
    
    <table style="margin:auto" cellpadding="0" cellspacing="0">
		<tbody><tr>
		    <td>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="m_9156061853792629808html-email">
			    <tbody><tr>
				<td>
				    Добро пожаловать <?= $user->user_name ?><br>
                </td>
            </tr>
			</tbody>
			</table>

			<table class="m_9156061853792629808html-email" cellspacing="0" cellpadding="0" border="0" width="100%">
			    <tbody>
			    <tr>
				<th width="100%">Ваши регистрационные данные</th>
			    </tr>
			    <tr>
                    <td valign="top" width="100%">
                        Ваше имя пользователя: <?= $user->user_email ?><br>
                        Ваше показываемое имя: <?= $user->user_name ?><br>
                        Ваш пароль: <?= $user->password ?><br><br>
                        Эл.почта: <?= $user->user_email ?><br>
                        Телефон: <?= $user->user_phone ?><br>
                        Фамилия / Имя / Отчество: <?= $user->user_name ?><br>
                        Почтовый индекс: <?= $user->user_zip ?><br>
                        Город: <?= cityId2name($user->user_city_id) ?><br>
                        Страна: <?= countryId2name($user->user_country_id) ?><br>		
                        
                        <p>Ссылка для активации: <a href="<?= $user->activateLink ?>"><?= $user->activateLink ?></a></p>		
                    </td>
			    </tr>
			</tbody></table>
		    </td>
		</tr>
	    </tbody>
	    </table>
	    	    
   </div>
</div>