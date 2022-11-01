<?php

namespace App\Http\Controllers\cms;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Repository\Category\CategoryImplement;

class CategoryController extends Controller
{

    protected $categoryImplement;

    public function __construct(CategoryImplement $categoryImplement)
    {
        $this->categoryImplement = $categoryImplement;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "Category List";

        $data = $this->categoryImplement->getAll();

        return view('cms.pages.category.index', compact('title', 'data'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        try {

            $this->categoryImplement->create($request);

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
    public function update(CategoryRequest $request)
    {
        try {
            $category = $this->categoryImplement->getId($request->id);

            if (!$category) {
                $notif = array(
                    'message' => 'Update Category Failed',
                    'alert-info' => 'warning'
                );

                return redirect()->back()->with($notif);
            };

            $this->categoryImplement->update($request->id, $request);

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
            $category = $this->categoryImplement->getId($request->id);

            if (!$category) {
                $notif = array(
                    'message' => 'Delete Category Failed',
                    'alert-info' => 'warning'
                );

                return redirect()->back()->with($notif);
            };

            $this->categoryImplement->delete($request->id);

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
