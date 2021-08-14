<?php
/**
 * Created by PhpStorm.
 * User: Wanzhou Chen
 * Date: 2021/1/22
 * Time: 17:31
 */

require_once __DIR__ . '/../vendor/autoload.php';

$viewFile = new \melodyne\wfm\ViewFile([
    'sys_name'=>'在线log日志系统',
    'dir_path'=>'../logs',
    'file_action'=>['create','update','delete'] // 创建，修改，删除
]);
$viewFile->showList();
