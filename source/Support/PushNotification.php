<?php

namespace Source\Support;

use Source\Models\CafeApp\AppPushNotificationRegistration;

class PushNotification
{

    /**
     * URL para a API do FCM
     * @var string 
     */
    const API = 'https://fcm.googleapis.com/fcm/send';

    private array $chave;
    private string $title;
    private string $body;

    public function __construct(?string $title = 'Hellow World', ?string $body = 'Test Push Notification', ?array $chave = [])
    {
        $this->chave = (!empty($chave)) ? $chave : $this->sendTo();
        $this->title = $title;
        $this->body = $body;
    }

    public function sendTo(): array
    {
        $chave_push = [];

        $chaves = (new AppPushNotificationRegistration())->getData();
        foreach ($chaves as $chave) {
            array_push($chave_push,  $chave->token_push_notification);
        }

        return $chave_push;
    }


    public function run()
    {
        return $this->_enviar();
    }

    /**
     * Realiza o envio para o Servidor
     * @param $dados array
     * @return array
     * @throws 1 Chave do Servidor Errada
     * @throws 2 Token do dispositivo errado
     */
    private function _enviar()
    {
        //JSON
        $fields['registration_ids'] =  $this->chave;

        $fields['notification'] = [
            "title" => $this->title,
            "body" => $this->body,
            "icon" => 'https://i.imgur.com/7J6HTD0.png',
            'sound' => 1
        ];

        $json = json_encode($fields);

        //Headers
        $headers = [
            'Content-Type:application/json',
            'Authorization:key=' . TOKEN_PUSHNOTIFICATION
        ];

        $ch = curl_init(self::API);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $resultado = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpcode == 401) throw new \Exception('Chave do Servidor errada', 1);
        if ($httpcode == 200) {
            $resultado = json_decode($resultado, true);

            if (!empty($resultado['failure']))
                throw new \Exception('Não foi possível enviar para todos os dispositivos. Cheque novamente o token do dispositivo', 2);

            return $resultado;
        }
    }
}
