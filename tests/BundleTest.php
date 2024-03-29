<?php

namespace BenTools\Shh\Tests;

use BenTools\Shh\Command\CheckCommand;
use BenTools\Shh\Command\DecryptCommand;
use BenTools\Shh\Command\EncryptCommand;
use BenTools\Shh\Command\RegisterSecretCommand;
use BenTools\Shh\Shh;
use BenTools\Shh\ShhEnvVarProcessor;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class BundleTest extends KernelTestCase
{
    protected function setUp(): void
    {
        static::bootKernel();
    }

    /**
     * @test
     */
    public function bundle_is_configured()
    {
        $this->assertTrue(self::$kernel->getContainer()->get('test.service_container')->has(CheckCommand::class));
        $this->assertTrue(self::$kernel->getContainer()->get('test.service_container')->has(DecryptCommand::class));
        $this->assertTrue(self::$kernel->getContainer()->get('test.service_container')->has(EncryptCommand::class));
        $this->assertTrue(self::$kernel->getContainer()->get('test.service_container')->has(RegisterSecretCommand::class));
        $this->assertTrue(self::$kernel->getContainer()->get('test.service_container')->has(Shh::class));
        $this->assertTrue(self::$kernel->getContainer()->get('test.service_container')->has(ShhEnvVarProcessor::class));
        $this->assertEquals(__DIR__.'/.keys/private.pem', self::$kernel->getContainer()->getParameter('shh.private_key_file'));
        $this->assertEquals(__DIR__.'/.keys/public.pem', self::$kernel->getContainer()->getParameter('shh.public_key_file'));
        $this->assertEquals('USNuclearCodeIs0000', self::$kernel->getContainer()->getParameter('shh.passphrase'));
        $this->assertEquals('d0N4LD 7rUMP i5 4 L177lE 91RL!', self::$kernel->getContainer()->getParameter('some_encrypted_secret'));
    }
}
