<?php

declare(strict_types=1);

namespace App\Http\Livewire\SWApi;

use App\Pagination\SWApi\Paginator;
use App\Repositories\SWApi\PlanetsRepository;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class PlanetList extends Component
{
    use WithPagination;
    public string $term = '';

    private PlanetsRepository $repository;

    public function render(): View
    {
        $request = ['page' => Paginator::resolveCurrentPage(), 'search' => $this->term];

        $response = $this->repository->search($request);

        return view('livewire.SWApi.planet-list', [
            'planets' => Paginator::fromResponse($response),
            'search'  => $request['search'],
        ]);
    }

    public function boot(PlanetsRepository $repository): void
    {
        $this->repository = $repository;
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }
}
