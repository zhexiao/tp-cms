<?php
class BlockViewModel extends ViewModel{
	public $viewFields = array(
		'Block'	=>	array(
			'blockname'
		),
		
		'Menu'	=>	array(
			'title'	=>	'm_title',
			'_on'	=>	'Menu.id=Block.menuid',
		),
		
		'MenuItem'	=>	array(
			'id'		=>	'm_i_id',
			'title'		=>	'm_i_title',
			'type'		=>	'm_i_type',
			'link'		=>	'm_i_link',
			'image_src'	=>	'm_i_src',
			'type_id'	=>	'm_i_type_id',
			'published'	=>	'm_i_published',
			'_on'		=>	'MenuItem.menuid=Block.menuid',
		),
	);
}
?>