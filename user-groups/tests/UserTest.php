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

    public function testShouldCreateUser()
    {

        $parameters = [
            'name' => 'neha',
            'email' => 'neha@gmail.com',
            'contact_no' => '9866654564',
            'password' => 'ffggf',
        ];

        // $res = $this->post("api/users", $parameters, []);
        // dd($res);
        $this->post("api/users", $parameters, []);

        $this->seeStatusCode(201);
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

}
