<?php

use App\Http\Livewire\SWApi\PlanetList;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\SWApi\PlanetResidentsList;

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
Route::get('/planet/{id}', PlanetResidentsList::class)->where('id', '[0-9]+');

