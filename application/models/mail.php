<?php

namespace application\models;
    
use \smashEngine\core\App AS App; 
use \PDO;

class mail extends \smashEngine\core\Model
{
    /**
     * @var array массив, содержащий кэш шаблонов 
     * чтобы при массовой рассылке одного шаблона не выдёргивать текст каждый раз 
     */
    protected static $templates = array();
    
    public static $tpl_folder = 'application/views/mail/';
    
    
    function addMessage($list, $user, $tpl, $text, $subject = null, $emails = null, $raiting = 10, $form = null)
    {
        if (count($list) > 0)
        {
            if ($list['subscribers'])
            {
                $query[] = "SELECT `user_id` FROM `mail_list_subscribers` WHERE `mail_list_id` IN ('" . implode(',', $list['subscribers']) . "') ";
            }
            
            if ($list['printshop'])
            {
                $query[] = "SELECT u.`user_id` FROM `printshop_users` AS pu, users AS u WHERE u.`user_id` = pu.`user_id`";
            }
            
            if ($list['buyers'])
            {
                $query[] = "SELECT `user_id` FROM `users` WHERE `user_delivered_orders` >= '1'";
            }
            if ($list['designers'])
            {
                $query[] = "SELECT `user_id` FROM `goods` WHERE `good_status` != 'customize'";
            }
            if ($list['havebonus'])
            {
                $query[] = "SELECT `user_id` FROM `users` WHERE `user_bonus` > '0'";
            }

            $q = implode(' UNION ', $query) . " GROUP BY `user_id` ORDER BY `user_id` DESC";
            
            $result = App::db()->query($q);

            $i=0;

            foreach ($result->fetchAll() AS $row)
            {
                $user[$i] = $row['user_id'];
                $i++;
            }
            
            $raiting = 0;
            $form    = 'noreply@maryjane.ru';
        }
        
        $i=0;

        $results = array();

        foreach ($user AS $u)
        {
            $rr = App::db()->query("SELECT * FROM `users` WHERE `user_id` = '{$u}' LIMIT 1");
            
            if ($rr->rowCount() == 1)
            {
                $us = $rr->fetch();
                
                if ($us['user_subscription_status'] == 'active' || $raiting != 0)
                {
                    $ub  = $us['user_bonus'];
                    $un  = $us['user_name'];
                    $ul  = $us['user_login'];
                    $um  = $us['user_email'];
                    
                    $foo = App::db()->query("SELECT SUM(`user_bonus_count`) AS s FROM `user_bonuses` WHERE `user_id` = '" . $us['user_id'] . "' AND `user_bonus_status` = '0'")->fetch();
                    $ubw = $foo['s'];
                    
                    $ql_code = md5($us['user_password'] . rand() . time());
                    
                    $tmptext = stripslashes($text);
                    $tmpsubj = $subject;
                    
                    $tmptext = str_ireplace("%userId%", $u, $tmptext);
                    $tmptext = str_ireplace("%userName%", $un, $tmptext);
                    $tmptext = str_ireplace("%userEmail%", $um, $tmptext);
                    $tmptext = str_ireplace("%userLogin%", $ul, $tmptext);
                    $tmptext = str_ireplace("%userPassword%", $us['user_password'], $tmptext);
                    $tmptext = str_ireplace("%userNameLogin%", ((!empty($un)) ? $un : $ul), $tmptext);
                    $tmptext = str_ireplace("%userEmailLogin%", ((!empty($um)) ? $um : (($us->user_login) ? $us->user_login : '')), $tmptext);
                    
                    $tmptext = str_ireplace("%userBonuses%", (($ub > 0) ? "Позвольте сообщить Вам состояние Вашего Лицевого счёта в нашем магазине.<br><br>На вашем счету: $ub РУБ.<br>Эти деньги Вы можете потратить на любые товары в нашем интернет-магазине, чтобы сэкономить на следующей покупке. Для этого при оформлении нового заказа на странице ШАГ 3 поставьте галочку напротив пункта:<br><br><ВЫ ХОТИТЕ ИСПОЛЬЗОВАТЬ ИМЕЮЩИЕСЯ У ВАС НА СЧЕТУ БОНУСЫ ДЛЯ ОПЛАТЫ ЭТОГО ЗАКАЗА?><br>Стоимость Вашего нового заказа будет уменьшена на $ub РУБ. <br><br>Посмотреть историю всех операций Вы можете в Профиле на странице Заказов: <a href='http://www.maryjane.ru/orderhistory/?utm_source=mail&utm_medium=notifier&utm_campaign=mailuserbonuses'>http://www.maryjane.ru/orderhistory</a><br>Состояние бонусного счета отражено на главной странице Профиля<br><a href='http://www.maryjane.ru/orderhistory/?utm_source=mail&utm_medium=notifier&utm_campaign=mailuserbonuses'>http://www.maryjane.ru/profile</a>" : ''), $tmptext);
                    $tmptext = str_ireplace("%userBonusesCount%", intval($ub), $tmptext);
                    $tmptext = str_ireplace("%userBonusesWait%", intval($ubw), $tmptext);
                    
                    $tmpsubj = str_ireplace("%userName%", $un, $tmpsubj);
                    $tmpsubj = str_ireplace("%userLogin%", $ul, $tmpsubj);
                    $tmpsubj = str_ireplace("%userNameLogin%", ((!empty($un)) ? $un : $ul), $tmpsubj);
                    $tmpsubj = str_ireplace("%userEmailLogin%", ((!empty($um)) ? $um : (($us->user_login) ? $us->user_login : '')), $tmpsubj);
                    $tmpsubj = str_ireplace("%userBonusesWait%", intval($ubw), $tmpsubj);
                    
                    $tmptext = str_ireplace("%operation_time_1%", getVariableValue('operation_time_1'), $tmptext);
                    $tmptext = str_ireplace("%operation_time_2%", getVariableValue('operation_time_2'), $tmptext);
                    $tmptext = str_ireplace("%operation_time_3%", getVariableValue('operation_time_3'), $tmptext);
                    
                    
                    $tmptext = str_ireplace("%bprice_11%", ((40 - $ub) < 0) ? 0 : 40 - $ub, $tmptext);
                    $tmptext = str_ireplace("%bprice_12%", ((600 - $ub) < 0) ? 0 : 600 - $ub, $tmptext);
                    $tmptext = str_ireplace("%bprice_13%", ((790 - $ub) < 0) ? 0 : 790 - $ub, $tmptext);
                    $tmptext = str_ireplace("%bprice_21%", ((990 - $ub) < 0) ? 0 : 990 - $ub, $tmptext);
                    $tmptext = str_ireplace("%bprice_22%", ((1090 - $ub) < 0) ? 0 : 1090 - $ub, $tmptext);
                    $tmptext = str_ireplace("%bprice_23%", ((1990 - $ub) < 0) ? 0 : 1990 - $ub, $tmptext);
                    
                    
                    if (strpos($tmptext, '%quickLoginLink%') !== false)
                    {
                        App::db()->query("INSERT INTO `user_quick_login` SET `hash` = '$ql_code', `user_id` = '$u'");
                        $tmptext = str_ireplace("%quickLoginLink%", 'http://www.maryjane.ru/login/quick/?user_id=' . $u . '&code=' . $ql_code, $tmptext);
                    }
                    
                    $tmptext = str_ireplace("%userBonusesText%", (($ub > 0) ? '<span style="color:#666;line-height: 33px;font-size: 12px;">На вашем бонусном счету </span><a href="http://www.maryjane.ru/login/quick/?user_id=' . $u . '&code=' . $ql_code . '&next=http://www.maryjane.ru/bonuses/%3Futm_source%3Dnewsletter%26utm_medium%3Demail%26utm_campaign%3Dmailsender_' . $tpl . '" style="color:#2f98ce;border:0px;margin:0px;padding:0px;line-height: 33px;font-size: 12px;" target="_blank">' . $ub . ' р.</a>' : ''), $tmptext);
                    
                    
                    $code    = md5($u . $um . $us['user_register_date']);
        
                    // код подписки
                    $tmptext = str_ireplace("%subscribeCode%", $code, $tmptext);
                    $tmptext = str_ireplace("%subscribeLink%", "http://www.maryjane.ru/subscribe/$u/$code/8/", stripslashes($tmptext));
                    
                    // код полного отключения подписки 
                    $tmptext = str_ireplace("%unsubscribeCode%", $code, $tmptext);
                    $tmptext = str_ireplace("%unsubscribeLink%", "http://www.maryjane.ru/unsubscribe/$u/$code", $tmptext);
                
                    
                    $result = App::db()->query("INSERT INTO `mail_messages` 
                                (`mail_message_template_id`, `mail_message_subject`, `mail_message_text`, `user_id`, `raiting`, `from`) 
                              VALUES 
                                ('{$tpl}', '" . addslashes($tmpsubj) . "','" . addslashes($tmptext) . "', '" . $u . "', '$raiting', '$form')");
                    
                    $results[] = App::db()->lastInsertId();
                    
                    $i++;
                }
            }
        }
    
        $i=0;
        
        while ($emails[$i])
        {
            $tmptext = $text;
            $tmpsubj = $subject;
            
            $tmptext = str_ireplace("%userName%", "Гость", $tmptext);
            $tmptext = str_ireplace("%userLogin%", "Гость", $tmptext);
            $tmptext = str_ireplace("%userBonuses%", (($ub > 0) ? "Позвольте сообщить Вам состояние Вашего Лицевого счёта в нашем магазине.<br><br>На вашем счету: $ub РУБ.<br>Эти деньги Вы можете потратить на любые товары в нашем интернет-магазине, чтобы сэкономить на следующей покупке. Для этого при оформлении нового заказа на странице ШАГ 3 поставьте галочку напротив пункта:<br><br><ВЫ ХОТИТЕ ИСПОЛЬЗОВАТЬ ИМЕЮЩИЕСЯ У ВАС НА СЧЕТУ БОНУСЫ ДЛЯ ОПЛАТЫ ЭТОГО ЗАКАЗА?><br>Стоимость Вашего нового заказа будет уменьшена на $ub РУБ. <br><br>Посмотреть историю всех операций Вы можете в Профиле на странице Заказов: <a href='http://www.maryjane.ru/orderhistory/?utm_source=mail&utm_medium=notifier&utm_campaign=mailuserbonuses'>http://www.maryjane.ru/orderhistory</a><br>Состояние бонусного счета отражено на главной странице Профиля<br><a href='http://www.maryjane.ru/orderhistory/?utm_source=mail&utm_medium=notifier&utm_campaign=mailuserbonuses'>http://www.maryjane.ru/profile</a>" : ''), $tmptext);
            $tmptext = str_ireplace("%userBonusesCount%", intval($ub), $tmptext);
            $tmptext = str_ireplace("%userBonusesText%", (($ub > 0) ? '<span style="color:#666;">На вашем бонусном счету </span><a href="%quickLoginLink%&next=http://www.maryjane.ru/bonuses/%3Futm_source%3Dnewsletter%26utm_medium%3Demail%26utm_campaign%3Dmailsender_' . $tpl . '" style="color:#2f98ce;border:0px;margin:0px;padding:0px;" target="_blank">' . $ub . ' р.</a>' : ''), $tmptext);
            
            $tmptext = str_ireplace("%operation_time_1%", getVariableValue('operation_time_1'), $tmptext);
            $tmptext = str_ireplace("%operation_time_2%", getVariableValue('operation_time_2'), $tmptext);
            $tmptext = str_ireplace("%operation_time_3%", getVariableValue('operation_time_3'), $tmptext);
            
            $tmpsubj = str_ireplace("%userName%", "Гость", $tmpsubj);
            $tmpsubj = str_ireplace("%userLogin%", "Гость", $tmpsubj);
            
            $result = App::db()->query("INSERT INTO `mail_messages` 
                        (`mail_message_template_id`, `mail_message_subject`, `mail_message_text`, `mail_message_email`, `raiting`, `from`) 
                      VALUES 
                        ('$tpl', '" . addslashes($tmpsubj) . "','" . addslashes($tmptext) . "', '" . $emails[$i] . "', '$raiting', '$form')");
            
            $results[] = App::db()->lastInsertId();
            
            $i++;
        }

        //die('STOP');
        //$this->sendAllMessages();
        
        return $results;
    }

    
    function sendAllMessages($count = 30, $raiting = array(0, 10))
    {
        require ROOTDIR . '/vendor/PHPMailer/PHPMailerAutoload.php';
                    
        $result = App::db()->query("SELECT mm.*, u.`user_email`, mt.`mail_template_order`
                               FROM `mail_messages` mm
                                    LEFT JOIN `users` u ON u.`user_id` = mm.`user_id`
                                    LEFT JOIN `mail_templates` mt ON mt.`mail_template_id` = mm.`mail_message_template_id`    
                               WHERE `mail_message_status` = 'awaiting' AND `raiting` IN ('" . implode("', '", $raiting) . "')
                               ORDER BY `raiting` DESC 
                               LIMIT $count");
                               
        foreach ($result->fetchAll(PDO::FETCH_BOTH) AS $row) {
            $messages[] = $row;
            $mids[]     = $row[0];
        }
        
        if (count($mids) > 0)
          $resultt = App::db()->query("UPDATE `mail_messages` SET `mail_message_status` = 'sent' WHERE `mail_message_id` IN (" . implode(',', $mids) . ") LIMIT $count");
        
        foreach ($messages AS $row)
        {
            if ($row['user_id'] > 0) {
                $to = $row['user_email'];
            } else {
                $to = $row['mail_message_email'];
            }

            if (!empty($to))
            {
                $row['mail_message_text'] = str_replace('%message_id%', $row['mail_message_id'], $row['mail_message_text']);
                
                $row['mail_message_subject'] = stripslashes($row['mail_message_subject']);
                
                if ($row['mail_template_order'] == 'news')
                    $row['from_name'] = "Maryjane";
                elseif ($row['from'] == 'valeriya@maryjane.ru')
                    $row['from_name'] = "Валерия Матросова";
                elseif ($row['from'] == 'mj@maryjane.ru' || $row['from'] == 'mstrrodin@yandex.ru') 
                    $row['from_name'] = "Сергей Родин";
                elseif ($row['mail_template_order'] == 'forDesigners')
                    $row['from_name']  = "Сергей @ Maryjane";
                elseif ($row['mail_template_order'] == 'dealers') {
                    if ($row['from'] == 'karolina@maryjane.ru') {
                        $row['from_name'] = 'Каролина';
                    } elseif ($row['from'] == 'sveta@maryjane.ru') {
                        $row['from_name'] = 'Светлана';
                    } elseif ($row['from'] == 'olga@maryjane.ru') {
                        $row['from_name'] = 'Ольга';
                    } elseif ($row['from'] == 'marysia@maryjane.ru') {
                        $row['from_name'] = 'Маруся';
                    } elseif ($row['from'] == 'mj@maryjane.ru' || $row['from'] == 'mstrrodin@yandex.ru') {
                        $row['from_name'] = 'Сергей';
                    } elseif ($row['from'] == 'smash@maryjane.ru') {
                        $row['from_name'] = 'Антон';
                    } elseif ($row['from'] == 'nata@maryjane.ru') {
                        $row['from_name'] = 'Наташа';
                    } elseif ($row['from'] == 'info@maryjane.ru') {
                        $row['from_name'] = 'Maryjane.ru';
                    } else {
                        $UU = user::find($row['from']);
                        $row['from_name'] = $UU->user_name;
                    }
                    
                } else
                    $row['from_name'] = (strpos($row['from'], 'allskins.ru') !== FALSE ? 'Allskins.ru' : 'Maryjane.ru');
                
                $message  = '<html><body>';
                $message .= stripslashes($row['mail_message_text']);
                // подменяем в шаблоне номер письма перед отправкой
                // код отслеживания открытия
                $message .= '<img src="http://www.maryjane.ru/track/' . $row['mail_message_id'] . '/' . md5($row['mail_message_id'] . SALT) . '/?utm_source=newsletter&utm_medium=_' . $row['mail_message_template_id'] . '_email_view&utm_campaign=_' . $row['mail_message_template_id'] . '_page_view" />';
                $message .= '</body></html>';
                
                // письмо с вложением
                //if (!empty($row['mail_message_attachments']))
                //{
                    $mail = new \PHPMailer;
                    $mail->CharSet = "utf-8";
                    $mail->setFrom($row['from'], $row['from_name']);
                    $mail->addAddress($to);
                    $mail->Subject = $row['mail_message_subject'];
                    $mail->msgHTML($message);
                    
                    foreach (json_decode($row['mail_message_attachments'], 1) as $f) 
                    {
                        $fpath = fileId2path($f);
                        $foo = explode('/', $fpath);
                        $mail->addAttachment(ROOTDIR . $fpath, end($foo));
                    }
                    
                    $mail->send();
                /*
                }
                else 
                {
                    $headers  = "From: " . $row['from_name'] . " <" . $row['from'] . ">\n";
                    $headers .= "Content-type: text/html; charset=utf-8 \n";
                    
                    $headers .= "Reply-To: <" . $row['from'] . ">\n";
                    $headers .= "Return-Path: <" . $row['from'] . ">\n";
                    $headers .= "X-Mailer: PHP/" . phpversion() . "\n";
                    
                    mail($to, ('=?UTF-8?B?' . base64_encode($row['mail_message_subject']) . '?='), $message, $headers, '-f' . $row['from']);
                }
                */
            }
        }
        
        die('STOP. DONE');
    }
    
    /**
     * @param mixed $userarray массив id или email получателей письма
     * @param int $templateid номер шаблона
     * @param array $reparray список переменных подставляемых в шаблоне
     * @param string $from отправитель письма
     * @param int $raiting приоритет отправки письма
     * @param mixed $attachments вложения
     */
    function send($userarray, $templateid, $reparray = null, $form = 'info@xxx.ru', $raiting = 10, $attachments = null)
    {
        if (!self::$templates[$templateid]) {
            $tpl = App::db()->query("SELECT * FROM `mail_templates` WHERE `mail_template_id` = '" . $templateid . "'")->fetch();
            self::$templates[$tpl['mail_template_id']] = $tpl;
        } else {
            $tpl = self::$templates[$templateid];
        }

        $subject = $tpl['mail_template_subject'];

        if (empty($text))
            $text = $tpl['mail_template_text'];

        if (empty($form))
            $form = 'info@maryjane.ru';

        preg_match_all ("/%(.*)%/U", $subject, $vararrays);

        $attachments = (array) $attachments;

        /**
         * замена шаблонов в заголовке письма
         */
        foreach ($vararrays[1] as $i => $val)
        {
            if (!in_array($val, array("userName", "userLogin", "userNameLogin", "userEmailLogin", "userBonuses", "userBonusesCount")))
            {
                $subject = str_ireplace('%' . $val . '%', $reparray[$val], $subject);
            }
        }

        if (empty($tpl['mail_template_file'])) 
        {
            /**
             * замена шаблонов в тексте
             */
            foreach ($reparray as $i => $val)
            {
                // замена простых переменных
                if (!is_array($val) && !is_object($val))
                {
                    $subject = str_ireplace("%" . $i . "%", $val, $subject);
                    
                    $spatern = '[IF ' . $i . ']';
                    $epatern = '[ENDIF ' . $i . ']';
                    
                    $spos = strpos($text, $spatern);
                    $epos = strpos($text, $epatern);
                    
                    if ($spos !== false)
                    {
                        while($spos !== false)
                        {
                            if ($val)
                            {
                                $block = substr($text, $spos + strlen($spatern), ($epos - ($spos + strlen($spatern))));
                                $block = str_ireplace("%" . $i . "%", $val, $block);
                            }
                            else
                            {
                                $block = '';
                            }
                            
                            $text = substr_replace($text, $block, $spos, $epos - $spos + strlen($epatern));
                            
                            $spos = strpos($text, $spatern);
                            $epos = strpos($text, $epatern);
                        }
                    }
                    else
                        $text = str_ireplace("%" . $i . "%", $val, $text);
                }
                // замена массивов
                else
                {
                    $spatern = '[BEGIN ' . $i . ']';
                    $epatern = '[END ' . $i . ']';
    
                    $spos = strpos($text, $spatern);
                    $epos = strpos($text, $epatern);
    
                    if ($spos !== false)
                    {
                        $block = substr($text, $spos + strlen($spatern), ($epos - ($spos + strlen($spatern))));
                        $blocks = array();
    
                        foreach ($val AS $kk => $v)
                        {
                            $blocks[$kk] = $block;
    
                            foreach ($v AS $k => $ii)
                            {
                                $blocks[$kk] = str_ireplace("%" . $i . '.' . $k . "%", $ii, $blocks[$kk]);
                            }
                        }
    
                        $text = substr_replace($text, implode('', $blocks), $spos, $epos - $spos + strlen($epatern));
                    }
                }
            }
        }
        else
        {
            extract($reparray);
            
            ob_start();
            require self::$tpl_folder . $tpl['mail_template_file'];
            $text = ob_get_contents();
            
            ob_end_clean();
        }

        /**
         * для рассылок оборачиваем ссылки в трекингкоды и автологины
         */
        preg_match_all("/href=\"(.*)\"/U", $text, $links, PREG_OFFSET_CAPTURE);
            
        $offset = 0;
        
        foreach ($links[0] AS $l)
        {
            $link = explode('#', str_replace(array('"', 'href='), '', $l[0]));
            
            $url = parse_url(str_replace('%quickLoginLink%&next=', '', $link[0]));
            
            if (!empty($link[0]) && (strpos($url['host'], '.maryjane.ru') !== false || empty($url['host'])) && !is_file(ROOTDIR  . $url['path']))
            {
                $link[0] = rtrim($link[0], '/');

                if (!empty($link[1]))
                    $anchor = array_pop($link);
                else
                    $anchor = '';

                if ($raiting == 0)
                {
                    if ($tpl['mail_template_order'] != 'forDesigners')
                    {
                        array_unshift($link, '%quickLoginLink%&next=');
                    }
                    
                    if (strpos($link[0], 'quickLoginLink') !== false) {
                        if (strpos($l[0], 'blog/view') !== false)
                            array_push($link, '/%3Futm_source%3Dnewsletter%26utm_medium%3Demail%26utm_campaign%3Dmailsender_%tpl%_1%26mid%3D%message_id%');
                        else
                            array_push($link, '/%3Futm_source%3Dnewsletter%26utm_medium%3Demail%26utm_campaign%3Dmailsender_%tpl%%26mid%3D%message_id%');
                    } else {
                        array_push($link, '/?utm_source=newsletter&utm_medium=email&utm_campaign=mailsender_%tpl%&mid=%message_id%');
                        // . ((strpos($l[0], 'blog/view') !== false) ? '_1' : ''));
                    }
                } 
                else
                {
                    if ($tpl['mail_template_order'] != 'misc') {
                        if (strpos($link[0], 'quickLoginLink') !== false) {
                            array_push($link, ($url['query'] ? '%26' : '/%3F') . 'utm_source%3Dmail%26utm_medium%3D' . ($tpl['mail_template_order'] == 'compred' ? 'compred' : 'notifier') . '%26utm_campaign%3Dmailsender_%tpl%%26mid%3D%message_id%');
                        } else {
                            array_push($link, ($url['query'] ? '&' : '/?') . 'utm_source=mail&utm_medium=' . ($tpl['mail_template_order'] == 'compred' ? 'compred' : 'notifier') . '&utm_campaign=mailsender_%tpl%&mid=%message_id%');
                        }
                    }
                }
                
                if (!empty($anchor))
                    array_push($link, '#' . $anchor);
                
                $link = implode('', $link);
                
                $text = substr_replace($text, $link, $l[1] + 6 + $offset, strlen($l[0]) - 7);
                
                $offset += strlen($link) - strlen($l[0]) + 7;
            }
        }

        $text = str_ireplace("%tpl%", $templateid, $text);
        
        // Номер шаблона
        $text .= '<p style="color:#ccc"><small>#' . $templateid . '</small></p>';

        if (count($emails) > 0)
        {
            if (count($userarray) == 0 && count($emails) > 0)
                $userarray = $emails;
            else
                $userarray = array_merge((array) $userarray, (array) $emails);
        }

        $i = 0;

        $sth = App::db()->prepare("INSERT INTO `mail_messages`
                 SET
                    `mail_message_template_id` = :templateid, 
                    `mail_message_subject` = :tmpsubj,
                    `mail_message_text` = :tmptext, 
                    `user_id` = :u, 
                    `mail_message_email` = :email, 
                    `raiting` = :raiting, 
                    `from` = :from,
                    `mail_message_attachments` = :attachments");

        foreach ((array) $userarray AS $u)
        {
            if (empty($u))
                continue;
            
            if (is_numeric($u)) {
                $rr = App::db()->query("SELECT * FROM `users` WHERE `id` = '{$u}' LIMIT 1");
                if ($rr->rowCount() == 0)
                    continue;
                else
                    $us = $rr->fetch();
            }
            else
            {
                if (validateEmail($u)) {
                    $us['user_email'] = $u;
                    $us['user_subscription_status'] = 'active';
                } else {
                    continue;
                } 
            }
                
            if ($us['user_subscription_status'] == 'active' || $tpl['mail_template_order'] == 'misc')
            {
                $sth->execute([
                    'templateid'  => $templateid,
                    'tmpsubj'     => $tmpsubj,
                    'tmptext'     => $tmptext,
                    'u'           => $u,
                    'email'       => $us['user_email'],
                    'raiting'     => $raiting,
                    'from'        => $form,
                    'attachments' => (count($attachments) > 0) ? json_encode($attachments) : '']);
                
                $results[] = App::db()->lastInsertId();
                
                $i++;
            }
        }
    
        return $results;
    }
}