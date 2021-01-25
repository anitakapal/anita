<?php
namespace App\Transformers;

use App\Group;
use League\Fractal;

class GroupTransformer extends Fractal\TransformerAbstract
{
    public function transform(Group $group)
    {
        return [
            'id' => (int) $group->id,
            'group_name' => $group->group_name,
            'group_created_by' => $group->group_created_by,
            'created_at' => $group->created_at->format('d-m-Y'),
            'updated_at' => $group->updated_at->format('d-m-Y'),
            'links' => [
                [
                    'uri' => 'groups/' . $group->id,
                ],
            ],
        ];
    }
}
