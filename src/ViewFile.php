<?php

namespace melodyne\wfm;

class ViewFile
{
    private $systemName = '在线log日志系统';
    private $fileAction = array('open','look');

    protected $dirPath = '';


    function __construct($config)
    {
        if(is_string($config)){
            $this->dirPath = $config;
        }else{
            if(empty($config['dir_path'])){
                die('请配置要读取的文件夹路径');
            }
            $this->dirPath = $config['dir_path'];
            if(isset($config['sys_name']) && $config['sys_name']){
                $this->systemName = $config['sys_name'];
            }
            if(isset($config['file_action']) && $config['file_action']){
                $this->fileAction = array_merge($this->fileAction,$config['file_action']);
            }
        }
    }

    function showList()
    {
        $sysName = $this->systemName;
        $dirPath = $this->dirPath;
        $fileAction = $this->fileAction;
        if (isset($_GET['dir'])) {
            $dirPath = $_GET['dir'];
        }
        $action = null;

        //获得onlineEditor对象
        $onlineEditor = new OnlineEditor($dirPath);
        $fileMes = $onlineEditor->main();

        //处理文件路径
        function subFilePath($dirPath, $filename)
        {
            $dirPath = rtrim($dirPath,'/');
            $dirPath = rtrim($dirPath,'\\');
            return $dirPath .'/'. $filename;
        }

        //初始化
        if (array_key_exists('action', $_GET)) {
            if(!in_array($_GET['action'],$fileAction)){
                echo "<a href=\"javascript:\" onclick=\"self.location=document.referrer;\" >点此返回</a>";
                die('无操作权限');
            }
            switch ($_GET['action']) {
                case 'open':
                    $action = 'open';
                    break;
                case 'look':
                    $action = 'look';
                    break;
                case 'update':
                    $action = 'update';
                    break;
                case 'delete':
                    $onlineEditor->delFile(subFilePath($dirPath, $_GET['filename']));
                    $action = 'delete';
                    echo subFilePath($dirPath, $_GET['filename']);
                    echo "<script>self.location=document.referrer;</script>";
                    break;
            }
        } else {
            $action = null;
        }

        if (array_key_exists('action', $_POST)) {
            if(!in_array($_POST['action'],$fileAction)){
                echo "<a href=\"javascript:\" onclick=\"self.location=document.referrer;\" >点此返回</a>";
                die('无操作权限');
            }
            switch ($_POST['action']) {
                case 'create':
                    $onlineEditor->createFile(subFilePath($dirPath, $_POST['filename']));
                    echo "<script>location.href = self.location=document.referrer;</script>";
                    break;
            }
        }

        //获取文件内容
        if (array_key_exists('filename', $_GET) && ($_GET['action'] == 'update' || $_GET['action'] == 'look')) {
            $root = subFilePath(rtrim($dirPath, '/') . '/', $_GET['filename']);
            $fileContent = $onlineEditor->getContent($root);
        } else {
            $fileContent = "文件路径错误，或操作类型不存在";
        }

        if (array_key_exists('filecontent', $_POST)) {
            $onlineEditor->putContent(subFilePath($dirPath, $_POST['filename']), $_POST['filecontent']);
            echo "<script>self.location=document.referrer+'&action=look';</script>";
        }

        // url路径处理
        $requestUri = $_SERVER["REQUEST_URI"];
        if(strstr($requestUri,'?')){
            $requestUri .= '&';
        }else{
            $requestUri .= '?';
        }

        //引入界面
        require __DIR__."/viewEditor.html";

    }
}

?>