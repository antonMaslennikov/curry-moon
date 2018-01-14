<?php
/**
 * проверка орфографии от Яндекса
 */
namespace admin\application\controllers;

trait SpellerTrait {
    
    public function action_spellchecker()
    {
        // скрипт предоставлен разработчиками компании ASTRALiENS
        $headers=array("Content-Type: application/json");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://speller.yandex.net/services/tinyspell");
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_POSTFIELDS, file_get_contents('php://input'));
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $data = curl_exec($ch);
        
        curl_close($ch);
        echo $data;
    }
}