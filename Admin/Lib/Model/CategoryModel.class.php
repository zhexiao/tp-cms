<?php
class CategoryModel extends RelationModel {
	//自动验证
	protected  $_validate = array(
		array('title','require','分类标题必须填写！',1),
	);
	
	//自动完成
	protected $_auto = array(
		array('order','0'),
		array('published','1'),
		array('componentid','2')
	);
	
	/**
	 * 关联操作，以便管理删除一种类别时，连带删除这种类别的所有文章。
	 * Have something Wrong !具体实现用别的方法在del中实现了
	 */
//	public $_link = array(
//		//关联上Article表
//		'article' => array(
//			'mapping_type'	=>	'HAS_MANY',
//			'class_name'	=>	'Article',
//			'foreign_key'	=>	'catid',
//			'mapping_name'	=>	'articles'
//		),
//	);
}
?>