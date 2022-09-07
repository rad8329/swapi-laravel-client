<?php

declare(strict_types=1);

namespace App\Http\Livewire\SWApi;

use App\Repositories\SWApi\PeopleRepository;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Livewire\Component;

class PersonView extends Component
{
    private PeopleRepository $repository;

    public function render(Request $request): View
    {
        return view('livewire.SWApi.person-view', [
            'person' => $this->repository->get((int) $request->id),
        ]);
    }

    public function boot(PeopleRepository $repository): void
    {
        $this->repository = $repository;
    }
}
