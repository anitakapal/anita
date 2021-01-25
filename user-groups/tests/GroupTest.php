<?php

use Laravel\Lumen\Testing\TestCase as BaseTestCase;

abstract class GroupCase extends BaseTestCase
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

    public function testShouldCreateGroup()
    {

        $parameters = [
            'group_name' => 'XYZ',
            'group_created_by' => 1,
        ];

        $this->post("groups", $parameters, []);
        $this->seeStatusCode(200);
        $this->seeJsonStructure(
            ['data' =>
                [
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
