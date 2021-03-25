# Classe Rdi_wp

## Instancie
A instância já carrega os códigos de autenticação


    $EI = new EI_ssw_wp_outlook();

## Você pode adicionar os códigos pela instância da classe
O client_secret

    $EI->setClientSecret('value');

O client_id

    $EI->setClientId('value');

O code

    $EI->setCode('value');

## Pegar as propriedades
O client_secret

    $EI->getClientSecret();

O client_id

    $EI->getClientId();

O code

    $EI->getCode();

## Verificar se as propriedades existem
O client_secret

    $EI->hasClientSecret();

O client_id

    $EI->hasClientId();

O code

    $EI->hasCode();

O access_token

    $EI->hasAcessToken();

O refresh_token

    $EI->hasRefreshToken();

## Recuperar dados
Recupera os email da caixa de entrada, pode mandar os parametros de <code>$select</code> como array da doc da microsoft: 

    $EI->getEmails(['subject', 'from', 'receivedDateTime']);

Recupera email de todas as pastas utilizando os filtros da doc da microsoft como um array:

    $args = array(
        '$filter' => urlencode("receivedDateTime ge ".$stringDate." and (from/emailAddress/address) eq 'santana@dnadevendas.com.br'"),
        '$orderby' => 'receivedDateTime',
    );
    $EI->getMessages($args);

Enjoy!
