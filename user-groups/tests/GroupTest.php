<?php
class GroupTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    //show all groups
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

    // create new group
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

    // add members to group
    public function testShouldAddMembersToGroup()
    {
        $parameters = [
            'member_id' => '1,2',
        ];

        $header = ['HTTP_Authorization' => 'bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvYXBpXC9sb2dpbiIsImlhdCI6MTYxMTc2ODA4OCwiZXhwIjoxNjExNzcxNjg4LCJuYmYiOjE2MTE3NjgwODgsImp0aSI6IkJtcDRybWljbFFDNWp4YzAiLCJzdWIiOjQsInBydiI6Ijg3ZTBhZjFlZjlmZDE1ODEyZmRlYzk3MTUzYTE0ZTBiMDQ3NTQ2YWEifQ.AJkU5id1EMjBv6fqUyQ_RuTqDKIk-_oWOBhPP69YzTM'];

        $this->post("api/groups/1/members", $parameters, $header);
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

        ]);
    }

    // delete group members
    public function testShouldDeleteMembers()
    {
        $header = ['HTTP_Authorization' => 'bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvYXBpXC9sb2dpbiIsImlhdCI6MTYxMTc2ODA4OCwiZXhwIjoxNjExNzcxNjg4LCJuYmYiOjE2MTE3NjgwODgsImp0aSI6IkJtcDRybWljbFFDNWp4YzAiLCJzdWIiOjQsInBydiI6Ijg3ZTBhZjFlZjlmZDE1ODEyZmRlYzk3MTUzYTE0ZTBiMDQ3NTQ2YWEifQ.AJkU5id1EMjBv6fqUyQ_RuTqDKIk-_oWOBhPP69YzTM'];

        $this->delete("api/groups/2/members/2", [], $header);
        $this->seeStatusCode(410);
        $this->seeJsonStructure([
            'message',
        ]);
    }
}
