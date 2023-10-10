<?php
/**
 * Example Call and Functionality Test
 */
require_once ('../vendor/autoload.php');

$wbs = new \wbs\Framework\Wbs('../');
echo $wbs->getDataPath();