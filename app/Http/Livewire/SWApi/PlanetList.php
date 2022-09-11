<?php

declare(strict_types=1);

namespace App\Http\Livewire\SWApi;

use App\Pagination\SWApi\Paginator;
use App\Services\SWApi\PlanetsServiceInterface;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class PlanetList extends Component
{
    use WithPagination;

    public string $term = '';

    /**
     * @var array<string, array<string, mixed>>
     */
    protected $queryString = [
        'term' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    private PlanetsServiceInterface $service;

    public function render(): View
    {
        $request = ['page' => Paginator::resolveCurrentPage(), 'search' => $this->term];

        $response = $this->service->search($request);

        return view('livewire.SWApi.planet-list', ['planets' => Paginator::fromResponse($response)]);
    }

    public function boot(PlanetsServiceInterface $service): void
    {
        $this->service = $service;
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }
}
