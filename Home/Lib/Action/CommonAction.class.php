<?php
class CommonAction extends Action{
	/**
    * 自动加载
    */
	function _initialize(){
		header("Content-Type:text/html; charset=utf-8");
	}
	
	/**
    *空方法 
    *如果调用不存在方法或模块自动加载
    */
	function _empty(){
		$this->assign("jumpUrl",__APP__.'/Index/index');
		$this->error("不存在路径");
	}
}
?>