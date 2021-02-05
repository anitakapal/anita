<?php
namespace App\Transformers;

use App\User;
use League\Fractal;

class UserTransformer extends Fractal\TransformerAbstract
{
    public function transform(User $user)
    {
        return [
            'id' => (int) $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'contact_no' => $user->contact_no,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
            'links' => [
                [
                    'uri' => 'users/' . $user->id,
                ],
            ],
        ];
    }
}
