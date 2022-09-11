<?php

use App\Http\Livewire\SWApi\PlanetList;
use App\Repositories\SWApi\PeopleRestRepository;
use App\Repositories\SWApi\PlanetsRepository;
use Illuminate\Support\Facades\Route;

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

Route::get('/', PlanetList::class);

Route::get('/planet/{id}', static function (int $id, PlanetsRepository $repository) {
    return view('SWApi.planet-resident-list', ['planet' => $repository->get($id)]);
})->where('id', '[0-9]+');

Route::get('/people/{id}', static function (int $id, PeopleRestRepository $repository) {
    return view('SWApi.person-view', ['person' => $repository->get($id)]);
})->where('id', '[0-9]+');

