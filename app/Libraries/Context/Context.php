<?php

namespace App\Libraries\Context;

/**
 * Application Context
 *
 * Similar to ServerRequestInterface
 * This is meant to hold in-scope request information
 *
 * Class Context
 * @package App\Libraries\Context
 */
interface Context
{
    /**
     * Return context's attributes
     *
     * @return array<string|class-string, mixed>
     */
    public function getAttributes(): array;

    /**
     * Get a context attribute if exist
     *
     * @param string|class-string $key
     * @param string $default
     * @return mixed
     */
    public function getAttribute(string $key, mixed $default = null): mixed;

    /**
     * Add new attribute and return new context instance
     *
     * @param string|class-string $key
     * @param mixed $value
     * @return Context
     */
    public function withAttribute(string $key, mixed $value): Context;

    /**
     * Remove attribute and return new context instance
     *
     * @param string $key
     * @return AppContext
     */
    public function withoutAttribute(string $key): Context;
}
