<?php
namespace Di4Php;

use Di4Php\Exception\ClassNotFoundException;
use Di4Php\Exception\ContractNotFoundException;

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
     * Service constructor.
     * @param string $contract
     * @param string $class
     * @throws ContractNotFoundException
     * @throws ClassNotFoundException
     */
    public function __construct(string $class, string $contract = null)
    {
        if ($contract === null) {
            if (!class_exists($class)) {
                throw new ClassNotFoundException;
            }

            $contract = $class;
        }

        if (!class_exists($contract) || !interface_exists($contract)) {
            throw new ContractNotFoundException;
        }

        if (!class_exists($class)) {
            throw new ClassNotFoundException;
        }

        $this->contract = $contract;
        $this->class = $class;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @return string
     */
    public function getContract()
    {
        return $this->contract;
    }
}