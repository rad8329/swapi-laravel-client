<?php

declare(strict_types=1);

namespace App\Http\Livewire\SWApi;

use App\Repositories\SWApi\PlanetsRepository;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Livewire\Component;

class PlanetResidentsList extends Component
{
    private PlanetsRepository $repository;

    public function render(Request $request): View
    {
        return view('livewire.SWApi.planet-resident-list', [
            'planet' => $this->repository->get((int) $request->id),
        ]);
    }

    public function boot(PlanetsRepository $repository): void
    {
        $this->repository = $repository;
    }
}
