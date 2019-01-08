<?php

namespace HWI\Bundle\OAuthBundle\Tests\OAuth\ResourceOwner;

use HWI\Bundle\OAuthBundle\OAuth\ResourceOwner\KeycloakResourceOwner;
use HWI\Bundle\OAuthBundle\Tests\Fixtures\CustomUserResponse;
use Symfony\Component\HttpFoundation\Request;

class KeycloakResourceOwnerTest extends GenericOAuth2ResourceOwnerTest
{
    protected $options = array(
      'client_id' => 'clientid',
      'client_secret' => 'clientsecret',
      'realms' => 'example',
      'base_url' => 'http://keycloak.local',

      'attr_name' => 'access_token',
    );

    protected $paths = array(
      'identifier' => 'sub',
      'nickname' => 'preferred_username',
      'realname' => 'name',
      'firstname' => 'given_name',
      'lastname' => 'family_name',
      'email' => 'email'
    );

    protected $userResponse = <<<json
{
  "sub":"1",
  "email_verified":true,
  "name":"John Doe",
  "preferred_username":"bar",
  "given_name":"John",
  "family_name":"Doe",
  "email":"john.doe@anubi.io"
  }
json;

    protected $expectedUrls = array(
      'authorization_url' => 'http://keycloak.local/auth/realms/example/protocol/openid-connect/auth?response_type=code&client_id=clientid&scope=name%2Cemail&redirect_uri=http%3A%2F%2Fredirect.to%2F&approval_prompt=auto',
      'authorization_url_csrf' => 'http://keycloak.local/auth/realms/example/protocol/openid-connect/auth?response_type=code&client_id=clientid&scope=name%2Cemail&state=random&redirect_uri=http%3A%2F%2Fredirect.to%2F&approval_prompt=auto',
      'access_token_url' => 'http://keycloak.local/auth/realms/example/protocol/openid-connect/token',
      'infos_url' => 'http://keycloak.local/auth/realms/example/protocol/openid-connect/userinfo'
    );

    protected $resourceOwnerClass = KeycloakResourceOwner::class;

    public function testUrlsAlreadySet()
    {
        $urls = [
          'authorization_url' => 'http://keycloak.local/auth/realms/example/auth',
          'access_token_url' => 'http://keycloak.local/auth/realms/example/token',
          'infos_url' => 'http://keycloak.local/auth/realms/example/userinfo'
        ];

        $resourceOwner = $this->createResourceOwner($this->resourceOwnerClass,$urls);

        $this->assertEquals($urls['authorization_url'], $resourceOwner->getOption('authorization_url'));
        $this->assertEquals($urls['access_token_url'], $resourceOwner->getOption('access_token_url'));
        $this->assertEquals($urls['infos_url'], $resourceOwner->getOption('infos_url'));
    }
}
