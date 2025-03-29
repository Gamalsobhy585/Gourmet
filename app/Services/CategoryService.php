<?php
namespace App\Services;
use App\Services\Interface\ICategoryService;
use App\Repositories\Interface\ICategory;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\CategoryResource;
use Illuminate\Support\Facades\Cache;
class CategoryService implements ICategoryService
{
    use ResponseTrait;
    private ICategory $CategoryRepo;
    private const ALL_CATEGORIES_CACHE_KEY = 'all_categories';
    private const SINGLE_CATEGORY_CACHE_KEY = 'category_';
    public function __construct(ICategory $CategoryRepo)
    {
        $this->CategoryRepo = $CategoryRepo;
    }
    public function getCategories($request)
    {
        $cacheKey = self::ALL_CATEGORIES_CACHE_KEY;
        $categories = Cache::get($cacheKey);
        if (!$categories) {
            $categories = $this->CategoryRepo->get();
            Cache::forever($cacheKey, $categories);
        }
        return $this->returnData(
            __('messages.category.index_success'),
            200,
            CategoryResource::collection($categories)
        );
    }
    public function store($request)
    {
        Log::info('Category Creation Request', $request->all());
        try {
            $categoryData = [
                'name' => $request->input('name'),
                'B_percentage' => $request->input('B_percentage'),
            ];
            $category = $this->CategoryRepo->save($categoryData);
            Cache::forget(self::ALL_CATEGORIES_CACHE_KEY);
            return $this->success(__('messages.category.create_success'), 201, [
                'category' => new CategoryResource($category)
            ]);
        } catch (\Exception $e) {
            Log::error('Category Create Error', ['error' => $e->getMessage()]);
            return $this->returnError(__('messages.category.create_failed'), 500);
        }
    }
    public function update($request, $id)
    {
        $existingCategory = $this->CategoryRepo->findById($id);
        try {
            Log::info('Update Request Data:', $request->all());
            $updateData = [
                'name' => $request->input('name', $existingCategory->name),
                'B_percentage' => $request->input('B_percentage', $existingCategory->B_percentage),
            ];
            $updatedCategory = $this->CategoryRepo->update($existingCategory, $updateData);
            Cache::forget(self::ALL_CATEGORIES_CACHE_KEY);
            Cache::forget(self::SINGLE_CATEGORY_CACHE_KEY . $id);
            return $this->success(__('messages.category.update_success'), 200, [
                'category' => new CategoryResource($updatedCategory)
            ]);
        } catch (\Exception $e) {
            Log::error('Category Update Error', ['error' => $e->getMessage()]);
            return $this->returnError(__('messages.category.update_failed'), 500);
        }
    }
    public function delete($model)
    {
        try {
            $this->CategoryRepo->delete($model);
            Cache::forget(self::ALL_CATEGORIES_CACHE_KEY);
            Cache::forget(self::SINGLE_CATEGORY_CACHE_KEY . $model->id);
            return $this->success(__('messages.category.delete_success'), 204);
        } catch (\Exception $e) {
            Log::error('Category Delete Error', ['error' => $e->getMessage()]);
            return $this->returnError(__('messages.category.delete_failed'), 500);
        }
    }
    public function getCategoryById($id)
    {
        $cacheKey = self::SINGLE_CATEGORY_CACHE_KEY . $id;
        $category = Cache::get($cacheKey);
        if (!$category) {
            $category = $this->CategoryRepo->findById($id);
            if ($category) {
                Cache::forever($cacheKey, $category);
            }
        }
        return $category ? new CategoryResource($category) : null;
    }
}