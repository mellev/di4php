<?php
namespace Di4Php;

use Di4Php\Exception\ClassNotFoundException;
use Di4Php\Exception\ContractNotFoundException;

/**
 * Class Container
 * @package Di4Php
 */
class Container
{
    private $services = [];

    /**
     * @param string $service
     * @param string|null $contact
     * @throws ClassNotFoundException
     * @throws ContractNotFoundException
     */
    public function registerService(string $service, string $contact = null)
    {
        $this->services[$contact] = new Service($service, $contact);
    }
}