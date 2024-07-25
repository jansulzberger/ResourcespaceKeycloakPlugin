<?php

/**
 * Hook into login form
 */
function HookKeycloakAllLoginformlink() {
    global $baseurl;
    $config = get_plugin_config('keycloak');
    if($config === null){
        return;
    }
    $auth_url = $config['keycloak_server_url'] . '/realms/' . $config['keycloak_realm'] . '/' . $config['keycloak_authorization_endpoint'];
    $auth_url .= '?client_id=' . $config['keycloak_client_id'];
    $auth_url .= '&response_type=code';
    $auth_url .= '&scope=' . $config['keycloak_scopes'];
    $auth_url .= '&redirect_uri=' . urlencode($baseurl . '/plugins/keycloak/pages/callback.php');
    print '<a href="' . $auth_url . '">Login with Keycloak</a>';
}


/**
 * Hook into user login
 */
function HookKeycloakAllProvideusercredentials() {
    global $baseurl;
    $config = get_plugin_config('keycloak');
    if (isset($_GET['code'])) {
        $code = $_GET['code'];
        $token_url = $config['keycloak_server_url'] . '/realms/' . $config['keycloak_realm'] . '/' . $config['keycloak_token_endpoint'];
        $post_fields = [
            'grant_type' => 'authorization_code',
            'client_id' => $config['keycloak_client_id'],
            'client_secret' => $config['keycloak_client_secret'],
            'code' => $code,
            'redirect_uri' => $config['keycloak_redirect_uri']
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $token_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_fields));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        $response_data = json_decode($response, true);
        if (isset($response_data['id_token'])) {
            $id_token = $response_data['id_token'];
            $jwt_parts = explode('.', $id_token);
            $jwt_payload = json_decode(base64_decode($jwt_parts[1]), true);
            //Default Usergroup
            $usergroup = 2;
            foreach($jwt_payload['realm_access']['roles'] as $role){
                switch($role){
                    case 'Administrator':
                        $usergroup = 3;
                        break;
                    case 'Editor':
                        $usergroup = 4;
                        break;
                    case 'Viewer':
                        $usergroup = 2;
                        break;
                }
            }
            $email = $jwt_payload['email'];
            $fullname = $jwt_payload['name'];
            if(user_email_exists($email)){
                $userref = get_user_by_email($email);
                $user = get_user(intval($userref[0]['ref']));
                ps_query("UPDATE user SET usergroup=? WHERE ref=?", ['i', $usergroup, 'i', $user['ref']]);

            }else{
                $user = new_user($email,$usergroup);
                ps_query("UPDATE user SET fullname=?, email=? WHERE ref=?", ['s', $fullname, 's', $email, 'i', $user]);
                $user = get_user($user);
            }
            $session_hash = generate_session_hash($user['password']);
            $ip=get_ip();
            $language = getval("language", "");
            ps_query("UPDATE user SET session=?, last_active = NOW(), login_tries = 0, lang = ? WHERE ref = ?", array("s",$session_hash,"s",$language,"i",$user['ref']));
            $get_user_local_timezone = getval('user_local_timezone', null);
            set_config_option($user, 'user_local_timezone', $get_user_local_timezone);
            daily_stat("User session", $user['ref']);
            //log_activity(null,LOG_CODE_LOGGED_IN,$ip,"user","last_ip",($userref!="" ? $userref :"null"),null,'',($userref!="" ? $userref :"null"));
            ps_query("DELETE FROM ip_lockout WHERE ip = ?",array("s",$ip));
            global $user_preferences;
            set_login_cookies(intval($user['ref']),$session_hash,$language, $user_preferences);
            header('Location: ' . $baseurl . '/pages/home.php');
            exit();
        } else {
            echo 'Error: Unable to obtain tokens from Keycloak';
        }
    }
}

?>
