<?php

namespace App\Repository\Category;

use App\Models\Category;
use Illuminate\Support\Str;
use App\Repository\Category\CategoryRepository;

class CategoryImplement implements CategoryRepository
{
    public function getAll()
    {
        $data =  Category::all();

        return $data;
    }

    public function create($request)
    {
        $data =  Category::create([
            'category_id' => Str::uuid(),
            'category' => $request['category']
        ]);

        return $data;
    }

    public function getId($id)
    {
        $data = Category::where('category_id', $id)->first();

        return $data;
    }

    public function update($id, $request)
    {
        $data = Category::where('category_id', $id)->update([
            'category' => $request['category']
        ]);

        return $data;
    }

    public function delete($id)
    {
        $data = Category::where('category_id', $id)->delete();

        return $data;
    }
}
