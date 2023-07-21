<?php

namespace EzPlatform\ActivitiesLogBundle\DependencyInjection\Security\PolicyProvider;

use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\Security\PolicyProvider\YamlPolicyProvider;

class UIEzPlatformActivitiesLogPolicyProvider extends YamlPolicyProvider
{
    protected string $path;

    /**
     * UIEzPlatformActivitiesLogPolicyProvider constructor.
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function getFiles(): array
    {
        return [$this->path.'/Resources/config/policies.yaml'];
    }
}
