<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voyage;
use App\Models\Vessel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;

class VoyageController extends Controller
{
    function addVoyage(Request $req){
        try {
            $data = json_decode($req->getContent(), false);
            //return $data;
            $voyage = new Voyage;
            $startDate = new Carbon; //scope
            $endDate = new Carbon;

            $voyage->vessel_id = $data->vessel_id;
            //check if start/end dates have been provided,
            if(property_exists($data, 'start')){
                $startDate = Carbon::createFromFormat('d/m/Y',$data->start);
            }else{
                $startDate = null;
            }

            if(property_exists($data, 'end')){
                $endDate = Carbon::createFromFormat('d/m/Y',$data->end);
            }else{
                $endDate = null;
            }

            if($endDate != null){
                if($endDate->lte($startDate)){
                    return 2;
                }
            }
            //for the code i get the name form vessels table with the 
            //provided id and convert the start date to the desired format
            $voyage->code = Vessel::find($data->vessel_id)->name . '-' . $startDate->format('Y-m-d'); 
            //this will be a problem if it's null i know. 
            //Setting the !exist case to today would solve but feels wrong
            $voyage->start = $startDate;
            $voyage->end = $endDate;
            $voyage->status = 'pending';
            $voyage->revenues = $data->revenues;
            if(property_exists($data, 'expenses')){
                $voyage->expenses = $data->expenses;
            }else{
                $voyage->expenses = 0.0;
            }
            $voyage->save();
            return 0;
        } catch (Exception $e) {
            return $e;
        }
    }

    //I will assume JSON only contains changes. If not i wasted a it of space but we should still be ok
    function editVoyage(Request $req, $id){ 
        //for some reason the put request with this url http://127.0.0.1:8000/api/voyage/5 does not pass an id
        //i am out of time
        $data = json_decode($req->getContent(), false);
        $voyage = Voyage::find($id);
        $startDate = Carbon::createFromFormat('Y-m-d H:i:s',$voyage->start);
        $endDate = Carbon::createFromFormat('Y-m-d  H:i:s',$voyage->end);


        if(property_exists($data, 'vessel_id')){
            $voyage->vessel_id = $data->vessel_id;
        }
        if(property_exists($data, 'start')){
            $voyage->start = Carbon::createFromFormat('d/m/Y',$data->start);
        }
        if(property_exists($data, 'end')){
            $voyage->end = $data-> Carbon::createFromFormat('d/m/Y',$data->end);
        }
        if($endDate != null){
            if($endDate->lte($startDate)){
                return 2;
            }
        }
        if(property_exists($data, 'status')){
            switch($data->status){
                case('ongoing'):
                    $result = DB::select("SELECT * FROM voyages WHERE vessel_id = '". 
                    $voyage->vessel_id . "' AND status = 'ongoing'");
                    if($result == null || $result == []){
                        $voyage->status = 'ongoing';
                        break;
                    }
                    return 3;
                case('submitted'):

                    break;
                default:
                    //you put "potato" in the Json didn't you
                    break;
            }
        }
        if(property_exists($data, 'revenues')){
            $voyage->revenues = $data->revenues;
        }
        if(property_exists($data, 'expenses')){
            $voyage->expenses = $data->expenses;
        }

        $voyage->save();
            return 0;
        // if(property_exists($data, 'vessel_id') && property_exists($data, 'start')){
        //    
        // }
    }
}
