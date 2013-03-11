<?php
class CommentViewModel extends ViewModel{
	public $viewFields = array(
		'Comment'	=>	array(
			'id'		=>	'c_id',
			'name'		=>	'c_name',
			'comment'	=>	'c_comment',
			'created'	=>	'c_created'
		),
		
		'Article'	=>	array(
			'id'		=>	'a_id',
			'title'		=>	'a_title',
			'_on'		=>	'Article.id=Comment.art_id',
		),	
	);
}	
?>