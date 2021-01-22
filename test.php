<?php
/**
 * Created by PhpStorm.
 * User: Wanzhou Chen
 * Date: 2021/1/22
 * Time: 17:31
 */

require_once __DIR__ . '/vendor/autoload.php';

$viewFile = new \melodyne\wfm\ViewFile('logs');
$viewFile->showList();
