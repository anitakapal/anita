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

/**
 * GroupTest class
 *
 * @category  TestCases
 * @package   REST API
 * @author    anita <anita@gmail.com>
 * @copyright 2021 REST API
 * @license   anita https://anita.com/legal/license
 * @version   Release: 8.0.0
 * @link      http://google.com
 */

class GroupTest extends TestCase
{
    public function testUserLogin(): array
    {
        $group = [];
        $this->assertEmpty($group);

        $parameters = [
            'email' => 'surbhi@gmail.com',
            'password' => '1234',
        ];
        //$response = $this->call('POST', 'api/login', $parameters);
        $this->post("api/login", $parameters);
        $json_data = json_decode($this->response->getContent());
        array_push($group, $json_data);
        return $group;
    }

    /**
     * Show all groups
     *
     * @param array $group group data array
     *
     * @return void
     * @depends testUserLogin
     */
    public function testShouldReturnAllGroups(array $group): void
    {
        $header = ['HTTP_Authorization' => 'bearer ' . $group[0]->token];

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

    /**
     * Create new group
     *
     * @param array $group groupdata array
     *
     * @return void
     * @depends testUserLogin
     */
    public function testShouldCreateGroup(array $group): array
    {
        $parameters = [
            'group_name' => 'ABC Group1',
        ];

        $header = ['HTTP_Authorization' => 'bearer ' . $group[0]->token];

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
        $json_data = json_decode($this->response->getContent());
        array_push($group, $json_data->data);
        return $group;
    }

    // add members to group
    // public function testShouldAddMembersToGroup(array $group): void
    // {
    //     $parameters = [
    //         'member_id' => '1,2',
    //     ];

    //     $header = ['HTTP_Authorization' => 'bearer ' . $group[0]->api_token];
    //     $this->post("api/groups/1/members", $parameters, $header);
    //     $this->seeStatusCode(200);
    //     $this->seeJsonStructure([
    //         'data' => ['*' =>
    //             [
    //                 'name',
    //                 'email',
    //                 'contact_no',
    //                 'created_at',
    //                 'updated_at',
    //                 'links',
    //             ],
    //         ],

    //     ]);
    // }

    // delete group members
    // public function testShouldDeleteMembers(array $group): void
    // {
    //     $header = ['HTTP_Authorization' => 'bearer ' . $group[0]->api_token];

    //     $this->delete("api/groups/2/members/2", [], $header);
    //     $this->seeStatusCode(410);
    //     $this->seeJsonStructure([
    //         'message',
    //     ]);
    // }
}
