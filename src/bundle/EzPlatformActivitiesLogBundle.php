<?php

namespace EzPlatform\ActivitiesLogBundle;

use EzPlatform\ActivitiesLogBundle\DependencyInjection\Security\PolicyProvider\UIEzPlatformActivitiesLogPolicyProvider;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EzPlatformActivitiesLogBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        /** @var \eZ\Bundle\EzPublishCoreBundle\DependencyInjection\EzPublishCoreExtension $kernelExtension */
        $kernelExtension = $container->getExtension('ezpublish');
        $kernelExtension->addPolicyProvider(new UIEzPlatformActivitiesLogPolicyProvider($this->getPath()));
    }
}
