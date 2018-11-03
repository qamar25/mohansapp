<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Survey;
use App\SurveyPublisher;
use App\SurveyTracking;
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
		$uuid2 = Uuid::uuid1();
		$survey = Survey::create([
            'survey_name'     => $request->input('name'),
            'survey_uuid'     => $uuid1,
            'survey_cookie'   => $uuid2,
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

	public function surveyStart(Request $request,$id) {
		// survey intermediate - means we land on a survey page
		// Here we will set cookies for a survey
		$uid = $id;
		$survey = Survey::where('survey_uuid',$uid)->first();
		if(!empty($survey)) {

			$sid = $uid;
			$pid = $request->input('pid');
			//Genrate new user id;
			$userid = rand(4000000,7000000);
			$client_link = $survey->link;

			$cookie_name = $survey->survey_cookie;
			// save SID
			Cookie::queue($cookie_name.'-sid', $sid, 60);
			// save new UID
			Cookie::queue($cookie_name.'-uid', $userid, 60);
			if(! empty($pid)) {
				// save publishers id - PID
				Cookie::queue($cookie_name.'-pid', $pid, 60);
			}

			$useragent = strtolower($_SERVER['HTTP_USER_AGENT']);
			$userip = $_SERVER['REMOTE_ADDR'];
			$userreferer = '';
			if(!empty($_SERVER['HTTP_REFERER'])) {
				$userreferer = $_SERVER['HTTP_REFERER'];
			}
			$current_time = date('Y-m-d H:i:s');

			$st = SurveyTracking::create([
	            'survey_id'          => $sid,
	            'user_id'            => $userid,
	            'publisher_id'       => $pid,
	            'user_agent'         => $useragent,
	            'user_ip'            => $userip,
	            'user_referer'       => $userreferer,
	            'landing_time'       => $current_time,
	            'final_update_time'  => $current_time
	        ]);

	        $st->save();

			return Redirect::to($client_link);
		}
		
		// we will redirect on client page
		return Redirect::to('survey/track');
	}

	public function surveyTrack(Request $request,$id) {
		$uid = $id;
		$data = explode('-',$uid);
		$sid = [];
		foreach ($data as $key => $value) {
			if($key == count($data)-1) {
				$type = $value;
			} else {
				array_push($sid,$value);
			}
		}
		$sid = implode('-', $sid);
		
		$status = 1;
		if($type == 'QF') {
			$status = 3;
		}else if($type == 'TM') {
			$status = 2;
		}

		$survey = Survey::where('survey_uuid',$sid)->first();
		if(!empty($survey)) {
			$cookie_name = $survey->survey_cookie;
			$sid = Cookie::get($cookie_name.'-sid');
			$user_id = Cookie::get($cookie_name.'-uid');
			
			

			$st = SurveyTracking::where('survey_id',$sid)->where('user_id',$user_id)->first();
			
			$st->final_status = $status;
			$st->final_update_time = $current_time = date('Y-m-d H:i:s');
			$st->save();
			return 'success';
		}
		
	}
}