<?php
namespace Di4Php\Test;

use Di4Php\Container;
use Di4Php\Exception\ClassIsNotInstantiableException;
use Di4Php\Exception\ClassNotFoundException;
use Di4Php\Exception\ContractNotFoundException;
use Di4Php\Test\Mocks\EmptyService;
use Di4Php\Test\Mocks\EmptyService2;
use Di4Php\Test\Mocks\AbstractEmptyService;
use Di4Php\Test\Mocks\EmptyServiceInterface;
use Di4Php\Test\Mocks\PrivateConstructorEmptyService;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
	public function testRegisterNotExistenClass()
	{
        $this->expectException(ClassNotFoundException::class);
        (new Container())->registerService(EmptyService::class . 'Null');
	}

    public function testRegisterAbstractClass()
    {
        $this->expectException(ClassIsNotInstantiableException::class);
        (new Container())->registerService(AbstractEmptyService::class);
    }

    public function testRegisterPrivateConstructorClass()
    {
        $this->expectException(ClassIsNotInstantiableException::class);
        (new Container())->registerService(PrivateConstructorEmptyService::class);
    }

    public function testRegisterNotExistenContract()
    {
        $this->expectException(ContractNotFoundException::class);
        (new Container())->registerService(EmptyService::class,EmptyService::class . 'Null');
    }

    public function testSuccessRegisterClass()
    {
        $this->assertNull((new Container())->registerService(EmptyService::class));
    }

    public function testSuccessRegisterClassWithInterfaceContract()
    {
        $this->assertNull((new Container())->registerService(EmptyService::class, EmptyServiceInterface::class));
    }

    public function testSuccessRegisterClassWithClassContract()
    {
        $this->assertNull((new Container())->registerService(EmptyService::class, EmptyService2::class));
    }

    public function testSuccessRegisterClassWithAbstractClassContract()
    {
        $this->assertNull((new Container())->registerService(EmptyService::class,AbstractEmptyService::class));
    }
}