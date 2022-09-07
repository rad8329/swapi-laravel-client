@if(!empty($planet->name))
    <div class="flex justify-center">
        <div class="p-4 w-full max-w-4xl bg-white rounded-lg border shadow-md sm:p-8 dark:bg-gray-800 dark:border-gray-700">
            <div class="flex justify-between items-center mb-4">
                <h5 class="text-xl font-bold leading-none text-gray-900 dark:text-white"> {{ $planet->name }}</h5>
                <div class="pt-2 relative text-gray-600">
                    <a class="text-sm font-medium text-blue-600 hover:underline dark:text-blue-500" onclick="history.back()">Back</a>
                </div>
            </div>
                @if(!$planet->residents->isEmpty())
                    <div class="flow-root">
                        <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($planet->residents as $resident => $url)
                                <li class="py-3 sm:py-4">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                                Resident {{ $resident + 1 }}
                                            </p>
                                        </div>
                                        <div
                                            class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                            <a href="{{ url('/people/'.\App\Helpers\SWApi\Helper::extractPeopleIdFromUrl($url)) }}"
                                               class="text-sm font-medium text-blue-600 hover:underline dark:text-blue-500">
                                                See it
                                            </a>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <div class="bg-orange-100 border-l-4 border-orange-500 text-orange-700 p-4" role="alert">
                        <p>It seems this planet has no residents.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@else
    <div class="bg-orange-100 border-l-4 border-orange-500 text-orange-700 p-4" role="alert">
        <p>It seems your query terms are wrong, or that planet is not discovered yet.</p>
    </div>
@endif
