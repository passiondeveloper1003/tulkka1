<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Translation\CategoryTranslation;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        removeContentLocale();

        $this->authorize('admin_categories_list');

        $categories = Category::where('parent_id', null)
            ->with([
                'subCategories'
            ])
            ->orderBy('id', 'desc')
            ->paginate(10);

        $data = [
            'pageTitle' => trans('admin/pages/categories.categories_list_page_title'),
            'categories' => $categories
        ];

        return view('admin.categories.lists', $data);
    }

    public function create()
    {
        $this->authorize('admin_categories_create');


        $data = [
            'pageTitle' => trans('admin/main.category_new_page_title'),
        ];

        return view('admin.categories.create', $data);
    }

    public function store(Request $request)
    {
        $this->authorize('admin_categories_create');

        $this->validate($request, [
            'title' => 'required|min:3|max:128',
            'icon' => 'required',
        ]);

        $data = $request->all();
        $category = Category::create([
            'icon' => $data['icon'],
        ]);

        CategoryTranslation::updateOrCreate([
            'category_id' => $category->id,
            'locale' => mb_strtolower($data['locale']),
        ], [
            'title' => $data['title'],
        ]);

        $hasSubCategories = (!empty($request->get('has_sub')) and $request->get('has_sub') == 'on');
        $this->setSubCategory($category, $request->get('sub_categories'), $hasSubCategories, $data['locale']);

        cache()->forget(Category::$cacheKey);

        removeContentLocale();

        return redirect('/admin/categories');
    }

    public function edit(Request $request, $id)
    {
        $this->authorize('admin_categories_edit');

        $category = Category::findOrFail($id);
        $subCategories = Category::where('parent_id', $category->id)
            ->orderBy('order', 'asc')
            ->get();

        $locale = $request->get('locale', app()->getLocale());
        storeContentLocale($locale, $category->getTable(), $category->id);

        $data = [
            'pageTitle' => trans('admin/pages/categories.edit_page_title'),
            'category' => $category,
            'subCategories' => $subCategories
        ];

        return view('admin.categories.create', $data);
    }

    public function update(Request $request, $id)
    {
        $this->authorize('admin_categories_edit');

        $this->validate($request, [
            'title' => 'required|min:3|max:128',
            'icon' => 'required',
        ]);

        $data = $request->all();

        $category = Category::findOrFail($id);
        $category->update([
            'icon' => $data['icon'],
        ]);

        CategoryTranslation::updateOrCreate([
            'category_id' => $category->id,
            'locale' => mb_strtolower($data['locale']),
        ], [
            'title' => $data['title'],
        ]);

        $hasSubCategories = (!empty($request->get('has_sub')) and $request->get('has_sub') == 'on');
        $this->setSubCategory($category, $request->get('sub_categories'), $hasSubCategories, $data['locale']);


        cache()->forget(Category::$cacheKey);

        removeContentLocale();

        return redirect('/admin/categories');
    }

    public function destroy(Request $request, $id)
    {
        $this->authorize('admin_categories_delete');

        $category = Category::where('id', $id)->first();

        if (!empty($category)) {
            Category::where('parent_id', $category->id)
                ->delete();

            $category->delete();
        }

        cache()->forget(Category::$cacheKey);

        return redirect('/admin/categories');
    }

    public function search(Request $request)
    {
        $term = $request->get('term');

        $option = $request->get('option', null);

        $query = Category::select('id')
            ->whereTranslationLike('title', "%$term%");

        /*if (!empty($option)) {

        }*/

        $categories = $query->get();

        return response()->json($categories, 200);
    }

    public function setSubCategory(Category $category, $subCategories, $hasSubCategories, $locale)
    {
        $order = 1;
        $oldIds = [];

        if ($hasSubCategories and !empty($subCategories) and count($subCategories)) {
            foreach ($subCategories as $key => $subCategory) {
                $check = Category::where('id', $key)->first();

                if (is_numeric($key)) {
                    $oldIds[] = $key;
                }

                if (!empty($subCategory['title'])) {
                    if (!empty($check)) {
                        $check->update([
                            'order' => $order,
                            'icon' => $subCategory['icon'] ?? null,
                        ]);

                        CategoryTranslation::updateOrCreate([
                            'category_id' => $check->id,
                            'locale' => mb_strtolower($locale),
                        ], [
                            'title' => $subCategory['title'],
                        ]);
                    } else {
                        $new = Category::create([
                            'parent_id' => $category->id,
                            'icon' => $subCategory['icon'] ?? null,
                            'order' => $order,
                        ]);

                        CategoryTranslation::updateOrCreate([
                            'category_id' => $new->id,
                            'locale' => mb_strtolower($locale),
                        ], [
                            'title' => $subCategory['title'],
                        ]);

                        $oldIds[] = $new->id;
                    }

                    $order += 1;
                }
            }
        }

        Category::where('parent_id', $category->id)
            ->whereNotIn('id', $oldIds)
            ->delete();

        return true;
    }
}
