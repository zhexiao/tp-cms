<?php
class MenuViewModel extends ViewModel{
	public $viewFields	=	array(
		
		"Menu"		=>	array(
			'id'		=>	'm_id',
			'title'		=>	'm_title',
		),
	
		//子菜单表
		"MenuItem"	=>	array(
			'title'		=>	'item_title',
			'link'		=>	'item_link',
			'type'		=>	'item_type',
			'_on'		=>	'MenuItem.menuid=Menu.id',
		),
	);
}
?>