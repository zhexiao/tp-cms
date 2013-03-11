<?php
class MenuModel extends Model{
	/**
	 * 表单验证
	 */
	protected  $_validate = array(
	//array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
		array('title','require','单元名必须存在！',1,'regex',3),
		array('title','','此菜单已存在',0,'unique',1),
		array('menutype','require','请输入菜单类型',1,'regex',3),
	);
	
	/**
	 * 自动填充
	 */
	protected $_auto=array(
	//array(填充字段,填充内容,填充条件,附加规则)
		
	);
}

?>