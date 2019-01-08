Step 2x: Setup Keycloak
=====================
First you will have to create your keycloak installation and set it up 
(https://www.keycloak.org/docs/latest/server_installation/index.html)

Next configure a resource owner of type `keycloak` with appropriate
`client_id`, `client_secret`, `base_url`, `options.realms`.
The params `access_token_url`, `infos_url`, `authorization_url` if not set will be automatically built using `base_url` param.

Refer to your keycloak installation and documentation to correctly configure.

``` yaml
# app/config/config.yml OR app/config/packages/hwi_oauth.yml

resource_owners:
        keycloak:
            type:                keycloak
            client_id:           <client_id>
            client_secret:       <client_secret>
            base_url:            'http://www.mykeycloak.com'            
            options:
                csrf: true
                realms:              <keycloak_realm_name>
```

When you're done. Continue by configuring the security layer or go back to
setup more resource owners.

- [Step 2: Configuring resource owners (Facebook, GitHub, Google, Windows Live and others](../2-configuring_resource_owners.md)
- [Step 3: Configuring the security layer](../3-configuring_the_security_layer.md).
