<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteCategoryRequest;
use App\Http\Requests\StoreCategoryRequest;
use App\Services\Interfaces\ICategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class CategoryController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware(middleware: 'auth:api', except: ['index']),
        ];
    }

    public function __construct(
        private ICategoryService $categoryService
    ) {}

    /**
     * Returns a list of the categories OR return a specific category by ID.
     *
     * @param  Illuminate\Http\Request $request
     * @param  int $id (optional) - If we want to get a specific category by ID.
     * @return Response
     */
    public function index(Request $request, int $id = null)
    {
        try {
            $ret = null;
            $message = "";

            if (!empty($id)) {
                $ret = $this->categoryService->getCategoryById($id);

                if (empty($ret)) {
                    throw new HttpException(404, "The category with the specified ID was not found.");
                }
                $message = "The category fetched successfully";
            } else {
                $ret = $this->categoryService->getCategories();
                $message = "The categories fetched successfully";
            }

            return response()->json([
                "message" => $message,
                "categories" => $ret
            ], 200);
        } catch (\InvalidArgumentException $e) {

            Log::error($e->getMessage());
            return response()->json(['message' => "The processing of the arguments was unsuccessful."], 500);
        } catch (HttpException $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        } catch (\Exception $e) {

            Log::error($e->getMessage());
            return response()->json(['message' => "An error occurred while processing the request."], 500);
        }
    }

    /**
     * Store a newly created category in storage.
     *
     * @param  StoreCategoryRequest $request
     * @return Response
     */
    public function store(StoreCategoryRequest $request)
    {
        try {

            $data = $request->validated();

            $ret = $this->categoryService->createCategory($data);

            return response()->json([
                "message" => "The category created successfully",
                "inserted_category" => $ret
            ], 201);
        } catch (\InvalidArgumentException $e) {

            Log::error($e->getMessage());
            return response()->json(['message' => "The processing of the arguments was unsuccessful."], 500);
        } catch (HttpException $e) {

            Log::error($e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        } catch (\Exception $e) {

            Log::error($e->getMessage());
            return response()->json(['message' => "An error occurred while processing the request."], 500);
        }
    }

    /**
     * Delete the specified category from storage.
     *
     * @param  DeleteCategoryRequest $request
     * @return Response
     */
    public function destroy(DeleteCategoryRequest $request)
    {
        try {

            $data = $request->validated();

            $ret = $this->categoryService->deleteCategory($data['id'], $data['title']);

            return response()->json([
                "message" => "The category deleted successfully",
                "deleted_category" => $ret
            ], 200);
        } catch (\InvalidArgumentException $e) {

            Log::error($e->getMessage());
            return response()->json(['message' => "The processing of the arguments was unsuccessful."], 500);
        } catch (HttpException $e) {

            Log::error($e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        } catch (\Exception $e) {

            Log::error($e->getMessage());
            return response()->json(['message' => "An error occurred while processing the request."], 500);
        }
    }
}
