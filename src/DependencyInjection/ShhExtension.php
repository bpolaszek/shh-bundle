<?php

namespace BenTools\Shh\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

final class ShhExtension extends Extension
{
    /**
     * @param array<string, mixed> $configs
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $container->setParameter('env(SHH_PASSPHRASE)', null);
        $container->setParameter(
            'env(SHH_SECRETS_FILE)',
            \sprintf('%s/.secrets.json', $container->getParameter('kernel.project_dir'))  // @phpstan-ignore-line
        );
        $config = $this->processConfiguration($configuration, $configs);
        $container->setParameter('shh.private_key_file', $config['private_key_file']);
        $container->setParameter('shh.public_key_file', $config['public_key_file']);
        $container->setParameter('shh.passphrase', $config['passphrase']);
        $container->setParameter('shh.keys_dir', $this->guessKeysDirectory($container));

        $loader = new XmlFileLoader($container, new FileLocator(\dirname(__DIR__) . '/Resources/config'));
        $loader->load('services.xml');
    }

    private function guessKeysDirectory(ContainerBuilder $container): string
    {
        return $container->getParameter('kernel.project_dir') . '/config/shh'; // @phpstan-ignore-line
    }
}
