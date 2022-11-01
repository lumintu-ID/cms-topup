<?php

namespace App\Repository\Country;

use App\Models\navigation;
use Illuminate\Support\Str;
use App\Repository\Navigation\NavigationRepository;

class NavigationImplement implements NavigationRepository
{
    public function getAll()
    {
        $data = navigation::orderBy('id_label', 'asc')->get();

        return $data;
    }

    public function create($request)
    {
        $data = navigation::create([
            'nav_id'        => Str::uuid(),
            'id_label'      => $request['label'],
            'url'           => $request['url'],
            'navigation'    => $request['name'],
            'icon'          => $request['icon'],
            'is_active'     => 1
        ]);

        return $data;
    }


    public function getId($id)
    {
        $data = navigation::where('nav_id', $id)->first();
        return $data;
    }

    public function update($id, $request)
    {
        $data = navigation::where('nav_id', $id)->update([
            'id_label'      => $request['label'],
            'url'           => $request['url'],
            'navigation'    => $request['name'],
            'icon'          => $request['icon'],
        ]);

        return $data;
    }

    public function delete($id)
    {
        $data = navigation::where('nav_id', $id)->delete();

        return $data;
    }

    public function updateStatus($id, $request)
    {
        $data = navigation::where('nav_id', $id)->update([
            'is_active' => ($request->is_active == 0) ? 1 : 0,
        ]);

        return $data;
    }
}
