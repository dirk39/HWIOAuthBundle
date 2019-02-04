<?php

namespace HWI\Bundle\OAuthBundle\OAuth\ResourceOwner;

use Symfony\Component\OptionsResolver\OptionsResolver;

class KeycloakResourceOwner extends GenericOAuth2ResourceOwner
{
    /**
     * {@inheritdoc}
     */
    protected $paths = array(
      'identifier' => 'sub',
      'nickname' => 'preferred_username',
      'realname' => 'name',
      'firstname' => 'given_name',
      'lastname' => 'family_name',
      'email' => 'email'
    );

    public function configure()
    {
        $this->prepareBaseUrls();
        $this->addPaths($this->paths);
    }

    public function getAuthorizationUrl($redirectUri, array $extraParameters = array())
    {
        return parent::getAuthorizationUrl($redirectUri, array_merge(array(
          'approval_prompt' => $this->getOption('approval_prompt')
        ),$extraParameters));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
          ->setDefined(array('protocol', 'response_type', 'approval_prompt'))
          ->setRequired(array('realm', 'base_url'))
          ->setDefaults(array(
            'protocol' => 'openid-connect',
            'scope' =>  'name,email',
            'response_type' => 'code',
            'approval_prompt' => 'auto',
            //we configure the urls later (if user don't set them)
            'authorization_url' => 'authorization_url',
            'infos_url' => 'infos_url',
            'access_token_url' => 'access_token_url',));
    }

    protected function prepareBaseUrls()
    {
        $urls = array(
          'authorization_url' => 'auth',
          'infos_url' => 'userinfo',
          'access_token_url' => 'token');

        foreach ($urls as $urlType => $suffix) {
            $this->buildUrl($urlType, $suffix);
        }
    }

    private function buildUrl($urlType, $suffix)
    {
        $url = trim($this->getOption($urlType), '/');
        //check if already configured
        if ($url !== $urlType) {
            return;
        }

        $url = trim($this->getOption('base_url'), '/').'/auth/realms/' . $this->getOption('realm');
        $url .= '/protocol/' . $this->getOption('protocol');

        $this->options[$urlType] = $url . '/'.$suffix;
    }
}