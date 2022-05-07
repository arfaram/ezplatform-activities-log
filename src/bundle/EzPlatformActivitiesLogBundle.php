<?php

namespace EzPlatform\ActivitiesLogBundle;

use EzPlatform\ActivitiesLogBundle\DependencyInjection\Security\PolicyProvider\UIEzPlatformActivitiesLogPolicyProvider;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EzPlatformActivitiesLogBundle extends Bundle
{
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        /** @var \Ibexa\Bundle\Core\DependencyInjection\IbexaCoreExtension $kernelExtension */
        $kernelExtension = $container->getExtension('ibexa');
        $kernelExtension->addPolicyProvider(new UIEzPlatformActivitiesLogPolicyProvider($this->getPath()));
    }
}
