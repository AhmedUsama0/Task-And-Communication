<div x-data="{ isLoading: false }" @tasks-loading.window="isLoading = $event.detail">
    <div x-transition x-show="!isLoading" class="grid grid-cols-3 gap-4">
        @foreach ($tasks as $task)
             @livewire('task', ['task' => $task], key($task->id))
        @endforeach
    </div>
   <div x-transition x-show="isLoading" class="grid grid-cols-3 gap-4" style="display: none;">
        @for ($i = 0; $i < 6; $i++)
            <x-skeleton />
        @endfor
   </div>
</div>