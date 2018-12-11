<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

/**
 * REVIEW: 😁 Great test suite, nice usage of factories to create scenarios for complex
 * object creation.
 */
abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
}
