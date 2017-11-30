<?php

namespace application\models;
    
use \smashEngine\core\App AS App; 
use \PHPMailer\PHPMailer\PHPMailer;
use \PDO;

class mail extends \smashEngine\core\Model
{
    /**
     * @var array массив, содержащий кэш шаблонов 
     * чтобы при массовой рассылке одного шаблона не выдёргивать текст каждый раз 
     */
    protected static $templates = array();
    
    public static $tpl_folder = 'application/views/mail/';
    
    static function sendAllMessages($count = 100, $raiting = array(0, 10))
    {         
        $result = App::db()->query("SELECT mm.*, u.`user_email`, mt.`mail_template_order`
                               FROM `mail` mm
                                    LEFT JOIN `users` u ON u.`id` = mm.`user_id`
                                    LEFT JOIN `mail__templates` mt ON mt.`mail_template_id` = mm.`mail_message_template_id`    
                               WHERE `mail_message_status` = 'awaiting' AND `raiting` IN ('" . implode("', '", $raiting) . "')
                               ORDER BY `raiting` DESC 
                               LIMIT $count");
                               
        foreach ($result->fetchAll(PDO::FETCH_BOTH) AS $row) {
            $messages[] = $row;
            $mids[]     = $row[0];
        }
        
        if (count($mids) > 0)
          $resultt = App::db()->query("UPDATE `mail` SET `mail_message_status` = 'sent' WHERE `mail_message_id` IN (" . implode(',', $mids) . ") LIMIT $count");
        
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
                
                $row['from_name'] = siteName;
                
                $message  = '<html><body>';
                $message .= stripslashes($row['mail_message_text']);
                // подменяем в шаблоне номер письма перед отправкой
                // код отслеживания открытия
                $message .= '<img src="' . mainUrl . '/track/' . $row['mail_message_id'] . '/' . md5($row['mail_message_id'] . SALT) . '/?utm_source=newsletter&utm_medium=_' . $row['mail_message_template_id'] . '_email_view&utm_campaign=_' . $row['mail_message_template_id'] . '_page_view" />';
                $message .= '</body></html>';
                
                
                $mail = new PHPMailer;
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
            }
        }
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
            $tpl = App::db()->query("SELECT * FROM `mail__templates` WHERE `mail_template_id` = '" . $templateid . "'")->fetch();
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

        extract($reparray);

        ob_start();
        require self::$tpl_folder . $tpl['mail_template_file'];
        $text = ob_get_contents();

        ob_end_clean();

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

        $sth = App::db()->prepare("INSERT INTO `mail`
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
                
            if ($us['user_subscription_status'] == 'active')
            {
                $sth->execute([
                    'templateid'  => $templateid,
                    'tmpsubj'     => $subject,
                    'tmptext'     => $text,
                    'u'           => $u,
                    'email'       => $us['user_email'],
                    'raiting'     => $raiting,
                    'from'        => $form,
                    'attachments' => (count($attachments) > 0) ? json_encode($attachments) : '']);
                
                $results[] = App::db()->lastInsertId();
                
                $i++;
            }
        }
    
        // заглушка чтоыб сразу отправлять все сообщения, но лучше вешать на крон
        self::sendAllMessages();
        
        return $results;
    }
}