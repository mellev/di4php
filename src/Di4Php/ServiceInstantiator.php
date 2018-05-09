<?php
namespace Di4Php;

use Di4Php\Exception\ClassNotFoundException;
use Di4Php\Exception\ContractNotFoundException;
use Di4Php\Exception\ClassIsNotInstantiableException;

/**
 * Class Service
 * @package Di4Php
 */
class ServiceInstantiator
{
    /**
     * @var Service
     */
    private $service;

    /**
     * @var Container
     */
    private $container;

    /**
     * ServiceInstantiator constructor.
     * @param Service $service
     * @param Container $container
     */
    public function __construct(Service $service, Container $container)
    {
        $this->service = $service;
        $this->container = $container;
    }

    /**
     * @param array $args
     * @return mixed
     */
    public function instantiate(array $args = [])
    {
        $reflectionClass = new \ReflectionClass($this->service->getClass());
        return $reflectionClass->newInstanceArgs($args);
    }

    /**
     * @param array $args
     * @return mixed
     */
    public function instantiateShared(array $args = [])
    {
        $sharedInstance = $this->service->getSharedInstance();

        if ($sharedInstance->hasInstance()) {
            return $sharedInstance->getInstance();
        }

        $instance = $this->instantiate($args);
        $sharedInstance->saveInstance($instance);

        return $instance;
    }
}