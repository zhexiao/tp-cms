<?php
class CategoryViewModel extends ViewModel{
	public $viewFields	=	array(
		'Category'	=>	array(
			'id'		=>	'c_id',
			'title'		=>	'c_title',
		),
		
		'Section'	=>	array(
			'id'		=>	's_id',
			'title'		=>	's_title',
			'_on'		=>	'Section.id=Category.sectionid'
		),
		
		'Article'	=>	array(
			'id'		=>	'a_id',
			'title'		=>	'a_title',
			'_on'		=>	'Article.catid=Category.id'
		),
	);
}	
?>