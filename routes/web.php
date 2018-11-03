<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::any('survey/click','SurveyController@surveyStart');
Route::any('survey/track','SurveyController@surveyTrack');

Route::any('{all}', function () {
    return view('app');
})->where(['all' => '.*']);
