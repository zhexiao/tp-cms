<?php
class ArticleViewModel extends ViewModel {
	public $viewFields=array(
		//文章表
		'Article'=>array(
			'id'	=>	'aid',
			'title'	=>	'atitle',
			'description',
			'published'	=>	'apublished',
			'sectionid'	=>	'sid',
			'art_attr'	=>	'aart_attr',
			'catid'	=>	'cid',
			'created',
			'author',
			'hits',
		),
		//单元表
		'Section'=>array(
			'title'	=>	'stitle',
			'_on'	=>	'Section.id=Article.sectionid'
		),
		//类别表
		'Category'=>array(
			'id'	=>	'cid',
			'title'	=>	'ctitle',
			'_on'	=>	'Category.id=Article.catid'
		),
	);
}
?>