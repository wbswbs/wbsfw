<?php

namespace wbs\Framework\Mail;


use Exception;
use wbs\Framework\Config\ENV;
use wbs\Framework\Ip\Ip;
use wbs\Framework\WbsClass;
use PHPMailer\PHPMailer\PHPMailer;
use wbs\Framework\Wbs;

class Smtp extends WbsClass
{
    protected string $host;
    protected string $port;
    protected string $user;
    protected string $pass;
    protected string $from_email;
    protected string $from_name;

    const MAX_LEVEL_ARRAY = 3;

    public function __construct(Wbs $wbs)
    {
        parent::__construct($wbs);
        $this->host = $wbs->env(ENV::SMTP_HOST);
        $this->port = $wbs->env(ENV::SMTP_PORT);
        $this->user = $wbs->env(ENV::SMTP_USER);
        $this->pass = $wbs->env(ENV::SMTP_PASS);
        $this->from_email = $wbs->env(ENV::SMTP_FROM_EMAIL);
        $this->from_name = $wbs->env(ENV::SMTP_FROM_NAME);

    }

    /**
     * @param $subject
     * @param $body
     * @param bool $add_context
     * @param bool $add_post
     * @param bool $add_get
     * @throws Exception
     */
    public function sendMailToSiteAdmin($subject, $body, bool $add_context = true, bool $add_post = false, $add_get = false)
    {

        $context_text = '';

        if ($add_context) {

            $context['ENV:URL'] = $this->wbs()->env(ENV::URL_ABSOLUTE);
            $context['ENV:PROJECT_NAME'] = $this->wbs()->env(ENV::PROJECT_NAME);
            $context['MDF:IP'] = Ip::calculateIP();
            // Server Variables
            $context['SERVER:PHP_SELF'] = $this->wbs()->getArrayValue('PHP_SELF', $_SERVER);
            $context['SERVER:SCRIPT_FILENAME'] = $this->wbs()->getArrayValue('SCRIPT_FILENAME', $_SERVER);
            $context['SERVER:HTTP_USER_AGENT'] = $this->wbs()->getArrayValue('HTTP_USER_AGENT', $_SERVER);
            $context['SERVER:ROOT_PATH'] = $this->wbs()->getArrayValue('ROOT_PATH', $_SERVER);
            $context['SERVER:USER'] = $this->wbs()->getArrayValue('USER', $_SERVER);
            $context['SERVER:REQUEST_METHOD'] = $this->wbs()->getArrayValue('REQUEST_METHOD', $_SERVER);
            $context['PHP:DATE'] = date('d.m.Y H:i:s');

            if (is_object($context)) {
                $context = (array)$context;
            }
            if (is_array($context)) {
                $this->arrayOutput($context, 0, $context_text);
            }
            $body .= "\n\n" . $context_text;
        }

        if($add_post){
            $post = 'POST:'."\n\n";

            if (is_array($_POST)) {
                $this->arrayOutput($_POST, 0, $post);
            }
            $body .= "\n\n" . $post;

        }
        if($add_get){

            $get = 'GET:'."\n\n";

            if (is_array($_GET)) {
                $this->arrayOutput($_GET, 0, $get);
            }
            $body .= "\n\n" . $get;

        }

        $recipent = explode(',', $this->wbs()->env(ENV::SITE_ADMIN));
        $this->sendMail($recipent, $subject, 'To the Site Admin of ' .
            $this->wbs()->getUrlAbsolute() . PHP_EOL . PHP_EOL . $body);
    }

    private function arrayOutput($array, $level, &$the_add)
    {
        $space = str_repeat('  ', $level);
        foreach ($array as $key => $value) {
            if (is_object($value)) {
                $value = (array)$value;
            }
            if (is_array($value)) {
                $the_add .= $space . '-> ' . $key . '(' . count($value) . '): ' . PHP_EOL;
                $nextlevel = $level + 1;
                if ($nextlevel > self::MAX_LEVEL_ARRAY) {
                    $the_add .= $space . "-> Max Level " . self::MAX_LEVEL_ARRAY . " erreicht !!!!" . PHP_EOL;
                    return;
                }
                $this->arrayOutput($value, $nextlevel, $the_add);
            } else {
                $the_add .= $space . '-> ' . $key . ': ' . $value . PHP_EOL;
            }
        }
        return;
    }

    /**
     * @param string | array $recipient
     * @param $subject
     * @param $body
     * @throws Exception
     * @throws Exception
     */
    public function sendMail($recipient, $subject, $body)
    {
        try {
            $this->wbs()->log()->info('[SMTP]' . __FUNCTION__ . '() Subject: ' . $subject);
//            .var_export($recipient,true));
//            $this->wbs()->log()->info('[SMTP] '.__FUNCTION__.' From Email: '.$this->config()->get(self::CFG_FROM_EMAIL));

            $mail = new PHPMailer(true); // create a new object
            $mail->CharSet = 'UTF-8';
            $mail->IsSMTP(); // enable SMTP
            $mail->SMTPAuth = true; // authentication enabled
            $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for G
            // Default Value is better, is 1.3
//            $mail->SMTPOptions = [
//                'ssl' => ['crypto_method' => STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT]
//            ];
            $mail->Host = $this->host;
            $mail->Port = $this->port;
            $mail->Username = $this->user;
            $mail->Password = $this->pass;
            $mail->SetFrom(
                $this->from_email,
                $this->from_name
            ); //"noreply@wbs.de");
            $mail->Subject = $subject;
            $mail->Body = $body;
            if (is_array($recipient)) {
                foreach ($recipient as $the_recipient) {
                    $mail->AddAddress($the_recipient);
                }
            } else {
                $mail->AddAddress($recipient);

            }
            $mail->Send();
//        }catch(phpmailerException $e){
//            $this->wbs()->log()->critical('[SMTP][PHPMailer Exception] '.$e->getMessage());
        } catch (\PHPMailer\PHPMailer\Exception $e) {
            $this->wbs()->log()->critical('[SMTP][Exception] ' . $e->getMessage(),[],false);
        }
    }

    /**
     * Old Configuration
     *
     * @param array $data
     * @throws Exception
     * @throws Exception
     */
    public function sendMailByData($data = array())
    {
        try {
            $mail = new PHPMailer(true); // create a new object
            $mail->CharSet = 'UTF-8';
            $mail->IsSMTP(); // enable SMTP
            $mail->SMTPAuth = true; // authentication enabled
            $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
            $mail->Host = $this->host;
            $mail->Port = $this->port;
            $mail->Username = $this->user;
            $mail->Password = $this->pass;
            $mail->SetFrom(isset($data['recipient']) ? $data['recipient'] : "noreply@wbs.de");
            $mail->Subject = $data['subject'];
            $mail->Body = $data['body'];
            $mail->AddAddress($data['sendTo']);
            $mail->Send();
        } catch (\PHPMailer\PHPMailer\Exception $e) {
            $this->wbs()->log()->critical('[SMTP][PHPMailer Exception] ' . $e->getMessage(),[],false);
        } catch (Exception $e) {
            $this->wbs()->log()->critical('[SMTP][Exception] ' . $e->getMessage(),[],false);
        }
    }

}