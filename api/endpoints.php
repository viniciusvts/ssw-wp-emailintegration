<?php
function ssw_wp_callbackcodeei () {
  $EI = new EI_ssw_wp_outlook();
  $url = SSW_WP_EI_URLHOME;
  if($EI->setCode($_GET['code'])){
    wp_redirect($url);
    exit;
  }else{
    wp_redirect($url);
    exit;
  }
}
/**
 * Função registra os endpoints
 * @author Vinicius de Santana
 */
function ssw_wp_ei_registerapi(){
    $sswuriapi = 'ssw-wp-ei-integration/v1';
    //contato footer
    register_rest_route($sswuriapi,
      '/callback',
      array(
        'methods' => 'GET',
        'callback' => 'ssw_wp_callbackcodeei',
        'description' => 'recebe o code da integração e salva no banco',
      )
    );
}
  
add_action('rest_api_init', 'ssw_wp_ei_registerapi');
  