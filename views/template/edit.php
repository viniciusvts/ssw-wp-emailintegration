<?php
$EI = new EI_ssw_wp_outlook();
// verifica se há posts para configurar variáveis
if(isset($_POST['client_id'])){ $EI->setClientId($_POST['client_id']); }
if(isset($_POST['client_secret'])){ $EI->setClientSecret($_POST['client_secret']); }
if(isset($_POST['code'])){
    $EI->setCode(''); 
    $EI->setAccessToken(''); 
    $EI->setRefreshToken(''); 
}
// inicia a página
include SSW_WP_EI_PATH."/views/template/header.php";
?>
<h1>Configurações</h1>
<p>Essas informações estão no aplicativo criado no Azure Active Directory admin center</p>
<!-- Client ID -->
<form method="POST" action="<?php $_SERVER['HTTP_REFERER'] ?>">
    <label for="client_id">Cliente ID</label>
    <input type="text" name="client_id" value="<?php echo $EI->getClientId() ?>">
    <input type="submit" value="Atualizar">
</form>
<!-- Client Secret -->
<form method="POST" action="<?php $_SERVER['HTTP_REFERER'] ?>">
    <label for="client_secret">Cliente Secret</label>
    <input type="text" name="client_secret" value="<?php echo $EI->getClientSecret() ?>">
    <input type="submit" value="Atualizar">
</form>
<?php
if($EI->hasCode()){
?>
<!-- Reset Code -->
<form method="POST" action="<?php $_SERVER['HTTP_REFERER'] ?>">
    <label for="code">Apagar Autorização?</label>
    <input type="hidden" name="code" value="true">
    <input type="submit" value="Apagar">
</form>
<?php
}
?>
<?php
include SSW_WP_EI_PATH."/views/template/footer.php";
?>