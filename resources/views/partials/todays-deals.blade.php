<div class="mt-10 bg-white dark:bg-gray-800 rounded-lg shadow p-5 relative overflow-hidden transition-colors">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-bold text-red-600 flex items-center gap-2">
            <i class="fa fa-fire text-red-500"></i> Today's Deals
        </h2>
        <div class="flex items-center text-sm text-gray-700 dark:text-gray-200">
            <i class="fa fa-clock mr-2"></i>
            <span id="dealCountdown">00:00:00</span>
        </div>
    </div>

    <div class="relative">
        <div id="dealSlider" class="flex gap-4 overflow-x-auto scroll-smooth snap-x snap-mandatory">
            @foreach($todaysDeals as $deal)
            <div class="min-w-[180px] md:min-w-[220px] snap-center bg-gray-50 dark:bg-gray-700 rounded-lg shadow-sm hover:shadow-md transition cursor-pointer flex-shrink-0">
                <div class="relative">
                    <img src="{{ asset('storage/'.$deal->image) }}" alt="{{ $deal->name }}" class="w-full h-40 object-cover rounded-t-lg">
                    <span class="absolute top-2 left-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">
                        {{ round((1 - $deal->price / $deal->old_price) * 100) }}% OFF
                    </span>
                </div>
                <div class="p-3 text-center">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200 truncate">{{ $deal->name }}</h3>
                    <div class="mt-1">
                        <span class="text-red-600 font-bold">${{ $deal->price }}</span>
                        <span class="text-gray-400 dark:text-gray-300 line-through text-xs ml-1">${{ $deal->old_price }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <button id="dealPrev" class="absolute left-0 top-1/2 -translate-y-1/2 bg-white dark:bg-gray-700 shadow-lg rounded-full p-2 text-gray-700 dark:text-gray-200 hover:text-blue-600 hidden md:block">
            <i class="fa fa-chevron-left"></i>
        </button>
        <button id="dealNext" class="absolute right-0 top-1/2 -translate-y-1/2 bg-white dark:bg-gray-700 shadow-lg rounded-full p-2 text-gray-700 dark:text-gray-200 hover:text-blue-600 hidden md:block">
            <i class="fa fa-chevron-right"></i>
        </button>
    </div>
</div>
