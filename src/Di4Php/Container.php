<?php
namespace Di4Php;

use Di4Php\Exception\ClassNotFoundException;
use Di4Php\Exception\ContractNotFoundException;
use Di4Php\Exception\ClassIsNotInstantiableException;
use Di4Php\Exception\ServiceNotRegisteredException;

/**
 * Class Container
 * @package Di4Php
 */
class Container
{
    /**
     * @var Service[]
     */
    private $services = [];

    /**
     * @param string $class
     * @param string|null $contact
     * @throws ClassNotFoundException
     * @throws ContractNotFoundException
     * @throws ClassIsNotInstantiableException
     */
    public function add(string $class, string $contact = null)
    {
        $this->services[$contact] = new Service($class, $contact);
    }

    /**
     * @param string $contract
     * @return ServiceInstantiator
     * @throws ServiceNotRegisteredException
     */
    public function getServiceInstantiator(string $contract): ServiceInstantiator
    {
        if (!$this->serviceExists($contract)) {
            throw new ServiceNotRegisteredException;
        }

        return new ServiceInstantiator($this->services[$contract]);
    }

    /**
     * @param string $contract
     * @return bool
     */
    public function serviceExists(string $contract): bool
    {
        return array_key_exists($contract, $this->services);
    }
}