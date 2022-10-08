<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Traits\WithoutPermissionsTrait;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUpTraits()
    {
        parent::setUpTraits();
        $uses = array_flip(class_uses_recursive(static::class));
        if (isset($uses[WithoutPermissionsTrait::class])) {
            $this->withoutPermissionSetUp();
        }
    }
}
