<?php

namespace wbs\Framework\Html\Form;
/**
 * @since  version 0.8
 * 
	function exampleSuchForm(){
		$f = new \de\blessen\wbsfw\html\form\Tableform();
		$f->start("suche.php",$this->css_class);
		$f->new_line();
		$f->text("suchtext","",40,60);
		$f->end_line();
		$f->new_line();
		$f->button("form_submit","Suche","submit");
		$f->end_line();
		$f->end();
		return $f->getHTML();
	}
	
 * */
class Tableform{
	var $html;
	var $css;
	var $hidden;
	/**
	 * Kopf des Formulars  
	 * @param string $action Aktion
	 * @param string $css_class CSS Klasse für Formular und Tabelle
	 * @return void
	 */
	function start($action= '',$css_class='tableform') {

		if($action){
			$action = $this->attribute('action', $action);
		}
		if($css_class){
			$css_class = $this->attribute('class', $css_class);
		}
		$this->html = '<form '.$css_class.' method="post" '. $action . '>' .PHP_EOL;
		$this->html .= '<table '.$css_class.'>'.PHP_EOL;
		$this->hidden = '';
	}
	/**
	 * Formular und Tabelle wieder schliessen
	 */
	function end() {
		$this->html .= '</table>'.PHP_EOL;
		if($this->hidden){
			$this->html .= '<div>'.$this->hidden.'</div>'.PHP_EOL; 
		}
		$this->html .= '</form>'.PHP_EOL;
	}
	/**
	 * Den HTML Code des Formulrs ausführbar
	 * @return string html
	 */
	function getHTML(){
		return $this->html;
	}
	/**
	 * Code direkt dem Formular hinzufügen
	 * @param $the_code
	 */
	function add($the_code){
		$this->html.=$the_code.PHP_EOL;
	}

    /**
     * @param $bezeichner
     */
    function addFieldset($bezeichner){
		$this->html .=  
		'<fieldset>'.PHP_EOL.
    	'<legend>'.$bezeichner.'</legend>'.PHP_EOL.
		'<table>'.PHP_EOL;
	}
	function endFieldset(){
		$this->html .=  
		'</table>'.PHP_EOL.
    	'</fieldset>'.PHP_EOL;
	}
	/**
	 *  new line (row) in table
	 */
	function new_line() {
		$this->html .= '<tr>';
	}
	/**
	 * / end line (row)
	 */
	function end_line() {
		$this->html .= '</tr>' .PHP_EOL;
	}
	/**
	 * Ein Text in einer Tabellenzelle
	 * @param string $caption
	 * @param bool $necessary if(true)Text wird rot
	 */
	function label($caption, $necessary=false) {
		$this->html .= '<td>';
		if($necessary){
			$this->html .= '<span class="red">'.htmlentities($caption).'</span>';
		}else{
			$this->html .= htmlentities($caption);
		}
		$this->html .= '</td>'.PHP_EOL;
	}

	/**
	 * 	Text in einer Zelle, mit htmlentities
	 * @param string $caption Text
	 * @param int $colspan Gilt für x Zellen
	 */
	function caption($caption, $colspan=1) {
		if($colspan>1){
			$this->html .= '<td '.$this->attribute('colspan', $colspan).'>';
		}else{
			$this->html .= '<td >';
		}
			$this->html .= htmlentities($caption,ENT_COMPAT,'UTF-8').'</td>'.PHP_EOL;
	}
	/**
	 * 	Text in einer Zelle, ohne htmlentities
	 * @param string $txt
	 * @param int $colspan Gilt für x Zellen
	 */
		function asis($txt, $colspan=1) {
		if($colspan>1){
			$this->html .= '<td '.$this->attribute('colspan', $colspan).'>';
		}else{
			$this->html .= '<td>';
		}
			$this->html .= $txt.'</td>'.PHP_EOL;
	}

	// show URL in form cell

    /**
     * @param     $url
     * @param     $txt
     * @param int $colspan
     */
    function url($url, $txt, $colspan=1) {
		if($colspan>1){
			$this->html .= '<td '.$this->attribute('colspan', $colspan).'>';
		}else{
			$this->html .= '<td>';
		}
		$this->html .= "<a href=\"$url\">" . htmlentities($txt) . '</a></td>' .PHP_EOL;
	}

	// save hidden data in form

    /**
     * @param $name
     * @param $value
     */
    function hidden($name, $value) {
		$this->hidden .= '<input type="hidden" '.$this->attribute('name', $name).$this->attribute('value', $value).
            ' >' .PHP_EOL;
	}
	// save hidden data in form

    /**
     * @param $name
     * @param $value
     * @param $attributes
     */
    function wbs_hidden($name, $value,$attributes) {
		$this->html .= '<input type="hidden" '.$this->attribute('name', $name).$this->attribute('value', $value);
		foreach ((array)$attributes as $key=>$val){
			$this->html .= ' '.$this->attribute($key,$val).' ';
		}
		$this->html .= ' >'.PHP_EOL;
	}
	
	// create $n empty cells

    /**
     * @param int $n
     */
    function empty_cell($n=1) {
		$this->html .= str_repeat('<td>&nbsp;</td>', $n) . PHP_EOL;
	}
	// create $n empty cells
    /**
     * @param     $content
     * @param int $colspan
     */
    function cell($content,$colspan=1) {
		$this->html .= "<td colspan={$colspan}>".$content.'</td>'. PHP_EOL;
	}
	/**
	 * Input class="text"
	 * @param string $name Name des Feldes
	 * @param string $value Wert
	 * @param int $size Länge
	 * @param int $maxlength Maximale Länge
	 * @param int $colspan
	 */
	function text($name, $value, $size=40, $maxlength=40, $colspan=1) {
		if($colspan>1) {
            $this->html .= '<td ' . $this->attribute(
                    'colspan',
                    $colspan
                ) . '>';
        }
		else {
            $this->html .= '<td> ';
        }
		$this->html .= '<input class="tableform" '.$this->attribute('name', $name).$this->attribute('size', $size).$this->attribute(
                'maxlength', $maxlength);
		if($value) {
            $this->html .= $this->attribute(
                'value',
                $value
            );
        }
		$this->html .= ' ></td>'."\n";
	}

	// create text input field for form

    /**
     * @param     $name
     * @param     $value
     * @param int $size
     * @param int $maxlength
     * @param int $colspan
     */
    function password($name, $value, $size=40, $maxlength=40, $colspan=1) {
		if($colspan>1) {
            $this->html .= '<td ' . $this->attribute(
                    'colspan',
                    $colspan
                ) . '>';
        }
		else {
            $this->html .= '<td> ';
        }
		$this->html .= '<input class="tableform" '.$this->attribute(
                'type',
                'password'
            ).$this->attribute('name', $name).
		$this->attribute('size', $size).$this->attribute('maxlength', $maxlength);
		if($value) {
            $this->html .= $this->attribute(
                'value',
                $value
            );
        }
		$this->html .=' ></td>'."\n";
	}

	/**
	 * Eine Textarea anlegen 
	 * @param string $name Name des Feldes
	 * @param string $value Inhalt
	 * @param int $cols Zeichen pro Zeile
	 * @param int $rows Zeilen
	 * @param $colspan 
	 * @return void html
	 */
	function textarea($name, $value, $cols=35, $rows=5, $colspan=1) {
		if($colspan>1) {
            $this->html .= '<td ' . $this->attribute(
                    'colspan',
                    $colspan
                ) . '>';
        }
		else {
            $this->html .= '<td> ';
        }
		$this->html .='<textarea class="tableform" '.$this->attribute('name', $name).$this->attribute('rows', $rows).
		$this->attribute('cols', $cols).'>';
		if($value) {
            $this->html .= htmlspecialchars($value);
        }
		$this->html .='</textarea></td>'."\n";
	}
	/**
	 * attribute werden im array übergeben
	 * @param $name
	 * @param $value
	 * @param $attributes array
	 */
	function wbs_textarea($name, $value, $attributes) {
		$this->html .='<td> ';
		$this->html .='<textarea '.$this->attribute('name', $name);
		foreach ((array)$attributes as $key=>$val){
			$this->html .= ''.$this->attribute($key,$val).' ';
		}
		$this->html .='>';
		if($value) {
            $this->html .= htmlspecialchars($value);
        }
		$this->html .='</textarea></td>'."\n";
	}
	/**
	 * Textarea, in der Styles direkt übergeben werden
	 * @param $name
	 * @param $value
	 * @param $style
	 * @param $colspan
	 */ 
	function textarea_style($name, $value, $style, $colspan=1) {
		if($colspan>1) {
            $this->html .= '<td ' . $this->attribute(
                    'colspan',
                    $colspan
                ) . '>';
        }
		else {
            $this->html .= '<td> ';
        }
		$this->html .='<textarea class="tableform" '.$this->attribute('name', $name).$this->attribute('style', $style).'>';
		if($value) {
            $this->html .= htmlspecialchars($value);
        }
		$this->html .='</textarea></td>'."\n";
	}
	/**
	 * 
	 * @param string $name
	 * @param string  $value
	 * @param boolean $b_checked
	 */
	function checkbox($name,$value,$b_checked=false){
		$this->html .='<td>';
		$b_checked ? $str=' checked="checked" ' :$str ='';
		$this->html .='<input type="checkbox" name="'.$name.'[]" value="'.$value.'" '.$str.'>';
		$this->html .='</td>'."\n";
	}


	// create select list rows[0] = name, rows[1] = value

    /**
     * @param        $name
     * @param        $rows
     * @param int    $selected
     * @param string $attributes
     */
    function selectlist($name, $rows, $selected=-1,$attributes='') {
		$this->html .='<td>';
		$this->html .='<select class="tableform" '.$this->attribute('id', $name).$this->attribute('name', $name).$attributes.'>'."\n";
		$this->html .='<option value="-1">(keiner)</option>';
		foreach((array)$rows as $row) {
			$this->html .='<option '.$this->attribute('value', $row[1]);
			if($selected==$row[1]) {
                $this->html .= ' selected="selected" ';
            }
			$listentry = str_replace(
                ' ',
                '&nbsp;', htmlentities($row[0]));
			$this->html .=">$listentry</option>\n";
		}
		$this->html .='</select></td>'."\n";
	}

    	// create select list rows[0] = name =  value

    /**
     * @param     $name
     * @param     $rows
     * @param int $selected
     */
    function selectsimple($name, $rows, $selected=-1) {
		$this->html .='<td>';
		$this->html .='<select class="tableform" '.$this->attribute('name', $name).'>'."\n";
		foreach((array)$rows as $row) {
			$this->html .='<option '.$this->attribute('value', $row);
			if($selected==$row) {
                $this->html .= 'selected="selected" ';
            }
			$listentry = str_replace(
                ' ',
                '&nbsp;', htmlentities($row));
			$this->html .=">$listentry</option>\n";
		}
		$this->html .='</select></td>'."\n";
	}

	// create form button

    /**
     * @param        $name
     * @param        $txt
     * @param string $type
     * @param string $accesskey
     */
    function button($name, $txt, $type= 'submit',$accesskey='') {
	    $ak = ($accesskey==''?'':$this->attribute('accesskey',$accesskey));
		$this->html .='<td><input '.$this->attribute(
                'class',
                'mybutton'
            ).$this->attribute('type', $type).
		$this->attribute('value', $txt).$this->attribute('name', $name).' '.$ak.' ></td>'."\n";
	}
	// create form button

    /**
     * @param $name
     * @param $txt
     * @param $attributes
     */
    function wbs_button($name, $txt,$attributes ) {
		$this->html .='<td><input '.$this->attribute(
                'class',
                'mybutton'
            ).
		    $this->attribute('value', $txt).$this->attribute('name', $name);
		foreach ((array)$attributes as $key=>$val){
			$this->html .= ''.$this->attribute($key,$val).' ';
		}
		$this->html .=' ></td>'."\n";
	}
	
	// build name="value"

    /**
     * @param $name
     * @param $value
     *
     * @return string
     */
    function attribute($name, $value) {
		return $name . '="' . htmlspecialchars($value) . '" ';
	}

	// show red error message

    /**
     * @param $txt
     */
    function show_error_msg($txt) {
		$this->html .='<p><span class="red">'.htmlentities($txt).'</span></p>'."\n";
	}

}// end class
?>
