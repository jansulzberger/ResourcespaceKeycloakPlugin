<?php

include '../../../include/db.php';


include "../../../include/authenticate.php"; if (!checkperm("a")) {exit ("Permission denied.");}
$plugin_page_heading = $lang['keycloak_configuration'];
$plugin_name = 'keycloak';
if(!in_array($plugin_name, $plugins))
{plugin_activate_for_setup($plugin_name);}


$page_def[] = config_add_section_header($lang['keycloak_server'],$lang['keycloak_server_description']);
$page_def[] = config_add_text_input(
    'keycloak_server_url',
    $lang['keycloak_server_url']
);
$page_def[] = config_add_text_input(
    'keycloak_client_id',
    $lang['keycloak_client_id']
);
$page_def[] = config_add_text_input(
    'keycloak_client_secret',
    $lang['keycloak_client_secret']
);
$page_def[] = config_add_text_input(
    'keycloak_realm',
    $lang['keycloak_realm']
);

$page_def[] = config_add_text_input(
    'keycloak_scopes',
    $lang['keycloak_scopes']
);

$page_def[] = config_add_section_header($lang['keycloak_server_endpoints'],$lang['keycloak_server_endpoint_description']);


$page_def[] = config_add_text_input(
    'keycloak_authorization_endpoint',
    $lang['keycloak_authorization_endpoint']
);

$page_def[] = config_add_text_input(
    'keycloak_token_endpoint',
    $lang['keycloak_token_endpoint']
);

$page_def[] = config_add_text_input(
    'keycloak_introspection_endpoint',
    $lang['keycloak_introspection_endpoint']
);

$page_def[] = config_add_text_input(
    'keycloak_userinfo_endpoint',
    $lang['keycloak_userinfo_endpoint']
);

$page_def[] = config_add_text_input(
    'keycloak_end_session_endpoint',
    $lang['keycloak_end_session_endpoint']
);

$page_def[] = config_add_text_input(
    'keycloak_jwks_uri',
    $lang['keycloak_jwks_uri']
);

$page_def[] = config_add_text_input(
    'keycloak_check_session_iframe',
    $lang['keycloak_check_session_iframe']
);

$page_def[] = config_add_text_input(
    'keycloak_logout_uri',
    $lang['keycloak_logout_uri']
);


// Do the page generation ritual -- don't change this section.
config_gen_setup_post($page_def, $plugin_name);
include '../../../include/header.php';
config_gen_setup_html($page_def, $plugin_name, null, $plugin_page_heading);

include '../../../include/footer.php';