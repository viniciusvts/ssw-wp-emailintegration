<?php
//adiocionar admin menu
add_action ('admin_menu', 'sswEImainAdminPage');
// para pegar a url de uma página
/* menu_page_url( string $menu_slug, bool $echo = true ) */
function sswEImainAdminPage()
{
	add_menu_page(
		SSW_WP_EI_PLUGIN_NAME,
		SSW_WP_EI_PLUGIN_NAME,
		'manage_options',
		SSW_WP_EI_PLUGIN_SLUG,
		'sswEIreturnMainPage',
		'dashicons-admin-settings',
		150
	);
	/*
	add_submenu_page( string $parent_slug, 
					string $page_title, 
					string $menu_title, 
					string $capability, 
					string $menu_slug, 
					callable $function = '', 
					int $position = null )
	*/
	add_submenu_page( 
		SSW_WP_EI_PLUGIN_SLUG, 
		SSW_WP_EI_PLUGIN_NAME.'Configuração', 
		'Configuração', 
		'manage_options',
		SSW_WP_EI_PLUGIN_SLUG.'-config', 
		'sswEIreturnEditPage', 
		1
	);
}
function sswEIreturnMainPage(){
	include SSW_WP_EI_PATH."/views/template/index.php";
}
function sswEIreturnEditPage(){
	include SSW_WP_EI_PATH."/views/template/edit.php";
}