<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Dusk\TestCase as DuskBaseTestCase;

abstract class DuskTestCase extends DuskBaseTestCase
{
    use CreatesApplication;

    /**
     * Tentukan base URL untuk Dusk.
     *
     * @return string
     */
    protected function baseUrl()
    {
        return 'http://localhost'; // ganti jika pakai port lain
    }
}
