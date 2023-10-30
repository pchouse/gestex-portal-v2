<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\Di;

use PChouse\GestexPortal\MVC\IController;

/**
 * Dependency injection container
 */
class Container
{

    protected \Logger $logger;

    /**
     * The Container instance
     *
     * @var \PChouse\GestexPortal\Di\Container
     */
    protected static Container $selfInstance;

    /**
     * The binds stack
     *
     * @var \PChouse\GestexPortal\Di\IBind[]
     */
    protected array $bindStack = [];

    /**
     * Singletons stack
     *
     * @var object[]
     */
    protected array $singletons = [];

    /**
     * @throws \PChouse\GestexPortal\Di\DiException
     */
    protected function __construct()
    {
        $this->logger = \Logger::getLogger(static::class);
        $this->logger->debug("New Instance");
        $this->initBinds();
        $this->initRoutes();
    }

    /**
     * Init container
     *
     * @return void
     * @throws \PChouse\GestexPortal\Di\DiException
     */
    protected function initBinds(): void
    {
        $this->logger->debug("Init binds in DI Container");
        $binds = require __DIR__ . DIRECTORY_SEPARATOR . 'Binds.php';
        foreach ($binds as $bind) {
            $this->putBindInStack($bind);
        }
    }

    /**
     * Init routes container
     *
     * @throws \PChouse\GestexPortal\Di\DiException
     */
    protected function initRoutes(): void
    {
        $this->logger->debug("Init routes in DI Container");
        $routes = require __DIR__ . DIRECTORY_SEPARATOR . 'Routes.php';
        foreach ($routes as $route) {
            $this->putBindInStack($route);
        }
    }

    /**
     *
     * Build container
     *
     * @param array $binds
     *
     * @return void
     * @throws \PChouse\GestexPortal\Di\DiException
     */
    public static function build(array $binds): void
    {
        if (!isset(self::$selfInstance) || \count($binds) > 0) {
            self::$selfInstance = new self();
        }
        self::$selfInstance->logger->debug("Build DI Container start");
        foreach ($binds as $bind) {
            static::$selfInstance->putBindInStack($bind);
        }
    }

    /**
     *
     * Get controller for route
     *
     * @template T of \PChouse\GestexPortal\MVC\IController
     * @param class-string<T> $name
     *
     * @return T
     * @throws \PChouse\GestexPortal\Di\DiException
     * @throws \ReflectionException
     * @throws \Throwable
     */
    public static function getRouteController(string $name): IController
    {
        if (!isset(static::$selfInstance)) {
            static::build([]);
        }

        $container = static::$selfInstance;

        $container->logger->debug("Getting Route controller for $name");

        $bind = $container->getBindFromStack($name);

        if ($bind->getScope() !== Scope::ROUTE) {
            $msg = "'$name' is not a DI route";
            $container->logger->debug($msg);
            throw new DiException($msg);
        }

        /** @noinspection all */
        /** @phpstan-ignore-next-line */
        return $container->createInstance($bind);
    }


    /**
     * @template T
     *
     * @param class-string<T>|string $bindName
     *
     * @return T | mixed
     * @throws \PChouse\GestexPortal\Di\DiException
     * @throws \ReflectionException
     * @throws \Throwable
     */
    public static function get(string $bindName): mixed //@phpstan-ignore-line
    {
        /**  */
        if (!isset(static::$selfInstance)) {
            static::build([]);
        }

        $container = static::$selfInstance;

        $container->logger->debug("Get instance for $bindName");

        $key  = Container::keyBuilder($bindName);
        $bind = $container->getBindFromStack($bindName);

        if (\in_array($bind->getScope(), [Scope::MOCK, Scope::PROVIDES])) {
            $value = $bind->getValue();
            return \is_callable($value) ? \call_user_func($value) : $value;
        }

        if ($bind->getScope() === Scope::ROUTE) {
            throw new DiException("Controller cannot be getter from this method");
        }

        if ($bind->getScope() === Scope::SINGLETON) {
            if (!\array_key_exists($key, $container->singletons)) {
                if (\is_callable($bind->getValue())) {
                    $container->singletons[$key] = \call_user_func($bind->getValue());
                } else {
                    $container->singletons[$key] = $container->createInstance($bind);
                    $container->logger->info("Singleton $bindName added to Singleton stack");
                }
            }
            $container->logger->info("Return $bindName from Singleton stack");
            return $container->singletons[$key];
        }

        $container->logger->info("Going to create instance $bindName to return");

        if (\is_callable($bind->getValue())) {
            return \call_user_func($bind->getValue());
        }

        return $container->createInstance($bind);
    }

    /**
     * @param \PChouse\GestexPortal\Di\IBind $bind
     *
     * @return object
     * @throws \PChouse\GestexPortal\Di\DiException
     * @throws \ReflectionException
     * @throws \Throwable
     */
    protected function createInstance(IBind $bind): object
    {
        try {
            $reflectionClass = new \ReflectionClass($bind->getValue());
            $hasEvents       = $reflectionClass->isSubclassOf(IDIEvents::class);

            $countInjectClassAttributes    = \count($reflectionClass->getAttributes(Inject::class));
            $countAutowiredClassAttributes = \count($reflectionClass->getAttributes(Autowired::class));

            if ($countAutowiredClassAttributes === 0 && $countInjectClassAttributes === 0) {
                /** @var \PChouse\GestexPortal\Di\IDIEvents $instance */
                $instance = $reflectionClass->newInstance();
                if ($hasEvents) {
                    $instance->afterInstanceCreated();
                    $instance->beforeReturnInstance();
                }
                return $instance;
            }

            $constructorArgs = [];

            $constructorParameters = $reflectionClass->getConstructor()?->getParameters() ?? [];
            foreach ($constructorParameters as $parameter) {
                /** @phpstan-ignore-next-line */
                $constructorArgs[] = Container::get($parameter->getType()->getName());
            }

            /** @var \PChouse\GestexPortal\Di\IDIEvents $instance */
            $instance = $reflectionClass->newInstanceArgs([...$constructorArgs]);

            if ($hasEvents) {
                $instance->afterInstanceCreated();
            }

            if ($countAutowiredClassAttributes === 0) {
                if ($hasEvents) {
                    $instance->beforeReturnInstance();
                }
                return $instance;
            }

            $classProperties = $reflectionClass->getProperties();

            foreach ($classProperties as $property) {
                $countAttributes = \count(
                    \array_merge(
                        $property->getAttributes(Inject::class),
                        $property->getAttributes(Autowired::class)
                    )
                );

                if ($countAttributes === 0) {
                    continue;
                }

                /** @noinspection all */
                $property->setAccessible(true);

                $property->setValue(
                    $instance,
                    /** @phpstan-ignore-next-line */
                    Container::get($property->getType()->getName() ?? "")
                );
            }

            if ($hasEvents) {
                $instance->beforeReturnInstance();
            }

            return $instance;
        } catch (\Throwable $e) {
            $this->logger->error($e);
            throw $e;
        }
    }

    /**
     * Generate the bind stack key
     *
     * @param string $name The bind name
     *
     * @return string|class-string
     */
    protected static function keyBuilder(string $name): string
    {
        return \md5(\trim($name, " \t\n\r\0\x0B\\"));
    }

    /**
     * Put bind in stack
     *
     * @throws \PChouse\GestexPortal\Di\DiException
     */
    protected function putBindInStack(IBind $bind): void
    {
        $key = $this->keyBuilder($bind->getBinds());

        if ($bind->getScope() === Scope::MOCK) {
            if (!defined("GESTEX_TEST")) {
                throw new DiException("MOCK binds only allow in tests");
            }
            $this->bindStack[$key] = $bind;
            return;
        }

        if (\array_key_exists($key, $this->bindStack)) {
            throw new DiException(
                \sprintf(
                    "A bind for '%s' already exists in container",
                    $bind->getBinds()
                )
            );
        }

        $this->bindStack[$key] = $bind;
    }

    /**
     * Get bind from bind stack
     *
     * @param class-string|string $name
     *
     * @return \PChouse\GestexPortal\Di\IBind
     * @throws \PChouse\GestexPortal\Di\DiException
     */
    protected function getBindFromStack(string $name): IBind
    {
        $key = static::keyBuilder($name);
        if (\array_key_exists($key, $this->bindStack)) {
            return $this->bindStack[$key];
        }
        throw new DiException(
            \sprintf("Cannot get bind '%s' because not exists in stack", $name)
        );
    }
}
