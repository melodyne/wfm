<?php

namespace melodyne\wfm;

class ViewFile
{
    protected $dirPath = '';

    function __construct($dirPath)
    {
        $this->dirPath = $dirPath;
    }

    function showList()
    {

        $dirPath = $this->dirPath;
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
                case 'del':
                    $onlineEditor->delFile(subFilePath($dirPath, $_GET['filename']));
                    $action = 'del';
                    echo subFilePath($dirPath, $_GET['filename']);
                    echo "<script>self.location=document.referrer;</script>";
                    break;
            }
        } else {
            $action = null;
        }

        if (array_key_exists('action', $_POST)) {
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