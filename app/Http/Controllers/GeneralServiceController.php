<?php

namespace App\Http\Controllers;

use App\Http\ConcreateInterfaces\subjectsConcrete;
use App\Http\ConcreateInterfaces\categoriesConcrete;
use App\Http\ConcreateInterfaces\universitiesConcrete;
use App\Http\Controllers\Controller;

use App\Services\Messages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class GeneralServiceController extends Controller
{
    //
    public function delete_item(){
        $table = request('table');
        $id = request('id');
        try{

            DB::table($table)->delete($id);
            return Messages::success(trans('messages.deleted_successfully'));
        }catch (\Exception $e){
            return Messages::error($e->getMessage());
           // DB::table($table)->delete($id);
        }

    }



    public function paginate_notification_data(){
        $id = request('id') ?? 0;
        $type = request('type') ?? '';
        return pagiante_notifications::get_notifications($id,$type);
    }

    public function get_map_data_type(){
        $model =  '\\App\\Models\\'.request('type');
        $model = new $model();
        $data = $model->select('id',app()->getLocale().'_name as name')->get();
        return response()->json($data);

    }

    public function get_next_map_type(){
        $model =  '\\App\\Models\\'.request('type');
        $model = new $model();
        $data = $model->select('id',app()->getLocale().'_name as name')
            ->where(request('whereColumn'),'=',request('id'))->get();
        return response()->json($data);
    }
}
