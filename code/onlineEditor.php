<?php

//在线文件编辑器
/*-------------------------------------
   使用工厂设计模式，MVC实现
*/

class onlineEditor{

    //设置全局变量路径
    public $filePath = null;
    //设置过滤信息
    private $fileFilter = array(
        '.',
        '..',
        '.svn',
        '.git'
    );

    //构造函数必须是私有的在单例设计模式中
    function __construct($filePath){
        $this->filePath = $filePath;
    }

    //当本类销毁的时候进行的操作
    function __destruct(){
        // echo $this->filePath;
    }

    //获取文件的内容
    function getContent($filePath){
        if (!isset($filePath)) {

        } else{
            if(filetype($filePath)=='file'){
                $fileContent = file_get_contents($filePath);
                return $fileContent;
            }
        }
    }

    //放入文件内容
    function putContent($filePath,$fileContent){
        file_put_contents($filePath, $fileContent);
    }

    //判断目录是否存在
    private function judgeExist(){
        //判断目录是否为空或者没有文件
        if(is_dir($this->filePath) && file_exists($this->filePath)){
            return true;
        } else{
            return false;
        }
    }

    //创建文件
    function createFile($filename){
        if(!file_exists($filename)){
            fopen($filename, "w+");
        }

        else{
            echo "<a href = 'index.php' >点此返回</a>";
            die("文件已经存在");
        }

    }
    //删除文件
    function delFile($filename){
        if(file_exists($filename)){
            unlink($filename);
        }
    }

    // 文件排序
    function my_sort($arrays,$sort_key,$sort_order=SORT_ASC,$sort_type=SORT_NUMERIC ){
        if($arrays==[])return [];
        if(!is_array($arrays))return false;

        foreach ($arrays as $array){
            if(is_array($array)){
                $key_arrays[] = $array[$sort_key];
            }else{
                return false;
            }
        }
        array_multisort($key_arrays,$sort_order,$sort_type,$arrays);
        return $arrays;
    }

    /**
     * 格式化文件大小显示
     *
     * @param int $size
     * @return string
     */
    function formatSize($size)
    {
        $prec = 3;
        $size = round(abs($size));
        $units = array(
            0 => " B ",
            1 => " KB",
            2 => " MB",
            3 => " GB",
            4 => " TB"
        );
        if ($size == 0)
        {
            return str_repeat(" ", $prec) . "0$units[0]";
        }
        $unit = min(4, floor(log($size) / log(2) / 10));
        $size = $size * pow(2, -10 * $unit);
        $digi = $prec - 1 - floor(log($size) / log(10));
        $size = round($size * pow(10, $digi)) * pow(10, -$digi);

        return $size . $units[$unit];
    }

    //主函数
    function main(){
        if($this->judgeExist()){
            //获取打开文件夹对象
            $fileOpen = opendir($this->filePath);
            $dirArr = array();
            $fileArr = array();
            $i = 1;
            //遍历文件夹
            while ($file = readdir($fileOpen)) {
                //过滤
                if(in_array($file, $this->fileFilter)){
                    continue;
                }
                $path = rtrim($this->filePath,'/').'/'.$file;
                $type = fileType($path);
                $size = $this->formatSize(fileSize($path));
                if($type=='dir'){ // 文件夹
                    $dirArr[] = array(
                        'fileCode'  => $i,
                        'fileName'  => $file,
                        'fileType'  => $type,
                        'fileSize'  => $size,
                        'filemtime' => filemtime($path)
                    );
                }else{ // 文件
                    $fileArr[] = array(
                        'fileCode'  => $i,
                        'fileName'  => $file,
                        'fileType'  => $type,
                        'fileSize'  => $size,
                        'filemtime' => filemtime($path)
                    );
                }
                $i++;
            }
            $dirArr = $this->my_sort($dirArr,'fileName',SORT_ASC,SORT_STRING);
            $fileArr = $this->my_sort($fileArr,'fileName',SORT_ASC,SORT_STRING);
            closedir($fileOpen); //关闭文件
            return array_merge($dirArr,$fileArr);

        } else{
            die("不存在此文件夹");
        }
    }

}

?>