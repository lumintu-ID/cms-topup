<?php

namespace App\Http\Controllers\cms;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "Category List";

        $data = Category::all();

        return view('cms.pages.category.index', compact('title', 'data'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $valid = Validator::make($request->all(), [
                'category' => 'required|string'
            ]);

            if ($valid->fails()) {
                return redirect()->back()->withInput()->withErrors($valid->errors());
            };

            Category::create([
                'category_id' => Str::uuid(),
                'category' => $request->category
            ]);

            $notif = array(
                'message' => 'Success Create Category',
                'alert-info' => 'success'
            );

            return redirect()->back()->with($notif);
        } catch (\Throwable $th) {
            $notif = array(
                'message' => 'Internal Server Error',
                'alert-info' => 'warning'
            );

            return redirect()->back()->with($notif);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
            $category = Category::where('category_id', $request->id);

            if (!$category->first()) {
                $notif = array(
                    'message' => 'Update Category Failed',
                    'alert-info' => 'warning'
                );

                return redirect()->back()->with($notif);
            };

            $category->update([
                'category' => $request->category
            ]);

            $notif = array(
                'message' => 'Success Update Category',
                'alert-info' => 'success'
            );

            return redirect()->back()->with($notif);
        } catch (\Throwable $th) {
            $notif = array(
                'message' => 'Internal Server Error',
                'alert-info' => 'warning'
            );

            return redirect()->back()->with($notif);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {
            $category = Category::where('category_id', $request->id);

            if (!$category->first()) {
                $notif = array(
                    'message' => 'Delete Category Failed',
                    'alert-info' => 'warning'
                );

                return redirect()->back()->with($notif);
            };

            $category->delete();

            $notif = array(
                'message' => 'Success Delete Category',
                'alert-info' => 'success'
            );

            return redirect()->back()->with($notif);
        } catch (\Throwable $th) {
            $notif = array(
                'message' => 'Internal Server Error',
                'alert-info' => 'warning'
            );

            return redirect()->back()->with($notif);
        }
    }
}
