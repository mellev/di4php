<?php
namespace Di4Php\Test;

use Di4Php\Container;
use Di4Php\Exception\ClassNotFoundException;
use Di4Php\Exception\ContractNotFoundException;
use Di4Php\Mocks\EmptyService;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
	public function testRegisterNotExistenClass()
	{
        $this->expectException(ClassNotFoundException::class);
        (new Container())->registerService(EmptyService::class . '2');
	}

    public function testRegisterNotExistenContract()
    {
        $this->expectException(ContractNotFoundException::class);
        (new Container())->registerService(EmptyService::class,EmptyService::class . '2');
    }
}