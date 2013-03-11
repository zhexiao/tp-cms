<?php
class BlockViewModel extends ViewModel{
	public $viewFields = array(
		'Block'		=>	array(
			'id'	=>	'b_id',
		),
		
		'Menu'		=>	array(
			'title'	=>	'm_title',
			'_on'	=>	'Menu.id=Block.menuid'
		),
		
		'MenuItem'	=>	array(
			'id'	=>	'm_i_id',
			'title'	=>	'm_i_title',
			'link'	=>	'm_i_link',
			'type'	=>	'm_i_type',
			'type_id'	=>	'm_i_typeid',
			'_on'	=>	'MenuItem.menuid=Block.menuid'
		),
	);
}

?>