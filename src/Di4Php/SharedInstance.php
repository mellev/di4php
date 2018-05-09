<?php
namespace Di4Php;

use Di4Php\Exception\InstanceNotExistsException;
use Di4Php\Exception\TypeMismatchException;

/**
 * Class SharedInstance
 * @package Di4Php
 */
class SharedInstance
{
    /**
     * @var Service
     */
    private $service;

    /**
     * @var mixed
     */
    private $instance;

    /**
     * SharedInstance constructor.
     * @param Service $service
     */
    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    /**
     * @param mixed $instance
     * @throws TypeMismatchException
     */
    public function saveInstance(object $instance)
    {
        $class = $this->service->getClass();
        if (!$instance instanceof $class) {
            throw new TypeMismatchException;
        }

        $this->sharedInstance = $instance;
    }

    /**
     * @return mixed
     * @throws InstanceNotExistsException
     */
    public function getInstance()
    {
        if ($this->instance === null) {
            throw new InstanceNotExistsException;
        }

        return $this->instance;
    }

    /**
     * @return bool
     */
    public function hasInstance(): bool
    {
        return $this->instance !== null;
    }
}