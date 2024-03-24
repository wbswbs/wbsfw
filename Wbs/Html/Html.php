<?php

namespace wbs\Framework\Html;

use wbs\Framework\Html\Bootstrap\Bootstrap;
use wbs\Framework\Html\Form\Form;
use wbs\Framework\Html\Table\Table;
use wbs\Framework\WbsClass;

class Html extends WbsClass {


    /**
     * @var \wbs\Framework\Html\Bootstrap\Bootstrap
     */
    protected $bootstrap;
    /**
     * @var \wbs\Framework\Html\Form\Form
     */
    protected $form;
    /**
     * @param string $css_class
     *
     * @return \wbs\Framework\Html\Table\Table
     */
    function getNewTable($css_class = '')
    {

//        require_once $this->wbs()->getWbsPath(). 'html/table/table.php';
        return new Table($css_class);
    }

//    /**
//     * @return Tableform
//     */
//    function getNewTableForm()
//    {
//
//        require_once $this->wbs()->getWbsPath().'html/form/form.inc.php';
//        $f = new Tableform();
//
//        return $f;
//    }

    /**************************************************************************
     * S U B C L A S S E S
     *************************************************************************/
    /**
     * Instance of the Bootstrap Form
     *
     * @return \wbs\Framework\Html\Bootstrap\Bootstrap
     */
    public function bootstrap(){

        if(is_null($this->bootstrap)){
            $this->bootstrap = new Bootstrap();
        }
        return $this->bootstrap;
    }
    /**
     * Instance of the Class Form
     * @return \wbs\Framework\Html\Form\Form
     */
    public function form(){

        if(is_null($this->form)){
            $this->form = new Form($this->wbs());
        }
        return $this->form;
    }
    /**
     * @param $var
     *
     * @return string
     */
    function my_stripslashes($var)
    {
        return stripslashes((string)$var);
    }

    /**
     * @param $var
     *
     * @return string
     */
    function html_escape($var)
    {
        return htmlentities(stripslashes($var));
    }

    /**
     * @param $var
     *
     * @return string
     */
    function html_out($var)
    {
        return nl2br(htmlentities(stripslashes($var)));
    }

    /**
     * @return string
     */
    function br()
    {
        return ('<br>' . PHP_EOL);
    }

    /**
     * @return string
     */
    function hr()
    {
        return ('<hr>' . PHP_EOL);
    }

    /**
     * @param $str
     *
     * @return string
     */
    function h1($str)
    {
        return ('<h1>' . $str . '</h1>' . PHP_EOL);
    }

    /**
     * @param $str
     *
     * @return string
     */
    function h2($str)
    {
        return ('<h2>' . $str . '</h2>' . PHP_EOL);
    }

    /**
     * @param $str
     *
     * @return string
     */
    function h3($str)
    {
        return ('<h3>' . $str . '</h3>' . PHP_EOL);
    }

    /**
     * @param $str
     *
     * @return string
     */
    function h4($str)
    {
        return ('<h4>' . $str . '</h4>' . PHP_EOL);
    }

    /**
     * @param $str
     *
     * @return string
     */
    function h5($str)
    {
        return ('<h5>' . $str . '</h5>' . PHP_EOL);
    }

    /**
     * @param $str
     *
     * @return string
     */
    function h6($str)
    {
        return ('<h6>' . $str . '</h6>' . PHP_EOL);
    }

    /**
     * @param $str
     *
     * @return string
     */
    function b($str)
    {
        return ('<b>' . $str . '</b>' . PHP_EOL);
    }

    /**
     * @param $str
     *
     * @return string
     */
    function i($str)
    {
        return ('<i>' . $str . '</i>' . PHP_EOL);
    }

    /**
     * @param string $str
     * @param string $class
     *
     * @return string
     */
    function div($str, $class = '')
    {
        empty($class) ? $s = '' : $s = ' class="' . $class . '" ';

        return ("<div$s>" . $str . '</div>' . PHP_EOL);
    }
//    function div($str){return ("<div>".$str."</div>".PHP_EOL);}

    /**
     * @param $str
     *
     * @return string
     */
    function span($str)
    {
        return ('<span>' . $str . '</span>' . PHP_EOL);
    }

    /**
     * @param $str
     *
     * @return string
     */
    function table($str)
    {
        return ('<table>' . PHP_EOL . $str . PHP_EOL . '</table>' . PHP_EOL);
    }

    /**
     * @param $str
     *
     * @return string
     */
    function tr($str)
    {
        return ('<tr>' . $str . '</tr>' . PHP_EOL);
    }

    /**
     * @param $str
     *
     * @return string
     */
    function td($str)
    {
        return ('<td>' . $str . '</td>');
    }

    /**
     * @param string $str content
     * @param string $class
     *
     * @return string
     */
    function ul($str, $class = '')
    {
        empty($class) ? $s = '' : $s = ' class="' . $class . '" ';

        return ("<ul$s>" . $str . '</ul>' . PHP_EOL);
    }

    /**
     * @param        $str
     * @param string $class
     *
     * @return string
     */
    function ol($str, $class = '')
    {
        empty($class) ? $s = '' : $s = ' class="' . $class . '" ';

        return ("<ol$s>" . $str . '</ol>' . PHP_EOL);
    }

    /**
     * @param        $str
     * @param string $class
     *
     * @return string
     */
    function li($str, $class = '')
    {
        empty($class) ? $s = '' : $s = ' class="' . $class . '" ';

        return ("<li$s>" . $str . '</li>' . PHP_EOL);
    }

    /**
     * @param        $str
     * @param string $class
     *
     * @return string
     */
    function p($str, $class = '')
    {
        empty($class) ? $s = '' : $s = ' class="' . $class . '" ';

        return ("<p$s>" . $str . '</p>' . PHP_EOL);
    }

    /**
     * @param        $str
     * @param string $class
     *
     * @return string
     */
    function pre($str, $class = '')
    {
        empty($class) ? $s = '' : $s = ' class="' . $class . '" ';

        return ("<pre$s>" . $str . '</pre>'
            . PHP_EOL);
    }

    /**
     * Spezielle Ausgabefunktionen
     *
     * @param $str
     *
     * @return string
     */
    function comment($str)
    {
        return '<!-- ' . $str . ' -->' . PHP_EOL;
    }

    /**
     * @param $str
     *
     * @return string
     */
    function info($str)
    {
        return ('<p class="alert alert-info">' . $str . '</p>' . PHP_EOL);
    }

    /**
     * @param $str
     *
     * @return string
     */
    function info_now($str)
    {
        return ('<p class="alert alert-info">' . $str . ' ' . date('m.d.y G:i:s') . '</p>' . PHP_EOL);
    }

    /**
     * @param $str
     *
     * @return string
     */
    function warning($str)
    {
        return ('<p class="alert alert-danger">' . $str . '</p>' . PHP_EOL);
    }

    /**
     * @param $str
     *
     * @return string
     */
    function warning_now($str)
    {
        return ('<p class="alert alert-danger">' . $str . ' ' . date('m.d.y G:i:s') . '</p>' . PHP_EOL);
    }

    /**
     * @param $str
     *
     * @return string
     */
    function line($str)
    {
        return ($str . '<br />' . PHP_EOL);
    }

    /**
     * @param $str
     *
     * @return string
     */
    function tline($str)
    {
        return ($str . PHP_EOL);
    } #Text line

    /**
     * @param $str
     *
     * @return string
     */
    function error_line($str)
    {
        return ('<b style="color:red;">' . $str . '</b><br />' . PHP_EOL);
    }

    /**
     * @param $name
     * @param $value
     *
     * @return string
     */
    function attribute($name, $value)
    {
        return ' ' . $name . '="' . htmlspecialchars($value) . '" ';
    }

    /**
     * @param $str
     */
    function shout($str)
    {
        echo $this->h1($str);
    }

    /**
     * Debug Funktionen
     *
     * @param string | array $str
     *
     * @return string
     */
    function my_print_r($str)
    {
        return ('<pre>' . print_r(
                $str,
                true
            ) . '</pre>' . PHP_EOL);
    }
    /***************************************************************************************
     *   L I N K S
     ***************************************************************************************/
    /**
     * Link (Text mit htmlentities)
     *
     * @param $url
     * @param $txt
     * @param $cssclass
     *
     * @return string
     */
    function href($url, $txt, $cssclass = '')
    {
        if ($cssclass) {
            return '<a href="' . $url . '" class="' . $cssclass . '">' . htmlentities(
                    $txt,
                    ENT_COMPAT,
                    'UTF-8'
                ) . '</a>';
        }

        return '<a href="' . $url . '">' . htmlentities(
                $txt,
                ENT_COMPAT,
                'UTF-8'
            ) . '</a>';
    }

    /**
     * Link (Text ohne htmlentities)
     *
     * @param $url
     * @param $txt
     * @param $cssclass
     *
     * @return string
     */
    function href_asis($url, $txt, $cssclass = '')
    {
        if ($cssclass) {
            return '<a href="' . $url . '" class="' . $cssclass . '">' . $txt . '</a>';
        }

        return '<a href="' . $url . '">' . $txt . '</a>';
    }

    /**
     * ahref, einen Link bauen
     *
     * @param string $url
     * @param string $txt
     * @param array $attributes assoziatives Array ('target'=>'_blank')
     *
     * @return string html
     */
    function ahref($url, $txt, $attributes = null)
    {
        $attr = '';
        foreach ((array)$attributes as $key => $value) {
            $attr .= ' ' . $key . '="' . $value . '" ';
        }

        return '<a href="' . $url . '" ' . $attr . ' >' . htmlentities(
                $txt,
                ENT_QUOTES,
                'UTF-8'
            ) . '</a>';
    }

    /**
     * build <a href=$url?$query>$txt</a>
     *
     * @param string $url
     * @param string $query
     * @param string $txt
     * @param string $class
     *
     * @return string html
     */
    public function build_href($url, $query, $txt, $class = '')
    {
        if ($class) {
            $cls = ' class="' . $class . '" ';
        } else {
            $cls = '';
        }

        if ($query) {
            return '<a href="' . $url . $this->getLinkVerkettung($url) . $query . '"' . $cls . '>' . $txt . '</a>';
        }

        return '<a href="' . $url . '"' . $cls . '>' . $txt . '</a>';
    }

    public function button($title, $attributes = null)
    {
        $attr = '';
        foreach ((array)$attributes as $key => $value) {
            $attr .= ' ' . $key . '="' . $value . '" ';
        }

        return '<button type="button" ' . $attr . '>' .
            htmlentities($title) .
            '</button > ' . PHP_EOL;

    }

    /**
     * $query mit & oder ? an url hängen
     *
     * @param $url
     * @param $query
     *
     * @return string
     */
    public function buildLink($url, $query)
    {
        /** @noinspection StrStrUsedAsStrPosInspection */
        if (stristr(
            $url,
            '?'
        )) {
            return $url . '&' . $query;
        } /** @noinspection RedundantElseClauseInspection */ else {
            return $url . '?' . $query;
        }
    }

    /**
     * Entscheiden, ob Parameter an eeinen Link mit ? oder mit & angehängt werden
     * Enter description here ...
     *
     * @param string $link
     *
     * @return string
     */
    function getLinkVerkettung($link)
    {
        if (stripos($link, '?') !== false) {
            return '&';
        }
        return '?';
    }

    /**
     * Attribute für html/xml Element zusammenbauen
     *
     * @param array $attributes assoziatives Array (target=>'_blank')
     *
     * @return string html
     */
    function getAttributes($attributes)
    {
        $attr = '';
        foreach ((array)$attributes as $key => $value) {
            $attr .= ' ' . $key . ' = "' . $value . '" ';
        }

        return $attr;
    }

    /**
     * Zeilenumbrüche in HTML Zeilenumbrüche ersetzen
     *
     * @param $str
     *
     * @return array|string|string[]
     */
    function my_nl2br($str)
    {
        $order = ["\r\n", "\n", "\r"];
        $replace = ' < br />';

        // Verarbeitet \r\n's zuerst, so dass sie nicht doppelt konvertiert werden
        return str_replace(
            $order,
            $replace,
            $str
        );
    }

    /**
     * JavaSkriptMeldung ausgeben
     *
     * @param string $the_msg Die Meldung
     *
     * @return string html/js
     * @TODO Check Replacement for unescape
     */
    function getJavaScriptMessage($the_msg)
    {
        $html = '<script type="text/javascript">alert( unescape("' . $the_msg . '"));</script>';

        return $html;
    }

    /**
     * Einen String in einen Filenamen umwandeln (klein und ohne Leerzeichen)
     *
     * @param string $word
     *
     * @return string
     */
    function string_to_filename($word)
    {
        $syn = str_replace(array('"', "'", '/', '\\', 'ä', 'ö', 'ü', 'ß'), array('', '', '', '', 'ae', 'oe', 'ue', 'sz'), strtolower($word));
        $tmp = preg_replace(
            '/^\W+|\W+$/',
            '',
            $syn
        ); // remove all non-alphanumeric chars at begin & end of string
        $tmp = preg_replace(
            '/\s+/',
            '_',
            $tmp
        ); // compress internal whitespace and replace with _
        #return strtolower(preg_replace('/\W-/', '', $tmp)); // remove all non-alphanumeric chars except _ and -
        return strtolower(
            preg_replace(
                '/[^0-9a-zA-Z_-]/',
                '',
                $tmp
            )
        );

    }

    /**
     * Div Container mit der Klase card
     *
     * @param string $html
     *
     * @return string
     */
    public function card($html)
    {
        return $this->div($html, 'card');
    }

    /**
     * Div Container mit der Klase container
     *
     * @param string $html
     *
     * @return string
     */
    public function container($html)
    {
        return $this->div($html, 'container');
    }
}