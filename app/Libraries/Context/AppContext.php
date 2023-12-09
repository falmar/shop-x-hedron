<?php

namespace App\Libraries\Context;

class AppContext implements Context
{
    /** @var array<string, mixed> */
    protected array $attributes = [];

    /**
     * @param array<string, mixed> $attributes
     */
    public function __construct(array $attributes = [])
    {
        foreach ($attributes as $k => $v) {
            $this->attributes[$k] = $v;
        }
    }

    /**
     * @inheritDoc
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @inheritDoc
     */
    public function getAttribute(string $key, mixed $default = null): mixed
    {
        return ($this->attributes[$key] ??= $default);
    }

    /**
     * @inheritDoc
     */
    public function withAttribute(string $key, mixed $value): Context
    {
        $this->attributes[$key] = $value;

        return new self($this->getAttributes());
    }

    /**
     * @inheritDoc
     */
    public function withoutAttribute(string $key): Context
    {
        unset($this->attributes[$key]);

        return new self($this->getAttributes());
    }

    /**
     * Create base context
     *
     * @param array<string, mixed> $attributes
     * @return Context
     */
    public static function background(array $attributes = []): Context
    {
        return new AppContext($attributes);
    }
}
