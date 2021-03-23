<?php
$EI = new EI_ssw_wp_outlook();
include SSW_WP_EI_PATH."/views/template/header.php";
?>
<h2>Home</h2>
<p>Status da integração:</p>
<?php
if($EI->hasClientId()){ echo '<p>Client ID ok</p>'; }
else{
    echo '<p>Insira o Client ID. <a href="';
    menu_page_url(SSW_WP_EI_PLUGIN_SLUG.'-config');
    echo '">Aqui</a></p>';
}

if($EI->hasClientSecret()){ echo '<p>Client Secret ok</p>'; }
else{
    echo '<p>Insira o Client Secret. <a href="';
    menu_page_url(SSW_WP_EI_PLUGIN_SLUG.'-config');
    echo '">Aqui</a></p>';
}

if($EI->hasCode()){ echo '<p>Autorização ok</p>'; }
else{
    if(!$EI->hasClientId() || !$EI->hasClientSecret()){ echo '<p>Código ausente, configure o Client ID/Secret primeiro</p>'; }
    else{ 
        echo '<p>Integração não completada. Antes de iniciar, defina na aplicação na Azure a url de callback para: <strong>';
        echo SSW_WP_EI_URLCALLBACK.'</strong></p>';
        //url de integração rd
        $url = 'https://login.microsoftonline.com/common/oauth2/v2.0/authorize?client_id=';
        $url .= $EI->getClientId().'&redirect_uri='.SSW_WP_EI_URLCALLBACK;
        $url .= '&response_type=code&scope=offline_access Mail.ReadWrite';
        //
        echo '<a href="';
        echo $url;
        echo '">Iniciar integração</a>';
    }
}
include SSW_WP_EI_PATH."/views/template/footer.php";
?>
