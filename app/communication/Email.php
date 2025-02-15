<?php

namespace App\communication;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

class Email{

    /**
     * Credenciais de acesso SMTP
     */
    private static $HOST;

    private static $USER;

    private static $PASS;

    private static $SECURE;

    private static $PORT;

    private static $CHARSET;

    /**
    * Dados do remetente
    */
    public $FROM_EMAIL;

    public $FROM_NAME;

    /**
     * Mensagem de erro do envio
     */
    private $error;

    /**
     * Inicializa as configurações do e-mail
     */
    public static function init($HOST, $USER, $PASS, $SECURE, $PORT, $CHARSET)
    {

        self::$HOST = $HOST;

        self::$USER = $USER;

        self::$PASS = $PASS;

        self::$SECURE = $SECURE;

        self::$PORT = $PORT;

        self::$CHARSET = $CHARSET;

    }

    /**
     * Retorna o erro de envio, se houver
     */
    public function getError()
    {

        return $this->error;

    }

    /**
     * Envia um e-mail
     */
    public function sendEmail($addresses, $subject, $body, $attachments = [], $ccs = [], $bccs = [])
    {

        $this->error = "";

        $obMail = new PHPMailer(true);
        
        try {

            $obMail->SMTPDebug = 3; // Níveis: 1 = básico, 2 = detalhado, 3 = muito detalhado
            $obMail->Debugoutput = 'html';

            $obMail->isSMTP();

            $obMail->SMTPAuth = true;

            $obMail->Host = self::$HOST;

            $obMail->SMTPAuth = true;

            $obMail->Username = self::$USER;

            $obMail->Password = self::$PASS;

            $obMail->Port = self::$PORT;

            $obMail->CharSet = self::$CHARSET;

            $obMail->setFrom(self::$USER, $this->FROM_NAME);

            // Configuração de segurança
            if (self::$SECURE === 'TLS') {

                $obMail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

            } elseif (self::$SECURE === 'SSL') {

                $obMail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;

            }

            // Adiciona destinatários
            foreach ((array) $addresses as $address) {

                $obMail->addAddress($address);

            }

            // Adiciona anexos
            foreach ((array) $attachments as $attachment) {

                $obMail->addAttachment($attachment);

            }

            // Adiciona CCs
            foreach ((array) $ccs as $cc) {

                $obMail->addCC($cc);

            }

            // Adiciona BCCs
            foreach ((array) $bccs as $bcc) {

                $obMail->addBCC($bcc);

            }

            // Configura e envia
            $obMail->isHTML(true);

            $obMail->Subject = $subject;

            $obMail->Body = $body;

            return $obMail->send();

        } catch (PHPMailerException $e) {

            $this->error = $e->getMessage();

            return false;

        }

    }

}