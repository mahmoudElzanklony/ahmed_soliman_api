<?php

namespace App\Http\Controllers;

use App\Actions\CheckForUploadImage;
use App\Filters\EndDateFilter;
use App\Filters\NameFilter;
use App\Filters\StartDateFilter;
use App\Filters\UniversityIdFilter;
use App\Filters\UserIdFilter;
use App\Filters\VideoIdFilter;
use App\Http\Requests\categoriesFormRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\PropertyHeadingResource;
use App\Models\categories;
use App\Models\categories_properties;
use App\Models\properties;
use App\Models\properties_heading;
use App\Services\FormRequestHandleInputs;
use App\Services\Messages;
use Illuminate\Http\Request;
use App\Http\Traits\upload_image;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;

class CategoriesControllerResource extends Controller
{
    use upload_image;
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only('store','update');
    }
    public function index()
    {
        $data = categories::query()
            ->orderBy('id','DESC');

        $output = app(Pipeline::class)
            ->send($data)
            ->through([
                StartDateFilter::class,
                EndDateFilter::class,
                NameFilter::class,
            ])
            ->thenReturn()
            ->paginate(request('limit') ?? 10);

        return CategoryResource::collection($output);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function save($data , $image)
    {
        DB::beginTransaction();
        // prepare data to be created or updated

        // start save category data
        $category = categories::query()->updateOrCreate([
            'id'=>$data['id'] ?? null
        ],$data);


        DB::commit();
        // return response
        return Messages::success(__('messages.saved_successfully'),CategoryResource::make($category));
    }

    public function store(categoriesFormRequest $request)
    {

        return $this->save($request->validated(),request()->file('file'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $cat  = categories::query()->where('id', $id)->FailIfNotFound(__('errors.not_found_data'));

        return CategoryResource::collection($cat);
        ///////////////////////////////////////////// bad way
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(categoriesFormRequest $request , $id)
    {
        $data = $request->validated();
        $data['id'] = $id;
        return $this->save($data,request()->file('file'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
