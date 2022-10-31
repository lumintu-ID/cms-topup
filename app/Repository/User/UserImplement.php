<?php

namespace App\Repository\User;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Repository\User\UserRepository;

class UserImplement implements UserRepository
{
    public function getAll()
    {
        $data = User::with('role')->orderBy('created_at', 'asc')->get();
        return $data;
    }

    public function Create($request)
    {
        $data =  User::create([
            'user_id'   => Str::uuid(),
            'name'      => $request['name'],
            'email'     => $request['email'],
            'password'  => Hash::make($request['password']),
            'role_id'   => $request['role'] ? $request['role'] : 2,
            'is_active' => 1
        ]);

        return $data;
    }

    public function getId($id)
    {
        $user = user::where('user_id', $id)->first();

        return $user;
    }

    public function update($id, $request)
    {
        if ($request['password']) {
            $user = user::where('user_id', $id)->update([
                'name'      => $request['name'],
                'email'     => $request['email'],
                'password'  => Hash::make($request['password']),
                'role_id'   => $request['role'],
            ]);
        } else {
            $user = user::where('user_id', $id)->update([
                'name'      => $request['name'],
                'email'     => $request['email'],
                'role_id'   => $request['role'],
            ]);
        }

        return $user;
    }

    public function delete($id)
    {
        User::where('user_id', $id)->delete();
    }
}
