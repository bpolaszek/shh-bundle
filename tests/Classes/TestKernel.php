<?php

namespace BenTools\Shh\Tests\Classes;

use BenTools\Shh\ShhBundle;
use Nyholm\BundleTest\CompilerPass\PublicServicePass;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\FrameworkBundle\Test\TestContainer;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\ServiceLocator;
use Symfony\Component\HttpKernel\Kernel;
use function Symfony\Component\DependencyInjection\Loader\Configurator\abstract_arg;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

final class TestKernel extends Kernel
{
    use MicroKernelTrait;

    public function registerBundles(): iterable
    {
        return [
            new FrameworkBundle(),
            new ShhBundle(),
        ];
    }

    public function configureContainer(ContainerConfigurator $container): void
    {
        $container->services()

            ->set('test.service_container', TestContainer::class)
            ->args([
                service('kernel'),
                'test.private_services_locator',
            ])
            ->public()

            ->set('test.private_services_locator', ServiceLocator::class)
            ->args([abstract_arg('callable collection')])
            ->public()
        ;

        $container->parameters()->set('kernel.secret', '%env(APP_SECRET)%');
        $container->parameters()->set('some_encrypted_secret', '%env(shh:A_BIG_SECRET)%');
        $container->extension('shh', [
            'private_key_file' => dirname(__DIR__). '/.keys/private.pem',
            'public_key_file' => dirname(__DIR__). '/.keys/public.pem',
        ]);
    }

    public function getProjectDir(): string
    {
        return dirname(__DIR__);
    }

    public function getCacheDir(): string
    {
        return sys_get_temp_dir() . '/sf-cache';
    }

    public function getLogDir(): string
    {
        return sys_get_temp_dir() . '/sf-log';
    }
}
