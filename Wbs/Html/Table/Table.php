<?php

namespace wbs\Framework\Html\Table;

class Table{
    /**
     * @var string
     */
    public $html;

    private $css_class;

    /**
     * @param string $css_class
     * @param string $css_id
     */
    public function __construct($css_class = '', $css_id = '')
    {
        $this->css_class = $css_class;
        $the_class = $css_class ? ' class="' . $css_class . '" ' : '';
        $the_id = $css_id ? ' id="' . $css_id . '" ' : '';
        $this->html = '<table ' . $the_class . $the_id . '>' . PHP_EOL;
    }

    /**
     * @param $two_dimensional_array
     *
     * @return string
     */
    public function fromArray($two_dimensional_array,$key_as_header = false){

        $the_class = $this->css_class ? ' class="' . $this->css_class . '" ' : '';

        $t = "<table $the_class>" . PHP_EOL;

        foreach ((array)$two_dimensional_array as $but_row) {
            if($key_as_header){
                $t .= "<thead>" . PHP_EOL;
                $head_row = $but_row;
                $t .= "\t<tr>" . PHP_EOL;
                foreach ((array)$head_row as $key=>$td) {
                    $t .= "\t\t<th>$key</th>" . PHP_EOL;
                }
                $t .= "\t</tr>" . PHP_EOL;
                $t .= "</thead>" . PHP_EOL;
                $key_as_header = false;
            }
            $t .= "\t<tr>" . PHP_EOL;
            foreach ((array)$but_row as $td) {
                $t .= "\t\t<td>$td</td>" . PHP_EOL;
            }
            $t .= "\t</tr>" . PHP_EOL;
        }
        $t .= '</table>';
        return $t;
    }
    /**
     * Start a New Table
     *
     * @param string $css_class
     * @param string $css_id
     */
    public function start($css_class = '', $css_id = '')
    {
        $the_class = $css_class ? ' class="' . $css_class . '" ' : '';
        $the_id = $css_id ? ' id="' . $css_id . '" ' : '';
        $this->html = '<table ' . $the_class . $the_id . '>' . PHP_EOL;
    }

    /**
     * @param $str
     */
    public function add($str)
    {
        $this->html .= $str . PHP_EOL;
    }

    /**
     * Close the form
     */
    public function end()
    {
        $this->html .= '</table>' . PHP_EOL;
    }

    /**
     * Get the HTML Text
     *
     * @return string
     */
    public function getHTML()
    {
        return $this->html;
    }

    /**
     *
     * @param string $css
     */
    public function new_line($css = '')
    {
        $fill_css = '';
        if (!empty($css)) {
            $fill_css = ' class="' . $css . '" ';
        }
        $this->html .= '<tr' . $fill_css . '>';
    }

    /**
     * Eine neue Tabellenzeile mit übergebenen HTML Attributen
     *
     * @param array $attributes Assoziatives Array
     */
    public function new_line_attribute($attributes)
    {
        $str_attributes = '';
        foreach ($attributes as $key => $value) {
            $str_attributes .= $this->attribute(
                $key,
                $value
            );
        }
        $this->html .= '<tr ' . $str_attributes . '>';
    }

// end line (row)
    public function end_line()
    {
        $this->html .= '</tr>' . PHP_EOL;
    }

    /**
     * @param     $str
     * @param int $colspan
     */
    public function td($str, $colspan = 1)
    {
        if ($colspan > 1) {
            $this->html .= '<td ' . $this->attribute(
                    'colspan',
                    $colspan
                ) . '>';
        }
        else {
            $this->html .= '<td>';
        }
        $this->html .= $str . '</td>' . "\n";
    }

    /**
     * @param     $str
     * @param int $colspan
     */
    public function th($str, $colspan = 1)
    {
        if ($colspan > 1) {
            $this->html .= '<th ' . $this->attribute(
                    'colspan',
                    $colspan
                ) . '>';
        }
        else {
            $this->html .= '<th>';
        }
        $this->html .= $str . '</th>' . "\n";
    }

    /**
     * @param     $str
     * @param     $css_class
     * @param int $colspan
     */
    public function td_style($str, $css_class, $colspan = 1)
    {
        if ($colspan > 1) {
            $this->html .= '<td class="' . $css_class . '" ' . $this->attribute(
                    'colspan',
                    $colspan
                ) . '>';
        }
        else {
            $this->html .= '<td class="' . $css_class . '">';
        }
        $this->html .= $str . '</td>' . "\n";
    }

// create $n empty cells

    /**
     * @param int $n
     */
    public function empty_cell($n = 1)
    {
        $this->html .= str_repeat(
                '<td>&nbsp;</td>',
                $n
            ) . "\n";
    }

    /**
     * Create a html attribute for example name="value
     *
     * @param $name
     * @param $value
     *
     * @return string
     */
    public function attribute($name, $value)
    {
        return $name . '="' . htmlspecialchars($value) . '" ';
    }

// show red error message

    /**
     * @param $txt
     */
    public function show_error_msg($txt)
    {
        $this->html .= '<p><span class="red">' . htmlentities(
                $txt,
                ENT_QUOTES,
                'UTF-8'
            ) . '</span></p>' . "\n";
    }

}