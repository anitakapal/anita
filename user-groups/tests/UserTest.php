<?php

class UserTest extends TestCase
{

    public function testEmpty(): array
    {
        //Cache::flush();

        $stack = [];
        $this->assertEmpty($stack);

        return $stack;
    }

    /**
     * @depends testEmpty
     */
    public function testShouldCreateUser(array $stack): array
    {

        $parameters = [
            'name' => 'sneha',
            'email' => 'sneha@gmail.com',
            'contact_no' => '8866654564',
            'password' => '1234',
        ];

        //$response = $this->call('GET', 'api/users/20');
        $response = $this->call('POST', 'api/users', $parameters);

        //$response = $this->get("api/users/2", [], []);
        //dd($response->getContent());
        $json_data = json_decode($response->getContent());
        array_push($stack, $json_data->data);

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

        // $this->assertSame($parameters, $stack[count($stack) - 1]);
        // $this->assertNotEmpty($stack);

        return $stack;
    }

    /**
     * @depends testPush
     */
    public function testShouldUpdateUser(array $stack): array
    {
        $parameters = [
            'name' => 'Sam',
            'email' => 'sam@gmail.com',
            'contact_no' => '9987656760',
            'password' => 'ffggf',
        ];

        $this->put("api/users/" . $stack[0]->id, $parameters, []);
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
        return $stack;

        //$this->assertSame('foo', array_pop($stack));
        //$this->assertEmpty($stack);
    }

    public function login(array $stack): array
    {
        $parameters = [
            'email' => 'sneha@gmail.com',
            'password' => '1234',
        ];
        $response = $this->call('POST', 'api/login', $parameters);
        dd($response);
        $json_data = json_decode($response->getContent());
        array_push($stack, $json_data->data);
        return $stack;

    }

    public function testShouldDeleteUser(array $stack): void
    {
        //dd($stack);
        $this->delete("api/users/" . $stack[0]->id, [], []);
        $this->seeStatusCode(410);
        $this->seeJsonStructure([
            'message',
        ]);
    }
}
