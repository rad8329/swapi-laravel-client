<div class="flex justify-center">
    <div class="p-4 w-full max-w-4xl bg-white rounded-lg border shadow-md sm:p-8 dark:bg-gray-800 dark:border-gray-700">
        <div class="flex justify-between items-center mb-4">
            <h5 class="text-xl font-bold leading-none text-gray-900 dark:text-white">Planets</h5>
            <div class="pt-2 relative text-gray-600">
                <input class="border-2 border-gray-300 bg-white h-10 px-5 rounded-lg text-sm focus:outline-none"
                       type="search" placeholder="Search a planet" wire:model.debounce.500ms="term"/>
            </div>
        </div>
        <div wire:loading>Searching planets...</div>
        <div wire:loading.remove>
            @if(!$planets->isEmpty())
                <div class="flow-root">
                    <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($planets as $planet)
                            <li class="py-3 sm:py-4">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                            {{ $planet->name }}
                                        </p>
                                        <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                            {{ $planet->terrain }}
                                        </p>
                                    </div>
                                    <div
                                        class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                        <a href="{{ url("/planet/{$planet->id}") }}"
                                           class="text-sm font-medium text-blue-600 hover:underline dark:text-blue-500">
                                            {{ $planet->population }}
                                        </a>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    @if($planets->count() > 1)
                        {{ $planets->links() }}
                    @endif
                </div>
            @else
                <div class="bg-orange-100 border-l-4 border-orange-500 text-orange-700 p-4" role="alert">
                    <p>It seems your query terms are wrong, or that planet is not discovered yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>
