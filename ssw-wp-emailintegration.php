<?php
/**
 * Plugin Name: SSW Integração de email
 * Plugin URI: https://www.santanasolucoesweb.com.br/
 * Description: Provê uma classe para acesso ao email do outlook
 * Version: 1.0
 * Author: Vinicius de Santana
 * Author URI: https://www.santanasolucoesweb.com.br/
 */
if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
// Informações do app
define('SSW_WP_EI_PATH', dirname( __FILE__ ) );
define('SSW_WP_EI_URL', plugins_url( '', __FILE__ ) );
define('SSW_WP_EI_PLUGIN_NAME', 'SSW Email Integra' );
define('SSW_WP_EI_PLUGIN_SLUG', 'ssw-ei-admin' );
define('SSW_WP_EI_URLHOME', '/wp-admin/admin.php?page='.SSW_WP_EI_PLUGIN_SLUG );
//informações do aplicativo criado no rd
define('SSW_WP_EI_CLIENT_ID', 'ssw-wp-ei-client-id');
define('SSW_WP_EI_CLIENTE_SECRET', 'ssw-wp-ei-cliente-secret');
define('SSW_WP_EI_URLCALLBACK', site_url().'/wp-json/ssw-wp-ei-integration/v1/callback/');
define('SSW_WP_EI_CODE', 'ssw-wp-ei-code');
define('SSW_WP_EI_ACCESS_TOKEN', 'ssw-wp-ei-access-token');
define('SSW_WP_EI_REFRESH_TOKEN', 'ssw-wp-ei-refresh-token');

include_once SSW_WP_EI_PATH.'/class/index.php';
include_once SSW_WP_EI_PATH.'/api/index.php';
include_once SSW_WP_EI_PATH.'/functions/index.php';

register_activation_hook(__FILE__, 'sswEIinstall');
register_uninstall_hook(__FILE__, 'sswEIuninstall');
//==================================================================
//funções
/**
 * função de instalação do plugin
 */
function sswEIinstall(){
	add_option(SSW_WP_EI_CLIENT_ID, '');
	add_option(SSW_WP_EI_CLIENTE_SECRET, '');
	add_option(SSW_WP_EI_CODE, '');
	add_option(SSW_WP_EI_ACCESS_TOKEN, '');
	add_option(SSW_WP_EI_REFRESH_TOKEN, '');
}

/**
 * função de desinstalação do plugin
 */
function sswEIuninstall(){
	delete_option(SSW_WP_EI_CLIENT_ID);
	delete_option(SSW_WP_EI_CLIENTE_SECRET);
	delete_option(SSW_WP_EI_CODE);
	delete_option(SSW_WP_EI_ACCESS_TOKEN);
	delete_option(SSW_WP_EI_REFRESH_TOKEN);
}