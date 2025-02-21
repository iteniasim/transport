<div class="mb-2 text-xl font-bold tracking-tight text-gray-900 dark:text-white">
    {{ $task->name }}
</div>
<div>
    <div class="px-3 text-sm text-gray-500">
        <span class="font-semibold">Load Type:</span>
        <span>{{ $task->load_type }}</span>
    </div>
    <div class="px-3 text-sm text-gray-500">
        <span class="font-semibold">From:</span>
        <span>{{ $task->from }}</span>
    </div>
    <div class="px-3 text-sm text-gray-500">
        <span class="font-semibold">To:</span>
        <span>{{ $task->to }}</span>
    </div>
    <div class="px-3 text-sm text-gray-500">
        <span class="font-semibold">Weight:</span>
        <span>{{ $task->weight }}</span>
    </div>
    <div class="px-3 text-sm text-gray-500">
        <span class="font-semibold">Cost:</span>
        <span>{{ $task->cost }}</span>
    </div>
</div>
