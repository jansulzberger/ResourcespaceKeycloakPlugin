<?php

$keycloak_client_id = 'resourcespace';
$keycloak_client_secret = 'resourcespace';

$keycloak_server_url = 'http://localhost:8080/realm/resourcespace/';

$keycloak_realm = 'resourcespace';


$keycloak_redirect_uri = 'protocol/openid-connect/auth';
$keycloak_logout_uri = 'protocol/openid-connect/logout';
$keycloak_scopes = 'openid profile email';

$keycloak_authorization_endpoint  = "protocol/openid-connect/auth";
$keycloak_token_endpoint  = "protocol/openid-connect/token";
$keycloak_introspection_endpoint  = "protocol/openid-connect/token/introspect";
$keycloak_userinfo_endpoint  = "protocol/openid-connect/userinfo";
$keycloak_end_session_endpoint  = "protocol/openid-connect/logout";
$keycloak_jwks_uri  = "protocol/openid-connect/certs";
$keycloak_check_session_iframe  = "protocol/openid-connect/login-status-iframe.html";
