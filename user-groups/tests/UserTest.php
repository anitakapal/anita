<?php

class UserTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */

    //Test cases for user apis

    public function testShouldReturnAllUsers()
    {
        $this->get("api/users", []);
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'data' => ['*' =>
                [
                    'name',
                    'email',
                    'contact_no',
                    'created_at',
                    'updated_at',
                    'links',
                ],
            ],
            'meta' => [
                '*' => [
                    'total',
                    'count',
                    'per_page',
                    'current_page',
                    'total_pages',
                    'links',
                ],
            ],
        ]);

    }
    //test create user
    public function testShouldCreateUser()
    {

        $parameters = [
            'name' => 'sneha',
            'email' => 'sneha@gmail.com',
            'contact_no' => '8866654564',
            'password' => 'ffggf',
        ];

        $this->post("api/users", $parameters, []);
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
    }
    //user update
    public function testShouldUpdateUser()
    {
        $parameters = [
            'name' => 'Sam',
            'email' => 'sam@gmail.com',
            'contact_no' => '9987656760',
            'password' => 'ffggf',
        ];
        $this->put("api/users/3", $parameters, []);
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
    }
    //test delete user
    public function testShouldDeleteUser()
    {
        $this->delete("api/users/7", [], []);
        $this->seeStatusCode(410);
        $this->seeJsonStructure([
            'message',
        ]);
    }

}
