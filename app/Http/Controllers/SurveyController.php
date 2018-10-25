<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Survey;
use App\SurveyPublisher;
use Ramsey\Uuid\Uuid;

class ClientController extends Controller
{
	public function getSurvey(Request $request,$id)
	{
		$survey = Survey::find($id);

		return response()->json(compact('survey'));
	}

	public function updateClient(Request $request)
	{
		$id = $request->input('id');
		
		$client = Client::find($id);
		$rules = [
			'name'         => 'required',
			'email'        => 'required|email',
			'phone_no'     => 'required',
			'location'     => 'required',

		];

		$this->validate($request, $rules);
		
		$client->name = $request->input('name');
		$client->location = $request->input('location');
		$client->phone_no = $request->input('phone_no');
		$client->email = $request->input('email');
		$client->status = $request->input('status');
		$client->save();

		return response()->json(compact('client'));
	}

	public function addSurvey(Request $request)
	{
		$rules = [
			'survey_name'  => 'required',
			'client_id'    => 'required|email'
		];

		$this->validate($request, $rules);


		echo $uuid1 = Uuid::uuid1();
		print_r($request->input('publishers'));
		die;
		$survey = Survey::create([
            'survey_name'     => $request->input('survey_name'),
            'survey_uuid'     => $uuid1,
            'client_id'       => $request->input('client_id'),
            'status'          => 1,
        ]);

        $survey->save();



		return response()->json(compact('survey'));
	}

	public function getAllClient(Request $request)
	{
		$client = Client::all();

		return response()->json(compact('client'));
	}
}