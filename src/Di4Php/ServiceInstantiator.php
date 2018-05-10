<?php
namespace Di4Php;

use Di4Php\Exception\ArgumentCountException;
use Di4Php\Exception\TypeMismatchException;
use Di4Php\Exception\ServiceNotRegisteredException;
use Di4Php\Exception\LoopDependencyException;

/**
 * Class ServiceInstantiator
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
     * @throws ArgumentCountException
     * @throws ServiceNotRegisteredException
     * @throws TypeMismatchException
     */
    public function instantiate(array $args = [], $chain = [])
    {
        $reflectionClass = new \ReflectionClass($this->service->getClass());
        $constructor = $reflectionClass->getConstructor();

        if ($constructor === null) {
            return $reflectionClass->newInstance();
        }

        $resultArguments = [];
        $usedArgIndex = 0;
        $chain[] = $this->service->getContract();

        foreach ($constructor->getParameters() as $parameter) {
            if (array_key_exists($parameter->getName(), $args)) {
                if ($this->checkType($parameter, $args[$parameter->getName()])) {
                    $resultArguments[$parameter->getPosition()] = $args[$parameter->getName()];
                } else {
                    throw new TypeMismatchException;
                }
            } else if ($parameter->getClass() && $this->container->serviceExists($parameter->getClass()->getName())) {
                if (in_array($parameter->getClass()->getName(), $chain)) {
                    throw new LoopDependencyException();
                }
                $resultArguments[$parameter->getPosition()] = $this->container
                    ->getServiceInstantiator($parameter->getClass()->getName())
                    ->instantiate([], $chain);
            } else if (array_key_exists($usedArgIndex, $args)) {
                if ($this->checkType($parameter, $args[$usedArgIndex])) {
                    $resultArguments[$parameter->getPosition()] = $args[$usedArgIndex];
                    $usedArgIndex++;
                } else {
                    throw new TypeMismatchException;
                }
            } else if ($parameter->isDefaultValueAvailable()) {
                $resultArguments[$parameter->getPosition()] = $parameter->getDefaultValue();
            } else {
                throw new ArgumentCountException;
            }
        }

        return $reflectionClass->newInstanceArgs($resultArguments);
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

    /**
     * @param \ReflectionParameter $parameter
     * @param mixed $arg
     * @return bool
     */
    private function checkType(\ReflectionParameter $parameter, $arg): bool
    {
        if (is_object($arg)) {
            if ($parameter->getType() === null) {
                return true;
            }

            $class = $parameter->getClass();
            if ($class !== null) {
                return $arg instanceof $class->name;
            }

            return false;
        }

        if ($arg === null) {
            return $parameter->allowsNull();
        }

        if (!$parameter->hasType()) {
            return true;
        }

        if ($parameter->getClass() !== null) {
            return false;
        }

        //@TODO iterable для php 7.1
        switch ($parameter->getType()->__toString()) {
            case 'array':
                return is_array($arg);
            case 'callable':
                return is_callable($arg);
            case 'bool':
                return is_bool($arg);
            case 'float':
                return is_int($arg) || is_float($arg);
            case 'int':
                return is_int($arg);
            case 'string':
                return is_string($arg);
        }

        return false;
    }
}