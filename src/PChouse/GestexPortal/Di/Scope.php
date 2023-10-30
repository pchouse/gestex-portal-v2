<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\Di;

/**
 * Instance scope
 */
enum Scope
{
    /**
     * A new instance it will be created every time that is get from container
     */
    case TRANSIENT;

    /**
     * Only one instance it will be created when get from container
     */
    case SINGLETON;

    /**
     * The value is the instance to provide
     */
    case PROVIDES;

    /**
     * Controller to roots
     */
    case ROUTE;

    /**
     * Test mock
     */
    case MOCK;
}
