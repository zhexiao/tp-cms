<?php
class CategoryViewModel extends ViewModel{
	public $viewFields = array(
		//类别表
		'Category' 	=>	array(
			'id' 			=> 	'cid',
			'title'			=> 	'ctitle',
			'description' 	=> 	'cdescription',
			'published'		=> 	'cpublished',
			'order'			=>	'corder'
		),
		
		//单元表
		'Section'  =>	array(
			'id'			=>	'sid',
			'title'			=>	'stitle',
			'_on'			=>	'Section.id=Category.sectionid'
		),			
	);
}

?>