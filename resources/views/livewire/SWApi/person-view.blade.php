@if(!empty($person->name))
    <div class="flex justify-center">
        <div
            class="p-4 w-full max-w-4xl bg-white rounded-lg border shadow-md sm:p-8 dark:bg-gray-800 dark:border-gray-700">
            <div class="flex justify-between items-center mb-4">
                <h5 class="text-xl font-bold leading-none text-gray-900 dark:text-white">{{ $person->name }}</h5>
                <div class="pt-2 relative text-gray-600">
                    <a class="text-sm font-medium text-blue-600 hover:underline dark:text-blue-500"
                       onclick="history.back()">Back</a>
                </div>
            </div>
            <div
                class="space-y-3 sm:space-y-2 font-open-sans text-sm lg:text-xs xl:text-sm mb-7 sm:mb-0 leading-tight overflow-hidden">
                <div class="flex items-center">
                    <div class="mr-3 w-15"><span class="">Height</span></div>
                    <div class="text-gray-600">{{ $person->height }}</div>
                </div>
                <div class="flex items-center">
                    <div class="mr-3 w-15"><span class="">Mass</span></div>
                    <div class="text-gray-600">{{ $person->mass }}</div>
                </div>
                <div class="flex items-center">
                    <div class="mr-3 w-15"><span class="">Gender</span></div>
                    <div class="text-gray-600">{{ $person->gender }}</div>
                </div>
                <div class="flex items-center">
                    <div class="mr-3 w-15"><span class="">Eye color</span></div>
                    <div class="text-gray-600">{{ $person->eyeColor }}</div>
                </div>
                <div class="flex items-center">
                    <div class="mr-3 w-15"><span class="">Skin color</span></div>
                    <div class="text-gray-600">{{ $person->skinColor }}</div>
                </div>
                <div class="flex items-center">
                    <div class="mr-3 w-15"><span class="">Birth year</span></div>
                    <div class="text-gray-600">{{ $person->birthYear }}</div>
                </div>
            </div>
            @if(!$person->films->isEmpty())
                <div class="flow-root">
                    <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($person->films as $film => $url)
                            <li class="py-3 sm:py-4">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                            Film {{ $film + 1 }}
                                        </p>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @else
                <div class="bg-orange-100 border-l-4 border-orange-500 text-orange-700 p-4" role="alert">
                    <p>It seems your query terms are wrong, or that person is not there.</p>
                </div>
            @endif
        </div>
    </div>
@else
    <div class="bg-orange-100 border-l-4 border-orange-500 text-orange-700 p-4" role="alert">
        <p>It seems your query terms are wrong, or that person is not there.</p>
    </div>
@endif
