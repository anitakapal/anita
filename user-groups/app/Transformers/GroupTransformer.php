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
            'name' => $group->name,
            'created_by' => $group->created_by,
            'type' => $group->type,
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
