<?php
class Public_Page{
	public $pageSize;	//每页条数
	public $total;	//总条数
	public $totalPages;	//总页数
	public $varPage;	//分页变量名
	public $url;	//当前地址
	public $curPage;	//当前页码
	public $urlMod;	//url模式
	public $offset;	//偏移量
	
	/**
	 * @param number $total 总数
	 * @param number $pageSize 每页数量
	 * @param string $varPage 页码变量
	 * @param number $urlMod 地址模式
	 */
	public function __construct($total, $pageSize=10, $varPage="page", $urlMod=0){
		$this->total = $total;
		$this->pageSize = $pageSize;
		$this->totalPages = ceil($this->total / $this->pageSize);
		$this->varPage = $varPage;
		$this->urlMod = $urlMod;
		$this->curPage = $this->getCurPage();
		$this->offset = ($this->curPage-1) * $this->pageSize;
	}
	
	/**
	 * @param number $num 显示页数$num×2+1
	 * @param number $numOnly 是否只显示数字
	 * @param array $text 文字信息
	 */
	public function show($num=5, $numOnly=0, $text=array("first"=>"First","prev"=>"Prev","next"=>"Next","last"=>"Last")){
		$pageNav = array();
		if($this->totalPages <= $num*2 + 1){
			$begin = 1;
			$end = $this->totalPages;
		}else{
			if($this->curPage <= $num){
				$begin = 1;
				$end = $num*2 + 1;
			}elseif($this->curPage >= $this->totalPages - $num){
				$begin = $this->totalPages - ($num*2 + 1);
				$end = $this->totalPages;
			}else{
				$begin = $this->curPage - $num;
				$end = $this->curPage + $num;
			}
		}
		for($i=$begin;$i<=$end;$i++){
			if($i!=$this->curPage){
				$url = $this->getUrl($i);
				$pageNav[] = '<span class="page_num"><a href="'.$url.'" class="btn">'.$i.'</a></span>';
			}else{
				$pageNav[] = '<span class="cur_page btn btn-danger"><b>'.$i.'</b></span>';
			}
		}
		if(!$numOnly){
			if($begin != 1){
				array_unshift($pageNav, '<span class="page_num btn">...</span>');
			}
			if($this->curPage != 1){
				$prevNum = $this->curPage - 1;
				$url = $this->getUrl($prevNum);
				array_unshift($pageNav, '<span class="page_num"><a href="'.$url.'" class="btn">'.$text['prev'].'</a></span>');
				$url = $this->getUrl(1);
				array_unshift($pageNav, '<span class="page_num"><a href="'.$url.'" class="btn">'.$text['first'].'</a></span>');
			}
			if($end != $this->totalPages){
				array_push($pageNav, '<span class="page_num btn">...</span>');
			}
			if($this->curPage != $this->totalPages){
				$nextNum = $this->curPage + 1;
				$url = $this->getUrl($nextNum);
				array_push($pageNav, '<span class="page_num"><a href="'.$url.'" class="btn">'.$text['next'].'</a></span>');
				$url = $this->getUrl($this->totalPages);
				array_push($pageNav, '<span class="page_num"><a href="'.$url.'" class="btn">'.$text['last'].'</a></span>');
			}
			array_push($pageNav, '<span class="page_num btn">共'.$this->total.'条记录，'.$this->curPage.' / '.$this->totalPages.'页</span>');
		}
		return "<div style='margin-top:10px'>".implode($pageNav)."</div>";
	}
	
	/**
	 *@param number $pageNum 页数  
	 */
	private function getUrl($pageNum){
	    if($this->urlMod){
	    	$url = $_SERVER["REQUEST_URI"];
	    	$url = rtrim($url, "/");
		    if(preg_match("/\/".$this->varPage."\/\d+/i", $url)){
		    	$url = preg_replace("/\/".$this->varPage."\/\d+/i", "/".$this->varPage."/".$pageNum, $url);
		    }else{
		    	$url .= "/" . $this->varPage . "/".$pageNum;
		    }
	    }else{
	    	$_GET[$this->varPage] = $pageNum;
	    	$query = http_build_query($_GET);
	    	$url = $_SERVER['SCRIPT_NAME'] . "?" . $query;
	    }
	    return $url;
	}
	
	private function getCurPage(){
		if($this->urlMod){
			preg_match("/\/".$this->varPage."\/(\d+)/i", $_SERVER['REQUEST_URI'], $match);
			$curPage = intval($match[1]);
		}else{
			$curPage = intval($_GET[$this->varPage]);
		}
		$curPage = min($this->totalPages, $curPage);
		$curPage = max($curPage, 1);
		return $curPage;
	} 
	
}