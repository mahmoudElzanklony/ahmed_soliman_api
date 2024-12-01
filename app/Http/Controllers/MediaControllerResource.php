<?php

namespace App\Http\Controllers;

use App\Actions\CheckForUploadImage;
use App\Actions\FileModalSave;
use App\Filters\CategoryIdFilter;
use App\Filters\EndDateFilter;
use App\Filters\NameFilter;
use App\Filters\StartDateFilter;
use App\Filters\UniversityIdFilter;
use App\Filters\UserIdFilter;
use App\Filters\VideoIdFilter;
use App\Http\Requests\categoriesFormRequest;
use App\Http\Requests\mediaFormRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\MediaResource;
use App\Http\Resources\PropertyHeadingResource;
use App\Models\categories;
use App\Models\categories_properties;
use App\Models\files;
use App\Models\media;
use App\Models\properties;
use App\Models\properties_heading;
use App\Services\FormRequestHandleInputs;
use App\Services\Messages;
use Illuminate\Http\Request;
use App\Http\Traits\upload_image;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;

class MediaControllerResource extends Controller
{
    use upload_image;
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except('index','show');
    }
    public function index()
    {
        $data = media::query()
            ->orderBy('id','DESC')
            ->with('file')
            ->when(request()->filled('type'),fn($q) => $q->whereHas('file',fn($a) => $a->where('type','=',request('type'))));
        $output = app(Pipeline::class)
            ->send($data)
            ->through([
                StartDateFilter::class,
                EndDateFilter::class,
                NameFilter::class,
                CategoryIdFilter::class
            ])
            ->thenReturn()
            ->paginate(request('limit') ?? 10);

        return MediaResource::collection($output);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function save($data , $file)
    {
        DB::beginTransaction();
        // prepare data to be created or updated
        $data['user_id'] = auth()->id();
        // start save category data
        $media = media::query()->updateOrCreate([
            'id'=>$data['id'] ?? null
        ],$data);
        if($file){
            $file_name = $this->upload_file($file);
            files::query()
                 ->where('fileable_id','=',$media->id)
                 ->delete();
            FileModalSave::make($media->id,'media',$file_name,$data['file_type']);
        }


        $media->load('file');

        DB::commit();
        // return response
        return Messages::success(__('messages.saved_successfully'),MediaResource::make($media));
    }

    public function store(mediaFormRequest $request)
    {
        return $this->save($request->validated(),request()->file('file_name'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $media  = media::query()->where('id', $id)->with('file')->FailIfNotFound(__('errors.not_found_data'));

        return MediaResource::collection($media);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(mediaFormRequest $request , $id)
    {
        $data = $request->validated();
        $data['id'] = $id;
        return $this->save($data,request()->file('file_name'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
