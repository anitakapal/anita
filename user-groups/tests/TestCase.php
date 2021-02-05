<?php

/**
 * /tests/GroupTest.php
 * PHP Version 7
 *
 * @category  TestCases
 * @package   REST API
 * @author    anita <anita@gmail.com>
 * @copyright 2021 REST API
 * @license   anita https://anita.com/legal/license
 * @version   Release: 8.0.0
 * @link      http://google.com
 * @since     January 2021
 */

//namespace Tests;

use Laravel\Lumen\Testing\TestCase as BaseTestCase;

/**
 * AppsTest class
 *
 * @category  TestCases
 * @package   REST API
 * @author    anita <anita@gmail.com>
 * @copyright 2021 REST API
 * @license   anita https://anita.com/legal/license
 * @version   Release: 8.0.0
 * @link      http://google.com
 */
abstract class TestCase extends BaseTestCase
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__ . '/../bootstrap/app.php';
    }
}
