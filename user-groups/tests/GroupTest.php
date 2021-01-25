<?php
class GroupTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */

    public function testShouldReturnAllGroups()
    {
        $header = ['HTTP_Authorization' => 'bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvYXBpXC9sb2dpbiIsImlhdCI6MTYxMTU5MTUzMywiZXhwIjoxNjExNTk1MTMzLCJuYmYiOjE2MTE1OTE1MzMsImp0aSI6IjdxckNySTl0QlVvYUhBZjUiLCJzdWIiOjIsInBydiI6Ijg3ZTBhZjFlZjlmZDE1ODEyZmRlYzk3MTUzYTE0ZTBiMDQ3NTQ2YWEifQ.JAY-pPTNySc3rvKqPs9Kr06DB91If2FI414hmykxQTw'];

        $this->get("api/groups", $header);
        $this->get("api/groups", []);
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'data' => ['*' =>
                [
                    'group_name',
                    'group_created_by',
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

    public function testShouldCreateGroup()
    {
        $parameters = [
            'group_name' => 'ABC Group1',
        ];

        $header = ['HTTP_Authorization' => 'bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvYXBpXC9sb2dpbiIsImlhdCI6MTYxMTU5MTUzMywiZXhwIjoxNjExNTk1MTMzLCJuYmYiOjE2MTE1OTE1MzMsImp0aSI6IjdxckNySTl0QlVvYUhBZjUiLCJzdWIiOjIsInBydiI6Ijg3ZTBhZjFlZjlmZDE1ODEyZmRlYzk3MTUzYTE0ZTBiMDQ3NTQ2YWEifQ.JAY-pPTNySc3rvKqPs9Kr06DB91If2FI414hmykxQTw'];

        $this->post("api/groups", $parameters, $header);
        $this->seeStatusCode(200);
        $this->seeJsonStructure(
            ['data' =>
                [
                    'id',
                    'group_name',
                    'group_created_by',
                    'created_at',
                    'updated_at',
                    'links',
                ],
            ]
        );

    }
}
