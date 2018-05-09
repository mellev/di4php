<?php
namespace Di4Php;

use Di4Php\Exception\ClassNotFoundException;
use Di4Php\Exception\ContractNotFoundException;
use Di4Php\Exception\ClassIsNotInstantiableException;

/**
 * Class Service
 * @package Di4Php
 */
class Service
{
    /**
     * @var string
     */
    private $contract;

    /**
     * @var string
     */
    private $class;

    /**
     * @var SharedInstance
     */
    private $sharedInstance;

    /**
     * Service constructor.
     * @param string $contract
     * @param string $class
     * @throws ContractNotFoundException
     * @throws ClassNotFoundException
     * @throws ClassIsNotInstantiableException
     */
    public function __construct(string $class, string $contract = null)
    {
        if ($contract === null) {
            if (!class_exists($class)) {
                throw new ClassNotFoundException;
            }

            $contract = $class;
        }

        if (!class_exists($contract) && !interface_exists($contract)) {
            throw new ContractNotFoundException;
        }

        if (!class_exists($class)) {
            throw new ClassNotFoundException;
        }

        if (!$this->checkClassIsInstantiable($class)) {
            throw new ClassIsNotInstantiableException;
        }

        $this->contract = $contract;
        $this->class = $class;

        $this->sharedInstance = new SharedInstance($this);
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @return string
     */
    public function getContract(): string
    {
        return $this->contract;
    }

    /**
     * @return SharedInstance
     */
    public function getSharedInstance(): SharedInstance
    {
        return $this->sharedInstance;
    }

    /**
     * @param string $class
     * @return bool
     */
    private function checkClassIsInstantiable(string $class): bool
    {
        $reflectionClass = new \ReflectionClass($class);
        return $reflectionClass->isInstantiable();
    }
}