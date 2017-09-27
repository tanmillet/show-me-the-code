<?php
ini_set("display_errors","On");
class Public_Upload{
	private $type;
	private $savePath;
	private $maxSizeInByte;
	private $maxSizeInHuman;
	private $file;
	private $allowExt;
	private $ext;
	public $error;
	
	/**
	 * @param string $type 类型
	 * @param string $maxSize 大小，可以带g、m、k单位
	 * @param array $allowExt 允许上传文件后缀
	 */
	public function __construct($type="image", $maxSize="1m", $allowExt=array()){
		$this->type = $type;
		$this->maxSizeInHuman = $maxSize;
		$unit = strtolower(substr($maxSize, -1, 1));
		switch($unit){
			case 'g':
				$maxSize = $maxSize * 1024 * 1024 * 1024;
				break;
			case 'm':
				$maxSize = $maxSize * 1024 * 1024;
				break;
			case 'k':
				$maxSize = $maxSize * 1024;
				break;
			default:
				$maxSize = intval($maxSize);
		}
		$this->maxSizeInByte = $maxSize;
		$this->allowExt = $allowExt;
	}
	
	/**
	 * @param string $path 目录
	 * @param number $mode 权限
	 */
	private function createPath($path, $mode=0755){
		if(is_dir($path)){
			return true;
		}
		$pre = substr($path, 0, 1);
		$reached = "";
		if($pre == "/"){
			$reached = $pre;
		}
		$arr = explode("/", $path);
		$arr = array_filter($arr);
		if(is_array($arr)){
			foreach($arr as $v){
				$reached .= $v . DIRECTORY_SEPARATOR;
				if(!is_dir($reached)){
					if(!mkdir($reached, $mode)){
						$this->setError("目录：".$reached." 创建失败！");
						return false;
					}
				}
			}
		}
		return true;
	}
	
	/**
	 * @param file $file 文件
	 * @param string $savePath 保存路径
	 * @param bool $rename 是否重命名
	 */
	public function save($file, $savePath, $rename=true){
		$this->file = $file;
		$this->savePath = $savePath;
		if(!$this->checkFile()){
			return false;
		}
		if($rename){
			$newName = md5(microtime(1)) . "." . $this->ext;
		}else{
			if(file_exists($this->savePath . DIRECTORY_SEPARATOR . $this->file['name'])){
				$pathinfo = pathinfo($this->file['name']);
				for($i=1;$i<=100;$i++){
					$n = str_replace(".".$pathinfo['extension'], "", $this->file['name'])."_".$i.".".$pathinfo['extension'];
					if(!file_exists($this->savePath . DIRECTORY_SEPARATOR . $n)){
						$newName = $n;
						break;
					}
				}
			}else{
				$newName = $this->file['name'];
			}
			
		}
		if(empty($newName)){
			$this->setError("文件重名太多！");
			return false;
		}
		if(!is_writable($this->savePath)){
			$this->setError($this->savePath."目录不可写！");
			return false;
		}
		$destination = $this->savePath . DIRECTORY_SEPARATOR . $newName;
		if(!$this->createPath($this->savePath)){
			return false;
		}else{
			if(!move_uploaded_file($file['tmp_name'], $destination)){
				$this->setError("保存文件错误！");
				return false;
			}
		}
		return $destination;
	}
	
	private function checkFile(){
		if(!is_uploaded_file($this->file['tmp_name'])){
			$this->setError("非可上传文件！");
			return false;
		}
		if($this->file['size'] > $this->maxSizeInByte){
			$this->setError("文件大小不能超出：".$this->maxSizeInHuman);
			return false;
		}
		$pathinfo = pathinfo($this->file['name']);
		$ext = strtolower($pathinfo['extension']);
		$this->ext = $ext;
		if(!empty($this->allowExt) && !in_array($ext, $this->allowExt)){
			$this->setError("该文件类型不允许上传！");
			return false;
		}
		if($this->type == "image"){
			if(!getimagesize($this->file['tmp_name'])){
				$this->setError("文件不是常规图片！");
				return false;
			}
		}
		return true;
	}
	
	private function setError($msg){
		$this->error = $msg;
	}
}