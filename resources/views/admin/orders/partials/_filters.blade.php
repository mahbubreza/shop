<form method="GET" class="p-4 bg-white rounded mb-4 shadow">
    <div class="flex gap-2 items-end">
        <div>
            <label class="block text-sm">Search</label>
            <input name="q" value="{{ request('q') }}" placeholder="ID, mobile or address" class="border px-2 py-1 rounded">
        </div>

        <div>
            <label class="block text-sm">Status</label>
            <select name="status" class="border px-2 py-1 rounded">
                <option value="">All</option>
                @foreach(['pending','processing','shipped','completed','cancelled','refunded'] as $s)
                    <option value="{{ $s }}" {{ request('status')== $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm">From</label>
            <input type="date" name="from" value="{{ request('from') }}" class="border px-2 py-1 rounded">
        </div>
        <div>
            <label class="block text-sm">To</label>
            <input type="date" name="to" value="{{ request('to') }}" class="border px-2 py-1 rounded">
        </div>

        <div class="ml-auto">
            <button class="px-3 py-2 bg-indigo-600 text-white rounded">Filter</button>
        </div>
    </div>
</form>
