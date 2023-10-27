<?php

namespace wbs\Framework\Html\Bootstrap;
/**
 * Class Bootstrap
 *
 * Hilfsfunktion für Bootstrap-formatierte Ausgaben
 */
class Bootstrap
{

    /**
     * Farben aus der global.css
     */
    const BG_GRAY = 'bg-gray';
    const BG_ORANGE = 'bg-orange';
    const BG_RED = 'bg-red';
    const BG_WHITE = 'bg-white';

    /**
     * @param string $msg
     * @param string $bg_color
     * @return string
     */
    public function alert_info($msg, $bg_color = '')
    {
        return '<div class="alert alert-info ' . $bg_color . '">' . $msg . '</div>' . PHP_EOL;
    }

    /**
     * @param string $msg
     * @param string $bg_color
     * @return string
     */
    public function alert_warning($msg, $bg_color = '')
    {
        return '<div class="alert alert-warning ' . $bg_color . '">' . $msg . '</div>' . PHP_EOL;
    }

    /**
     * @param string $msg
     * @param string $bg_color
     * @return string
     */
    public function alert_danger($msg, $bg_color = '')
    {
        return '<div class="alert alert-danger ' . $bg_color . '">' . $msg . '</div>' . PHP_EOL;
    }

    /**
     * Pass the Content of (1,2,3,4,6,12) Columns and create a Bootstrap Row
     *
     * @param string $msg
     * @param string $bg_color
     * @return string
     */
    public function create_row()
    {
        $numargs = func_num_args();
        switch ($numargs) {
            case 0:
                return '';
                break;
            case 5:
            case 7:
            case 8:
            case 9:
            case 10:
            case 11:
                throw new \Exception($numargs . ' Colums are not allowed');
                break;
            default:
                if ($numargs > 12) {
                    throw new \Exception($numargs . ' Colums are not allowed');
                }
        }
        $num_cols = 12 / $numargs;
//        $html = "Anzahl der Argumente: $numargs \n";
        $arg_list = func_get_args();
        $html = '<div class="row">' . PHP_EOL;
        for ($i = 0; $i < $numargs; $i++) {
            $html .= '<div class="col col-lg-' . $num_cols . '">' . $arg_list[$i] . '</div>' . PHP_EOL;
        }
        $html .= '</div>' . PHP_EOL;
        return $html;
    }
}