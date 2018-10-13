<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Publisher;

class PublisherController extends Controller
{
	public function getPublisher(Request $request,$id)
	{
		$publisher = Publisher::find($id);

		return response()->json(compact('publisher'));
	}

	public function updatePublisher(Request $request)
	{
		$id = $request->input('id');

		$publisher = Publisher::find($id);
		$rules = [
			'name'         => 'required',
			'email'        => 'required|email',
			'phone_no'     => 'required',
			'location'     => 'required',

		];

		$this->validate($request, $rules);
		
		$publisher->name = $request->input('name');
		$publisher->location = $request->input('location');
		$publisher->phone_no = $request->input('phone_no');
		$publisher->email = $request->input('email');
		$publisher->status = $request->input('status');
		$publisher->save();

		return response()->json(compact('publisher'));
	}

	public function addPublisher(Request $request)
	{
		$rules = [
			'name'         => 'required',
			'email'        => 'required|email',
			'phone_no'     => 'required',
			'location'     => 'required',

		];

		$this->validate($request, $rules);

		$publisher = Publisher::create([
            'name'             => $request->input('name'),
            'email'             => $request->input('email'),
            'phone_no'             => $request->input('phone_no'),
            'location'             => $request->input('location'),
            'status'          => 1,
        ]);

        $publisher->save();

		return response()->json(compact('publisher'));
	}

	public function getAllPublisher(Request $request)
	{
		$publisher = Publisher::all();

		return response()->json(compact('publisher'));
	}
}