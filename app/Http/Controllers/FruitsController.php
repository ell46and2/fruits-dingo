<?php

namespace App\Http\Controllers;

use App\Fruit;
use App\Transformers\FruitsTransformer;
use Dingo\Api\Http\Response\errorNotFound;
use Dingo\Api\Routing\Helpers;
use App\Http\Requests;

class FruitsController extends Controller
{
	use Helpers;

    public function index()
    {
    	$fruits = Fruit::all();

    	/*
		As you see, I used Dingo Response Builder for responding with an array that contains data as a key and a list of fruits as the value with status code 200 “OK” as the second parameter.
		*/
    	// return $this->response->array(['data' => $fruits], 200);

    	return $this->collection($fruits, new FruitsTransformer);
    }

    public function show($id)
    {
    	$fruit = Fruit::where('id', $id)->first();

    	if ($fruit) {
	        return $this->item($fruit, new FruitsTransformer);
	    }

	    return $this->response->errorNotFound();
    }

    public function store(Requests\StoreFruitRequest $request)
    {
    	if (Fruit::Create($request->all())) {
	        return $this->response->created();
	    }

	    return $this->response->errorBadRequest();
    }

    public function destroy($id)
    {
    	$fruit = Fruit::find($id);

    	if($fruit) {
    		$fruit->delete();
    		return $this->response->noContent();
    	}

    	return $this->response->errorBadRequest();
    }
}
