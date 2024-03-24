<?php

namespace wbs\Framework\Html\Form;

use wbs\Framework\WbsClass;

class Form extends WbsClass
{
    /**
     * @var string
     */
    protected $html_string;

    /**
     * @var array
     */
    protected $country_list;

    /**
     */
    public function __construct($wbs)
    {
        parent::__construct($wbs);

        $this->country_list = [];
        $this->html_string = '';
    }

    /**
     * @param $value
     */
    public function setHtml($value)
    {
        $this->html_string = $value;
    }

    /**
     * @return string
     */
    public function getHtml()
    {
        return $this->html_string;
    }

    /******************************************************************************************
     *     G E N D E R
     *****************************************************************************************/
    /**
     * Eine Auswahlliste für das Geschlecht
     *
     * @param string $selected
     * @param string $css_class
     * @param string $lang
     *
     * @return string
     */
    function getGenderSelect($selected, $css_class = '', $lang = 'de')
    {
        $sex = $this->readGender($lang);
        if ($css_class) {
            $css_class = ' class="' . $css_class . '" ';
        }
        $html = "<select $css_class name='sex' size='1'>" . PHP_EOL;
        foreach ($sex as $key => $land) {
            $selected_attribut = ($key == $selected) ? ' selected="selected "' : '';
            $html .= "<option value=\"$key\" $css_class $selected_attribut>" . htmlentities(
                    $land,
                    ENT_COMPAT,
                    'utf-8'
                ) . '</option>';
        }
        $html .= '</select>';

        return $html;
    }

    /**
     * @param        $short
     * @param string $lang
     *
     * @return string
     */
    function getGenderName($short, $lang = 'de')
    {
        $sex = $this->readGender($lang);
        if (array_key_exists(
            $short,
            $sex
        )) {
            return $sex[ $short ];
        }

        return 'Unbekanntes Geschlecht: ' . $short;
    }

    /**
     * @param string $lang
     *
     * @return array
     */
    function readGender($lang = 'de')
    {
        switch ($lang) {
            case 'en':
                $sex['w'] = 'Miss';
                $sex['m'] = 'Mister';
                $sex['d'] = '';
                break;
            case 'de':
            default:
                $sex['w'] = 'Frau';
                $sex['m'] = 'Herr';
                $sex['d'] = '';
        }

        return $sex;
    }

    /**
     * Einen Link als Button ausgeben
     *
     * @param string       $the_link
     * @param string       $the_name
     * @param string       $css_class
     * @param array|string $hidden_values Assoziatives Array von Hidden values (key=>value)
     *
     * @return string htmlform
     */
    public static function getButton($the_link, $the_name, $css_class = '', $hidden_values = '')
    {
        $hidden_html = '';

        foreach ((array)$hidden_values as $key => $value) {
            $hidden_html .= '<input type="hidden" name="' . $key . '" value="' . $value . '">' . PHP_EOL;
        }
        if ($css_class <> '') {
            return '<form action="' . $the_link . '"><div>
' . $hidden_html . '
<input type="submit" value="' . $the_name . '" class="' . $css_class . '">
</div></form>';
        }

        $the_link_button = '<form action="' . $the_link . '"><div>
' . $hidden_html . '
<input type="submit" value="' . $the_name . '" style="cursor:pointer;">
</div></form>';

        return $the_link_button;
    }

    /**
     * Einen Link als Button ausgeben
     * Funktionsweise umgestellt auf POST !
     *
     * @param string $the_link
     * @param string $the_name
     * @param string $hidden_name
     * @param string $hidden_value
     * @param string $css_class
     * @param array  $hidden_values Assoziatives Array von Hidden values (key=>value)
     *
     * @return string htmlform
     */
    public function getLinkButtonWithoutID($the_link, $the_name, $hidden_name = 'wbs', $hidden_value = 'wbs', $css_class = '', $hidden_values = null)
    {
        $hidden_html = '';
        //echo 'Link Count '.count((array)$hidden_values);

        foreach ((array)$hidden_values as $key => $value) {
            $hidden_html .= '<input type="hidden" name="' . $key . '" value="' . $value . '" />' . PHP_EOL;
        }
        if ($css_class <> '') {
            return '<form action="' . $the_link . '" method="POST"><div>
<input type="submit" value="' . $the_name . '" class="' . $css_class . '" />
<input type="hidden" name="' . $hidden_name . '"  value="' . $hidden_value . '" />
' . $hidden_html . '
</div></form>';
        }

        $the_link_button = '<form action="' . $the_link . '" method="POST"><div>
<input type="submit" value="' . $the_name . '" style="cursor:pointer;" />
<input type="hidden" name="' . $hidden_name . '" value="' . $hidden_value . '" />
' . $hidden_html . '
</div></form>';

        return $the_link_button;
    }

    /**
     * Einen Link als Button ausgeben
     * Funktionsweise umgestellt auf POST !
     *
     * @param string $the_link
     * @param string $the_name
     * @param string $hidden_name
     * @param string $hidden_value
     * @param string $css_class
     * @param array  $hidden_values Assoziatives Array von Hidden values (key=>value)
     *
     * @return string htmlform
     */
    public function getLinkButton($the_link, $the_name, $hidden_name = 'wbs', $hidden_value = 'wbs', $css_class = '', $hidden_values = null)
    {
        $hidden_html = '';
        //echo 'Link Count '.count((array)$hidden_values);

        foreach ((array)$hidden_values as $key => $value) {
            $hidden_html .= '<input type="hidden" name="' . $key . '" id="' . $key . '"value="' . $value . '" />' . PHP_EOL;
        }
        if ($css_class <> '') {
            return '<form action="' . $the_link . '" method="POST"><div>
<input type="submit" value="' . $the_name . '" class="' . $css_class . '" />
<input type="hidden" name="' . $hidden_name . '" id="' . $hidden_name . '" value="' . $hidden_value . '" />
' . $hidden_html . '
</div></form>';
        }

        $the_link_button = '<form action="' . $the_link . '" method="POST"><div>
<input type="submit" value="' . $the_name . '" style="cursor:pointer;" />
<input type="hidden" name="' . $hidden_name . '" id="' . $hidden_name . '" value="' . $hidden_value . '" />
' . $hidden_html . '
</div></form>';

        return $the_link_button;
    }

    /**
     * Eine Selectbox ausgeben, die das Formular bei Änderung absendet
     * onchange="this.form.submit();"
     *  create select list rows[0] = name =  value
     *
     * @param string $name
     * @param string | array $rows
     * @param int    $selected
     * @param string $class
     *
     * @return string
     */
    public function select_simple_submit($name, $rows, $selected = -1, $class = 'wbs_form')
    {
        $html = '<select class="' . $class . '" ' . $this->html()->attribute(
                'name',
                $name
            ) .
            $this->html()->attribute(
                'id',
                $name
            ) . $this->html()->attribute(
                'onchange',
                'this.form.submit()'
            ) . '>' . "\n";
        foreach ((array)$rows as $row) {
            if (is_array($row)) {
                $key = $row[0];
                $value = $row[0];
            } else {
                $key = $row;
                $value = $row;
            }
            $html .= '<option ' . $this->html()->attribute(
                    'value',
                    $value
                );
            if ($selected == $value) {
                $html .= 'selected="selected" ';
            }
            $listentry = str_replace(
                ' ',
                '&nbsp;',
                htmlentities($key)
            );
            $html .= ">$listentry</option>" . PHP_EOL;
        }
        $html .= '</select>' . PHP_EOL;

        return $html;
    }

    /**
     * Eine Selectbox ausgeben
     *  create select list rows[0] = name =  value
     *
     * @param string $name
     * @param        $rows
     * @param int    $selected
     * @param string $class
     *
     * @return string
     */
    public function select_simple($name, $rows, $selected = -1, $class = 'wbs_form')
    {
        #shout('Selected:'.$selected);
        $html = '<select class="' . $class . '" ' . $this->html()->attribute(
                'name',
                $name
            ) . $this->html()->attribute(
                'id',
                $name
            ) . '>' . "\n";
        foreach ((array)$rows as $row) {
            if (is_array($row)) {
                $key = $row[0];
                $value = $row[0];
            } else {
                $key = $row;
                $value = $row;
            }
            $html .= '<option ' . $this->html()->attribute(
                    'value',
                    $value
                );
            if ($selected == $value) {
                $html .= 'selected="selected" ';
            }
            $listentry = str_replace(
                ' ',
                '&nbsp;',
                htmlentities(
                    $key,
                    ENT_COMPAT,
                    'UTF-8'
                )
            );
            $html .= ">$listentry</option>" . PHP_EOL;
        }
        $html .= '</select>' . PHP_EOL;

        return $html;
    }

    /**
     * Eine Selectbox ausgeben
     *  create select list rows[0] = name, rows[1] = value
     *
     * @param string $name Name des <SELECT Elements>
     * @param array  $rows zweidimensionales Array
     * @param int    $selected Schlüssel des ausgewähltes Element
     * @param string $class css-Klasse
     * @param string $attributes Attribute als String z.B. ' onclick="Klick;" '
     * @param string $id ID des <Select> Elements, Standard = Name
     *
     * @return string
     */
    public function select_list($name, $rows, $selected = -1, $class = 'wbs_form', $attributes = '', $id = '')
    {
        $id_name = $id ? $id : $name;
//        $error = '';
        $html = '<select class="' . $class . '" ' . $this->html()->attribute(
                'name',
                $name
            ) . $this->html()->attribute(
                'id',
                $id_name
            ) . ' ' . $attributes . ' >' . "\n";
        foreach ((array)$rows as $row) {
            if (array_key_exists(
                1,
                $row
            )) {
                $html .= '<option ' . $this->html()->attribute(
                        'value',
                        $row[1]
                    );
                if ($selected == $row[1]) {
                    $html .= 'selected="selected" ';
                }
                $listentry = str_replace(
                    ' ',
                    '&nbsp;',
                    htmlentities($row[0])
                );
                $html .= ">$listentry</option>" . PHP_EOL;
//            } else {
//                $error .= $this->html()->warning($row[0] . ' has no Value');
            }
        }
        $html .= '</select>' . PHP_EOL;

        return $html;
    }

    /**
     * Eine Selectbox ausgeben
     *  create select list rows[0] = name, rows[1] = value
     *
     * @param string       $name Name des <SELECT Elements>
     * @param array        $rows zweidimensionales Array
     * @param int | string $selected Schlüssel des ausgewähltes Element
     * @param string       $class css-Klasse
     * @param string       $attributes Attribute als String z.B. ' onclick="Klick;" '
     * @param int|string   $id ID des <Select> Elements, Standard = Name
     *
     * @return string html
     */
    public function select_list_utf8($name, $rows, $selected = -1, $class = 'wbs_form', $attributes = '', $id = '')
    {
        $id_name = $id ? $id : $name;
        $html = '<select class="' . $class . '" ' . $this->html()->attribute(
                'name',
                $name
            ) . $this->html()->attribute(
                'id',
                $id_name
            ) . ' ' . $attributes . ' >' . "\n";
        foreach ((array)$rows as $row) {
            $html .= '<option ' . $this->html()->attribute(
                    'value',
                    $row[1]
                );
            if ($selected == $row[1]) {
                $html .= 'selected="selected" ';
            }
            $listentry = str_replace(
                ' ',
                '&nbsp;',
                htmlentities(
                    $row[0],
                    ENT_COMPAT,
                    'UTF-8'
                )
            );
            $html .= ">$listentry</option>" . PHP_EOL;
        }
        $html .= '</select>' . PHP_EOL;

        return $html;
    }
    /**
     * Eine Selectbox ausgeben
     *
     * @param string       $name Name des <SELECT Elements>
     * @param array        $rows Array mit Key=>Value Key will be the value, Value the Name of the Select Option
     * @param int | string $selected Schlüssel des ausgewähltes Element
     * @param string       $class css-Klasse
     * @param string       $attributes Attribute als String z.B. ' onclick="Klick;" '
     * @param int|string   $id ID des <Select> Elements, Standard = Name
     *
     * @return string html
     */
    public function getSelectList($name, $rows, $selected = -1, $class = 'wbs_form', $attributes = '', $id = '')
    {
        $id_name = $id ? $id : $name;
        $html = '<select class="' . $class . '" ' . $this->html()->attribute(
                'name',
                $name
            ) . $this->html()->attribute(
                'id',
                $id_name
            ) . ' ' . $attributes . ' >' . "\n";
        foreach ((array)$rows as $the_value=>$the_name) {
            $html .= '<option ' . $this->html()->attribute(
                    'value',
                    $the_value
                );
            if ($selected == $the_value) {
                $html .= 'selected="selected" ';
            }
            $listentry = str_replace(
                ' ',
                '&nbsp;',
                htmlentities(
                    $the_name,
                    ENT_COMPAT,
                    'UTF-8'
                )
            );
            $html .= ">$the_name</option>" . PHP_EOL;
        }
        $html .= '</select>' . PHP_EOL;

        return $html;
    }

    /**
     * Eine Multi Selectbox ausgeben
     *  create select list rows[0] = name, rows[1] = value
     *  $selected ist ein array ein array seinist
     *
     * @param        $name
     * @param        $rows
     * @param        $arr_selected
     * @param string $class
     *
     * @return string
     */
    public function select_multiple_list($name, $rows, $arr_selected, $class = 'wbs_form')
    {
        $html = '<select multiple class="' . $class . '" ' .
            $this->html()->attribute(
                'name',
                $name . '[]'
            ) . $this->html()->attribute(
                'id',
                $name
            ) . '>' . "\n";
        foreach ((array)$rows as $row) {
            $html .= '<option ' . $this->html()->attribute(
                    'value',
                    $row[1]
                );
            if (in_array(
                $row[1],
                $arr_selected,
                true
            )) {
                $html .= 'selected="selected" ';
            }
            $listentry = str_replace(
                ' ',
                '&nbsp;',
                htmlentities($row[0])
            );
            $html .= ">$listentry</option>" . PHP_EOL;
        }
        $html .= '</select>' . PHP_EOL;

        return $html;
    }

    /**
     * Eine Selectbox ausgeben
     *  create select list rows[0] = name =  value
     *
     * @param string $name Name des <SELECT Elements>
     * @param array  $rows eindimensionales Array
     * @param        int /string $selected Schlüssel des ausgewähltes Element
     * @param string $class css-Klasse
     * @param string $attributes Attribute als String z.B. ' onclick="Klick;" '
     * @param string $id ID des <Select> Elements, Standard = Name
     *
     * @return string
     */
    public function select_simple_attribute($name, $rows, $selected = -1, $class = 'wbs_form', $attributes = '', $id = '')
    {
        $id_name = $id ? $id : $name;
        $html = '<select class="' . $class . '" ' . $this->html()->attribute(
                'name',
                $name
            ) . $this->html()->attribute(
                'id',
                $id_name
            ) . ' ' . $attributes . ' >' . "\n";
        foreach ((array)$rows as $row) {
            if (is_array($row)) {
                $key = $row[0];
                $value = $row[0];
            } else {
                $key = $row;
                $value = $row;
            }
            $html .= '<option ' . $this->html()->attribute(
                    'value',
                    $value
                );
            if ($selected == $value) {
                $html .= 'selected="selected" ';
            }
            $listentry = str_replace(
                ' ',
                '&nbsp;',
                htmlentities(
                    $key,
                    ENT_QUOTES,
                    'UTF-8'
                )
            );
            $html .= ">$listentry</option>" . PHP_EOL;
        }
        $html .= '</select>' . PHP_EOL;

        return $html;
    }

    /**
     * @param      $name
     * @param bool $checked
     * @param      $attributes
     *
     * @return string
     */
    public function getCheckBox($name, $checked = false, $attributes = [])
    {

        $attr = '';
        foreach ((array)$attributes as $the_attribute) {
            if (is_array($the_attribute)) {
                $key = $the_attribute[0];
                $value = $the_attribute[0];
            } else {
                $key = $the_attribute;
                $value = $the_attribute;
            }
            $attr .= $this->html()->attribute(
                $key,
                $value
            );
        }

        $html = "<input type =\"checkbox\" name=\"{$name}\" " .
            $this->getAttributeChecked($checked) .
            $attr .
            ' />';

        return $html;

    }

    /**
     * @param $checked
     *
     * @return string
     */
    public function getAttributeChecked($checked)
    {
        if ($checked) {
            return ' checked="checked" ';
        }

        return '';
    }

    /**
     * Ein europäisches Land wählen
     *
     * @param string $selected *2    Kürzel des aktuellen Landes
     * @param string $css_class CSS-Klasse der Selektbox
     * @param string $lang
     * @param string $name Name der Selectbox ['country']
     * @param string $ID ID der Selectbox   ['country']
     *
     * @return string
     *
     */
    function getCountrySelect($selected,$css_class='',$lang='de',$name='country',$ID='country')
    {

        if(!array_key_exists($lang,$this->country_list)){
            $this->country_list[$lang] = $this->readCountryNames($lang);
        }

        if($css_class) {
            $css_class = ' class="' . $css_class . '" ';
        }
        $html = "<select $css_class name='$name' ID='$ID' size='1'>" . PHP_EOL;
        foreach ($this->country_list[$lang] as $key=>$land) {
            $selected_attribut = ($key==$selected)?' selected="selected "': '';
            $html .= "<option value=\"$key\" $css_class $selected_attribut>" . htmlentities($land,ENT_COMPAT,'utf-8') . '</option>';
        }
        $html .= '</select>';
        return $html;
    }

    /**
     * Name eines Landes anhand des Kürzels zurückgeben
     *
     * @param string $short Kürzel des Landes
     * @param string $lang Kürzel der Ausgabesprache
     *
     * @return string
     */
    function getCountryName($short,$lang='de')
    {
        if(!array_key_exists($lang,$this->country_list)){
            $this->country_list[$lang] = $this->readCountryNames($lang);
        }

        if(array_key_exists($short,$this->country_list[$lang])){
            return $this->country_list[$lang][$short];
        }
        /**
         * @TODO Inform Admin
         */
        return 'Unbekanntes Land => '.$short;
    }
    /**
     * Liste der Länder in Abhängigkeit von der Sprache zurückgeben
     *
     * @TODO There is a function somewhere outside for this in INTL ?

     * @param string $lang Das Sprachkürzel ISO ???
     *
     * @return array Liste der Länder $laender['be']="Belgien
     */
    private function readCountryNames($lang='de')
    {
        //Europa
        switch ($lang){
            case 'en':
                $laender['be']= 'Belgium';
                $laender['de']= 'Germany';
                $laender['dk']= 'Denmark';
                $laender['ee']= 'Estonia';
                $laender['fi']= 'Finland';
                $laender['fr']= 'France';
                $laender['gr']= 'Greece';
                $laender['uk']= 'Great Britain';
                $laender['ie']= 'Ireland';
                $laender['it']= 'Italy';
                $laender['lv']= 'Latvia';
                $laender['fl']= 'Liechtenstein';
                $laender['lt']= 'Lithuania';
                $laender['lu']= 'Luxembourg';
                $laender['nl']= 'Netherlands';
                $laender['no']= 'Norway';
                $laender['at']= 'Austria';
                $laender['pl']= 'Poland';
                $laender['pt']= 'Portugal';
                $laender['se']= 'Sweden';
                $laender['ch']= 'Switzerland';
                $laender['sk']= 'Slowakia';
                $laender['si']= 'Slowenia';
                $laender['es']= 'Spain';
                $laender['cz']= 'Czech Republik';
                $laender['hu']= 'Hungary';

                break;
            case 'de':
            default:

                $laender['be']= 'Belgien';
                $laender['de']= 'Deutschland';
                $laender['dk']= 'Dänemark';
                $laender['ee']= 'Estland';
                $laender['fi']= 'Finnland';
                $laender['fr']= 'Frankreich';
                $laender['gr']= 'Griechenland';
                $laender['uk']= 'Großbritannien';
                $laender['ie']= 'Irland';
                $laender['it']= 'Italien';
                $laender['lv']= 'Lettland';
                $laender['fl']= 'Liechtenstein';
                $laender['lt']= 'Litauen';
                $laender['lu']= 'Luxemburg';
                $laender['nl']= 'Niederlande';
                $laender['no']= 'Norwegen';
                $laender['at']= 'Österreich';
                $laender['pl']= 'Polen';
                $laender['pt']= 'Portugal';
                $laender['se']= 'Schweden';
                $laender['ch']= 'Schweiz';
                $laender['sk']= 'Slowakei';
                $laender['si']= 'Slowenien';
                $laender['es']= 'Spanien';
                $laender['cz']= 'Tschechien';
                $laender['hu']= 'Ungarn';
        }
        return $laender;
    }

    /**
     * Eine Emailadresse auf Gültigkeit prüfen
     * @param string $adr
     * @return bool
     */
    public function checkEmail($adr)
    {
        $regEx = '^([^\s@,:"<>]+)@([^\s@,:"<>]+\.[^\s@,:"<>.\d]{2,}|(\d{1,3}\.){3}\d{1,3})$';
        return preg_match("/$regEx/", $adr, $part) ? $part : false;
    }

}