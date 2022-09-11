<?php

use App\Http\Livewire\SWApi\PlanetList;
use App\Services\SWApi\PeopleServiceInterface;
use App\Services\SWApi\PlanetsServiceInterface;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

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

Route::get('/planet/{id}', static function (int $id, PlanetsServiceInterface $service): View {
    return view('SWApi.planet-resident-list', ['planet' => $service->getById($id)]);
})->where('id', '[0-9]+');

Route::get('/people/{id}', static function (int $id, PeopleServiceInterface $service): View {
    return view('SWApi.person-view', ['person' => $service->getById($id)]);
})->where('id', '[0-9]+');

