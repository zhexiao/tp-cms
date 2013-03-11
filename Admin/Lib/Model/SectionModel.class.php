<?php
class SectionModel extends RelationModel {
	/**
	 * 表单验证
	 */
	protected  $_validate = array(
	//array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
		array('title','require','单元名必须存在！',1,'regex',3),
	);
	
	/**
	 * 自动填充
	 */
	protected $_auto=array(
	//array(填充字段,填充内容,填充条件,附加规则)
		array('order','0'),
		array('published','1'),
		array('componentid','3')
	);
	
	/**
	 * 关联操作	 Have something Wrong !具体实现用别的方法在del中实现了
	 */
//	public $_link = array(
//		//关联Category表
//		'Category'	=>	array(
//			'mapping_type'	=>	'HAS_MANY',
//			'class_name'	=>	'Category',
//			'foreign_key'	=>	'sectionid',
//			'mapping_name'	=>	'categorys'
//		),
//		
//		//关联Article表
//		'Article'	=>	array(
//			'mapping_type'	=>	'HAS_MANY',
//			'class_name'	=>	'Article',
//			'foreign_key'	=>	'sectionid',	
//			'mapping_name'	=>	'articles'
//		),
//	);

}
?>