<?php

declare(strict_types=1);

namespace App\Http\Livewire\SWApi;

use App\Repositories\SWApi\PlanetsRestRepository;
use Illuminate\Http\Request;
use Illuminate\View\View;
use JsonException;
use Livewire\Component;

class PlanetResidentsList extends Component
{
    private PlanetsRestRepository $repository;

    /**
     * @throws JsonException when the query was not able to be serialized
     */
    public function render(Request $request): View
    {
        return view('livewire.SWApi.planet-resident-list', [
            'planet' => $this->repository->get((int) $request->id),
        ]);
    }

    public function boot(PlanetsRestRepository $repository): void
    {
        $this->repository = $repository;
    }
}
