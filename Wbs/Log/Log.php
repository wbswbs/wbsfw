<?php
/**
 * Klasse zum Loggen von Ereignissen in Dateien
 *
 * Vor der Benutzung die Dateirechte anpassen !
 *
 * $Logg = new log('file/log.txt');
 * $Logg->add('nachricht')  Schreibt die Nachricht mit Uhrzeit
 * $Logg->msg('nachricht')  Schreibt nur die Nachricht
 * $Logg->reset() Löscht die Log-Daten
 * $Logg->setActive(false) Deaktiviert das Loggin
 *
 * @author wbs
 * @version 0.8
 * @package wbsFramework
 */

/***************************************************************************************
 *   N A M E S P A C E
 ***************************************************************************************/

namespace wbs\Framework\Log;

use \Exception;
use wbs\Framework\Config\ENV;
use wbs\Framework\Ip\Ip;
use wbs\Framework\WbsClass;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Symfony\Component\HttpKernel\Tests\Fixtures\ExtensionNotValidBundle\ExtensionNotValidBundle;

/**
 * Class log
 *
 */
class Log extends WbsClass implements LoggerInterface
{


    const MAX_LEVEL_ARRAY = 3;

    private $file;
    private $active;
    private $db_is_active = false;

    /**
     * INIT
     *
     * @param string $filename
     *
     * @throws Exception
     */
    public function __construct($wbs,$filename)
    {
        parent::__construct($wbs);
        $this->active = true;
        $this->setFile($filename);
    }

    /**
     * Loggin an oder ausschalten
     *
     * @param bool $b_value
     */
    public function setActive($b_value)
    {
        $this->active = $b_value;
    }

    /**
     * LogDatei setzen
     *
     * @param string $value
     *
     * @throws Exception
     */
    public function setFile($value)
    {
        if (!$value) {
            $value = 'standard.log';
        }
        if (!file_exists($value)) {
            /**
             * When it does not exist it is not writable
             */
            try {
                $write_success = file_put_contents(
                    $value,
                    date(
                        'd.m.Y H:i:s',
                        time()
                    ).' Log created:' . PHP_EOL
                );
                chmod($value,0775);

                if($write_success == false){
                    throw new RuntimeException('Could not create Log File: '.$value);
                }
            } catch (Exception $e) {
                throw new RuntimeException('LogFile: ' . $e->getMessage());
            }

        }
        if (!is_writable($value)) {
            throw new RuntimeException("Log:File $value is not writable");
        }
        $this->file = $value;
    }

    /**
     * LogDatei setzen
     *
     * @throws Exception
     */
    public function emptyLogFile()
    {

        if (!file_exists($this->file)) {
            file_put_contents(
                $this->file,
                date(
                    'd.m.Y H:i:s',
                    time()
                ).
                ' Log reset:' . PHP_EOL
            );
        }
        if (!is_writable($this->file)) {
            throw new RuntimeException("Log:File $this->file is not writable");
        }
        file_put_contents(
            $this->file,
            date(
                'd.m.Y H:i:s',
                time()
            ).
            ' Log reset:' . PHP_EOL
        );
    }

    /**
     *
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Writes a Message with Timestamp
     *
     * @param string $str
     * @deprecated
     * @param array  $context
     *
     * @return void
     *
     * use info / error instead
     */
    public function add($str,array $context = [])
    {

        if ($this->active) {
            /** @noinspection DateUsageInspection */
            $the_add = date(
                    'd.m.Y H:i:s',
                    time()
                ) . '->' . $str . PHP_EOL;
            if(is_array($context)) {
                $this->arrayOutput($context,0,$the_add);
            }
            file_put_contents(
                $this->file,
                $the_add,
                FILE_APPEND
            );
        }
    }

    /**
     * Writes just ->Message
     *
     * @param mixed $str
     *
     * @return void
     */
    public function msg($str)
    {
        if ($this->active) {
            $the_add = '->' . $str . PHP_EOL;
            file_put_contents(
                $this->file,
                $the_add,
                FILE_APPEND
            );
        }
    }

    /**
     * Writes the String in a line
     *
     * @param mixed $str
     */
    public function line($str)
    {
        if ($this->active) {
            $the_add = $str . PHP_EOL;
            file_put_contents(
                $this->file,
                $the_add,
                FILE_APPEND
            );
        }
    }

    /**
     * Inhalt der Logdatei ausgeben
     */
    public function getContent()
    {
        return file_get_contents($this->file);
    }

    /**
     * Logs löschen
     */
    public function reset()
    {
        if ($this->active) {
            /** @noinspection DateUsageInspection */
            $the_add = '(' . date(
                    'd.m.Y H:i:s',
                    time()
                ) . ') RESET' . PHP_EOL;
            file_put_contents(
                $this->file,
                $the_add
            );
        }
    }
    /**
     * System is unusable.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function emergency($message, array $context = []){
        $this->add('[EMERGENCY] '.$message);

    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function alert($message, array $context = []){
        $this->add('[ALERT] '.$message,$context);

    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function critical($message, array $context = [],bool $send_mail = true){
        $this->add('[CRITICAL] '.$message,$context);
        if($send_mail){
            $this->sendError('[CRITICAL]', $message,$context);
        }
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function error($message, array $context = [],bool $send_mail = true){
        $this->add('[ERROR] '.$message,$context);
        if ($send_mail) {
            $this->sendError('[ERROR]', $message, $context);
        }
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function warning($message, array $context = []){
        $this->add('[WARNING] '.$message,$context);
        $this->sendError('[WARNING]', $message,$context);

    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function notice($message, array $context = []){
        $this->add('[NOTICE] '.$message,$context);

    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function info($message, array $context = []){
        $this->add('[INFO] '.$message,$context);

    }
    /**
     * Test Messages
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function test($message, array $context = []){
        $this->add('[TEST] '.$message,$context);

    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function debug($message, array $context = []){
        $this->add('[DEBUG] '.$message,$context);

    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed  $level
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function log($level, $message, array $context = []){
        $this->add('['.$level.'] '.$message,$context);
    }

    private function sendError($level,$message,$context)
    {
        $the_add='';
        $context['ENV:URL']             = $this->wbs()->env(ENV::URL_ABSOLUTE);
        $context['ENV:PROJECT_NAME']    = $this->wbs()->env(ENV::PROJECT_NAME);
        $context['wbsF:IP']              = Ip::calculateIP();
        // Server Variables
        $context['SERVER:PHP_SELF'] = $this->wbs()->getArrayValue('PHP_SELF',$_SERVER);
        $context['SERVER:SCRIPT_FILENAME'] = $this->wbs()->getArrayValue('SCRIPT_FILENAME',$_SERVER);
        $context['SERVER:HTTP_USER_AGENT'] = $this->wbs()->getArrayValue('HTTP_USER_AGENT',$_SERVER);
        $context['SERVER:ROOT_PATH'] = $this->wbs()->getArrayValue('ROOT_PATH',$_SERVER);
        $context['SERVER:USER'] = $this->wbs()->getArrayValue('USER',$_SERVER);
        $context['SERVER:REQUEST_METHOD'] = $this->wbs()->getArrayValue('REQUEST_METHOD',$_SERVER);
        $context['PHP:DATE'] = date('d.m.Y H:i:s');


        if (is_object($context)) {
            $context = (array)$context;
        }
        if(is_array($context)) {
                $this->arrayOutput($context,0,$the_add);
        }
        $message .= "\n\n".$the_add;
        $project = $this->wbs()->env(ENV::PROJECT_NAME)?:'N/A';
        $this->wbs()->smtp()->sendMailToSiteAdmin(
            "[{$project}] {$level} Script Error",
            $message,
            false
        );
        unset($the_add);
    }

    private function arrayOutput($array,$level,&$the_add)
    {
        $space = str_repeat('  ', $level);
        foreach($array as $key => $value) {
            if (is_object($value)) {
                $value = (array)$value;
            }
            if (is_array($value)) {
                $the_add .= $space.'-> ' . $key . '(' . count($value) . '): ' . PHP_EOL;
                $nextlevel = $level +1;
                if($nextlevel > self::MAX_LEVEL_ARRAY){
                    $the_add.=$space."-> Max Level ".self::MAX_LEVEL_ARRAY." erreicht !!!!". PHP_EOL;
                    return;
                }
                $this->arrayOutput($value,$nextlevel,$the_add);
            } else {
                $the_add .= $space.'-> ' . $key . ': ' . $value . PHP_EOL;
            }
        }
        return;
    }
}
