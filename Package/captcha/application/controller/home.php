<?php
/*
  home.php
*/
class home extends mt_controller{
  public function index(){
	$data['test_var']='Matrix';
	$data['test_arr']=array('apple','google','microsoft');
	$this->load->view('test',$data);
  }
  public function show(){
    echo 'This is the show function';
  }
}
