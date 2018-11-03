<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Survey;
use App\SurveyPublisher;
use Ramsey\Uuid\Uuid;
use Cookie;
use Illuminate\Support\Facades\Redirect;

class SurveyController extends Controller
{
	public function getSurvey(Request $request,$id)
	{
		$survey = Survey::with('client')->find($id);
		//with('survey')->with('publisher')->
		$publishers = SurveyPublisher::where('survey_id',$id)->get();
		
		return response()->json(['survey' => compact('survey'), 'publishers' => $publishers] );
	}

	public function updateSurvey(Request $request)
	{
		$id = $request->input('id');
		
		$survey = Survey::find($id);
		$rules = [
			'name'  => 'required',
			'client'    => 'required',
			'link'    => 'required'
		];

		$this->validate($request, $rules);
		
		$survey->survey_name = $request->input('name');
		$survey->client_id = $request->input('client');
		$survey->link = $request->input('link');
		$survey->status = 1;//$request->input('status');
		$survey->save();

		SurveyPublisher::where('survey_id',$id)->delete();
		$publishers = $request->input('publishers');
        foreach ($publishers as $publisher) {
        	$sp = SurveyPublisher::create([
        		'survey_id'     => $survey->id,
		        'publisher_id'  => $publisher['name'],
		        'link1'         => $publisher['link1'],
		        'link2'         => $publisher['link2'],
		        'link3'         => $publisher['link3'],
		        'status'        => 1,
        	]);
        	$sp->save();
        }
		return response()->json(compact('survey'));
	}

	public function addSurvey(Request $request)
	{
		$rules = [
			'name'  => 'required',
			'client'    => 'required',
			'link'    => 'required'
		];

		$this->validate($request, $rules);


		$uuid1 = Uuid::uuid1();
		
		$survey = Survey::create([
            'survey_name'     => $request->input('name'),
            'survey_uuid'     => $uuid1,
            'client_id'       => $request->input('client'),
            'link'       => $request->input('link'),
            'status'          => 1,
        ]);

        $survey->save();

        $publishers = $request->input('publishers');
        foreach ($publishers as $publisher) {
        	$sp = SurveyPublisher::create([
        		'survey_id'     => $survey->id,
		        'publisher_id'  => $publisher['name'],
		        'link1'         => $publisher['link1'],
		        'link2'         => $publisher['link2'],
		        'link3'         => $publisher['link3'],
		        'status'        => 1,
        	]);
        	$sp->save();
        }
	
		return response()->json(compact('survey'));
	}

	public function getAllSurvey(Request $request)
	{
		$survey = Survey::with('client')->get();

		return response()->json(compact('survey'));
	}

	public function surveyStart(Request $request) {
		// echo "Hello";

		Cookie::queue('name', 'mohan', 60);
		Cookie::queue('uid', 'mohan', 60);
		
		return Redirect::to('survey/track');
	}

	public function surveyTrack(Request $request) {
		// echo "Hello";

		
		// Cookie::make('name', 'Mohan', 360);

		echo $value = Cookie::get('name');
		echo $value = Cookie::get('uid');
		echo $value;
	}
}