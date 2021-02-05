<?php

/**
 * /tests/UserTest.php
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

use Illuminate\Support\Facades\Cache;

/**
 * UserTest class
 *
 * @category  TestCases
 * @package   REST API
 * @author    anita <anita@gmail.com>
 * @copyright 2021 REST API
 * @license   anita https://anita.com/legal/license
 * @version   Release: 8.0.0
 * @link      http://google.com
 */
class UserTest extends TestCase
{
    /**
     * Create empty array
     *
     * @return array
     */
    public function testEmpty(): array
    {
        Cache::flush();
        $user = [];
        $this->assertEmpty($user);
        return $user;
    }

    /**
     * Create new user
     *
     * @param array $user empty array
     *
     * @return array
     * @depends testEmpty
     */
    public function testShouldCreateUser(array $user): array
    {
        $parameters = [
            'name' => 'test1',
            'email' => 'test1@gmail.com',
            'contact_no' => '8866654564',
            'password' => '1234',
        ];
        $this->post('/api/users', $parameters, []);
        $json_data = json_decode($this->response->getContent());
        array_push($user, $json_data->data);
        $this->seeStatusCode(200);
        $this->seeJsonStructure(
            ['data' =>
                [
                    'id',
                    'name',
                    'email',
                    'contact_no',
                    'created_at',
                    'updated_at',
                    'links',
                ],
            ]
        );

        return $user;
    }

    /**
     * update user by id
     *
     * @param array $user userdata array
     *
     * @return array
     * @depends testShouldCreateUser
     */
    public function testShouldUpdateUser(array $user): array
    {
        $parameters = [
            'name' => 'test1 updated',
            'email' => 'test1updated@gmail.com',
            'contact_no' => '9987656760',
            'password' => 'ffggf',
        ];

        $this->put("api/users/" . $user[0]->id, $parameters, []);
        $this->seeStatusCode(200);
        $this->seeJsonStructure(
            ['data' =>
                [
                    'name',
                    'email',
                    'contact_no',
                    'created_at',
                    'updated_at',
                    'links',
                ],
            ]
        );
        $stack = [];
        $json_data = json_decode($this->response->getContent());
        array_push($stack, $json_data->data);
        return $stack;
    }

    /**
     * Remove user by id
     *
     * @param array $user userdata array
     *
     * @return void
     * @depends testShouldUpdateUser
     */
    public function testShouldDeleteUser(array $user): void
    {
        $this->delete("api/users/" . $user[0]->id, [], []);
        $this->seeStatusCode(410);
        $this->seeJsonStructure([
            'message',
        ]);
    }
}
