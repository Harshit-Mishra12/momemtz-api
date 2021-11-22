<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Interest_categories;



class CategoriesController extends Controller
{
    public function actionInterestCategory()
    {
         $data = Interest_categories::select("categoryId","name","iconUrl",)
        ->get();

        if(!empty($data ))
        {
            return response()->json(['statusCode'=>'200','data'=>$data], 200); 
        }
        else{
            return response()->json(['statusCode'=>'400','message'=>"No data is returned"], 400); 
        }

       
    }
}
