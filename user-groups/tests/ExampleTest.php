<?php

/**
 * /tests/ExampleTest.php
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

/**
 * ExampleTest class
 *
 * @category  TestCases
 * @package   REST API
 * @author    anita <anita@gmail.com>
 * @copyright 2021 REST API
 * @license   anita https://anita.com/legal/license
 * @version   Release: 8.0.0
 * @link      http://google.com
 */
class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->get('/');

        $this->assertEquals($this->app->version(), $this->response->getContent());
    }
}
