<?php
if( !class_exists('EI_ssw_wp_outlook') ){
    class EI_ssw_wp_outlook {
        // propriedades
        private $client_id = '';
        private $client_secret = '';
        private $code = '';
        private $access_token = '';
        private $refresh_token = '';
        
        public function __construct(){
            $this->client_id = get_option(SSW_WP_EI_CLIENT_ID);
	        $this->client_secret = get_option(SSW_WP_EI_CLIENTE_SECRET);
	        $this->code = get_option(SSW_WP_EI_CODE);
	        $this->access_token = get_option(SSW_WP_EI_ACCESS_TOKEN);
	        $this->refresh_token = get_option(SSW_WP_EI_REFRESH_TOKEN);
        }
        
        // set properties
        public function setClientId($value){
            if (update_option(SSW_WP_EI_CLIENT_ID, $value)){
                $this->client_id = $value;
                return true;
            }
            return false;
        }
        public function setClientSecret($value){
            if (update_option(SSW_WP_EI_CLIENTE_SECRET, $value)){
                $this->client_secret = $value;
                return true;
            }
            return false;
        }
        public function setCode($value){
            if (update_option(SSW_WP_EI_CODE, $value)){
                $this->code = $value;
                return true;
            }
            return false;
        }
        private function setAccessToken($value){
            if (update_option(SSW_WP_EI_ACCESS_TOKEN, $value)){
                $this->access_token = $value;
                return true;
            }
            return false;
        }
        private function setRefreshToken($value){
            if (update_option(SSW_WP_EI_REFRESH_TOKEN, $value)){
                $this->refresh_token = $value;
                return true;
            }
            return false;
        }
        public function clearAll(){
            $this->setClientId('');
            $this->setClientSecret('');
            $this->setCode('');
            $this->setAccessToken('');
            $this->setRefreshToken('');
        }

        // get properties
        public function getClientId(){
            return $this->client_id;
        }
        public function getClientSecret(){
            return $this->client_secret;
        }
        public function getCode(){
            return $this->code;
        }

        // has properties
        public function hasClientId(){
            if($this->client_id) return true;
            return false;
        }
        public function hasClientSecret(){
            if($this->client_secret) return true;
            return false;
        }
        public function hasCode(){
            if($this->code) return true;
            return false;
        }
        public function hasAcessToken(){
            if($this->access_token) return true;
            return false;
        }
        public function hasRefreshToken(){
            if($this->refresh_token) return true;
            return false;
        }

        // funções de autenticação no outlook
        public function getAccessAndRefreshToken(){
            $url = 'https://login.microsoftonline.com/common/oauth2/v2.0/token';
            $payload = 'grant_type=authorization_code'.
                '&code='. $this->code.
                '&redirect_uri='. 'https://bimworks.localhost/wp-json/ssw-wp-ei-integration/v1/callback/'.
                '&client_id='. $this->client_id.
                '&client_secret='. $this->client_secret.
                '&scope='. 'offline_access Mail.ReadWrite';
            $resp = $this->post($url, $payload);
            if(!isset($resp->access_token) || !isset($resp->refresh_token)){ return false; }
            if(isset($resp->access_token)){ $this->setAccessToken($resp->access_token); }
            if(isset($resp->refresh_token)){ $this->setRefreshToken($resp->refresh_token); }
            return true;
        }
        public function refreshToken(){
            //se não tem refresh token, então adquiri um
            if(!$this->hasRefreshToken()){
                return $this->getAccessAndRefreshToken();
            } else{
                // se tem refresh token, atualiza o token
                $url = 'https://login.microsoftonline.com/common/oauth2/v2.0/token';
                $payload = 'grant_type=refresh_token'.
                '&refresh_token='. $this->refresh_token.
                '&client_id='. $this->client_id.
                '&client_secret='. $this->client_secret.
                '&scope='. 'offline_access Mail.ReadWrite';
                $resp = $this->post($url, $payload);
                if(!$resp->access_token || !$resp->refresh_token){ return false; }
                if($resp->access_token){ $this->setAccessToken($resp->access_token); }
                if($resp->refresh_token){ $this->setRefreshToken($resp->refresh_token); }
                return true;
            }
            return false;
        }
        
        /**
         * Recupera emails na caixa de entrada
         */
        public function getEmails($select = ['subject', 'from', 'receivedDateTime']){
            $url = 'https://graph.microsoft.com/v1.0/me/mailfolders/inbox/messages?$select=';
            $url .= join(",",$select);
            $url .= '&$top=25&$orderby=receivedDateTime%20DESC';
            //headers
            $headers = array(
                'Authorization' => 'Bearer '. $this->access_token
            );
            $resp = $this->get($url, $headers);
            if(isset($resp->value)){ return $resp; }
            else{ 
                // se não retornar resposta atualizo o token no servidor
                // atualizo o header e tento novamente
                if($this->refreshToken()){
                    //headers
                    $headers = array(
                        'Authorization' => 'Bearer '. $this->access_token
                    );
                    //envia
                    $resp = $this->get($url, $headers);
                    if(isset($resp->value)){ return $resp; }
                }
            }
            return false;
        }

        /**
         * Recupera emails usando filtros
         * @param select
         * @param filter
         * @param search
         * @param top
         * @param top
         */
        public function getMessages(
        $select = null,
        $filter = null,
        $search = null,
        $top = null,
        $orderby = null){
            $hasParam = false;
            $url = 'https://graph.microsoft.com/v1.0/me/messages?';
            if($select){
                $url .= '$select='.join(",",$select);
                $hasParam = true;
            }
            if($filter){
                if($hasParam){
                    $url .= "&";
                }
                $url .= '$filter='.$filter;
                $hasParam = true;
            }
            if($search){
                if($hasParam){
                    $url .= "&";
                }
                $url .= '$search="'.$search.'"';
                $hasParam = true;
            }
            if($top){
                if($hasParam){
                    $url .= "&";
                }
                $url .= '$top="'.$top.'"';
                $hasParam = true;
            }
            if($orderby){
                if($hasParam){
                    $url .= "&";
                }
                $url .= '$orderby="'.$orderby.'"';
                $hasParam = true;
            }
            //headers
            $headers = array(
                'Authorization' => 'Bearer '. $this->access_token
            );
            $resp = $this->get($url, $headers);
            if(isset($resp->value)){ return $resp; }
            else{ 
                // se não retornar resposta atualizo o token no servidor
                // atualizo o header e tento novamente
                if($this->refreshToken()){
                    //headers
                    $headers = array(
                        'Authorization' => 'Bearer '. $this->access_token
                    );
                    //envia
                    $resp = $this->get($url, $headers);
                    if(isset($resp->value)){ return $resp; }
                }
            }
            return false;
        }

        //funções auxiliares
        private function post($url, $payload, $headers = []){
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            // Set the content type to application/json
            $headersArray = array('Content-Type:application/x-www-form-urlencoded');
            foreach ($headers as $key => $value) {
                $headersArray[] = $key.':'.$value;
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headersArray);
            
            // Return response instead of outputting
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // Execute the POST request
            $result = curl_exec($ch);
            // Close cURL resource
            curl_close($ch);
            //return
            return json_decode($result);
        }
        /**
         * get
         */
        private function get($url, $headers = []){
            $ch = curl_init($url);
            // Set the content type to application/json
            $headersArray = array('Content-Type:application/json');
            foreach ($headers as $key => $value) {
                $headersArray[] = $key.':'.$value;
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headersArray);
            
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            // Close cURL resource
            curl_close($ch);
            //return
            return json_decode($result);
        }
        // /**
        //  * patch
        //  */
        // private function patch($url, $payload, $headers = []){
        //     $ch = curl_init($url);
        //     // Attach encoded JSON string to the POST fields
        //     $payloadJsonEncoded = json_encode($payload);
        //     curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
        //     curl_setopt($ch, CURLOPT_POSTFIELDS, $payloadJsonEncoded);
        //     // Set the content type to application/json
        //     $headersArray = array('Content-Type:application/json');
        //     foreach ($headers as $key => $value) {
        //         $headersArray[] = $key.':'.$value;
        //     }
        //     curl_setopt($ch, CURLOPT_HTTPHEADER, $headersArray);
            
        //     // Return response instead of outputting
        //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //     // Execute the POST request
        //     $result = curl_exec($ch);
        //     // Close cURL resource
        //     curl_close($ch);
        //     //return
        //     return json_decode($result);
        // }
    }
}