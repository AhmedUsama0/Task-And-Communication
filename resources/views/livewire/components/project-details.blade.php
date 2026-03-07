<div class="p-4 h-full">
    {{-- Breadcrumbs --}}
    <h1>{{$this->project->title}}</h1>
    <div class="project-main-details mt-5 flex flex-col gap-y-5">
        <div class="flex items-center gap-x-10">
            <div class="flex items-center gap-x-2 text-gray-400 min-w-28">
                <x-heroicon-o-eye class="w-5 h-5" />
                <span>{{__('Visibility')}}</span>
            </div>
            <div class="flex items-center rounded-lg p-2 gap-x-2 bg-red text-red-500">
                @if ($this->project->is_private) 
                    <x-heroicon-o-lock-closed class="w-5 h-5 text-red-500" />
                    <span class="font-medium">{{__('Private Board')}}</span>
                @endif
            </div>
        </div>
        <div class="flex items-center gap-x-10">
            <div class="flex items-center gap-x-2 text-gray-400 min-w-28">
                <x-heroicon-o-user class="w-5 h-5" />
                <span>{{__('Assigned to')}}</span>
            </div>
            <div class="flex items-center gap-x-3">
                @foreach ($this->teamMembers as $member)
                    <x-team-member :member="$member"/>
                @endforeach
            </div>
        </div>
        <div class="flex items-center gap-x-10">
            <div class="flex items-center gap-x-2 text-gray-400 min-w-28">
                <x-heroicon-o-calendar class="w-5 h-5" />
                <span>{{__('Deadline')}}</span>
            </div>
            <span class="text-sm font-medium">{{$this->project->deadline}}</span>
        </div>
    </div>

    <div class="grid gap-x-2 mt-6" style="grid-template-columns: repeat({{$this->phases->count()}}, 1fr)">
        @foreach ($this->phases as $phase)
            <div wire:key="{{$phase->id}}" class="px-4 py-3 rounded-lg font-medium text-white shadow-sm" style="background-color: {{$phase->color}}">
                {{ $phase->status }}
            </div>
        @endforeach
    </div>
    <div class="overflow-y-auto max-h-125 mt-6">
        @foreach ($this->sprints as $sprint)
            <div wire:key="{{$sprint->id}}" x-data="{ collapsed: false }" class="mb-6">
                <div @click="collapsed = !collapsed" class="px-4 py-3 bg-primary text-white rounded-lg font-medium shadow-sm mb-4 cursor-pointer hover:bg-blue-600 transition-colors flex items-center justify-between">
                    <span>{{ $sprint->name }}</span>
                    <span class="transition-transform duration-200" :class="{ 'rotate-180': collapsed }">
                        <x-heroicon-o-chevron-down class="w-5 h-5" />
                    </span>
                </div>
                <div x-show="!collapsed" 
                     x-transition:enter="transition ease-out duration-200" 
                     x-transition:enter-start="opacity-0" 
                     x-transition:enter-end="opacity-100" 
                     x-transition:leave="transition ease-in duration-150" 
                     x-transition:leave-start="opacity-100" 
                     x-transition:leave-end="opacity-0"
                     class="grid gap-x-2" 
                     style="grid-template-columns: repeat({{ $this->phases->count() }}, 1fr)">
                    @foreach ($this->phases as $phase)
                        <div wire:key="{{$sprint->id}}-{{$phase->id}}" class="flex flex-col gap-y-3">
                            @php
                                $tasks = $sprint->tasks[$phase->status] ?? [];
                            @endphp
                            @foreach ($tasks as $task)
                                <div wire:key="{{$phase->id}}-{{$task->id}}" 
                                     class="cursor-pointer px-4 py-3 rounded-lg border border-grey-200 bg-white
                                          hover:bg-grey-50 hover:shadow-md transition-all shadow-sm"
                                     @click="$dispatch('open-task', {
                                        task: {{$task}}, 
                                        statuses: {{ $this->phases->pluck('status', 'id') }}
                                     })"
                                >
                                    <div class="flex justify-between items-center mb-2">
                                        @php
                                            $taskPriority = $task->priority;

                                            [$background,$textIcon] = match ($taskPriority->value) {
                                                'low' => ['bg-green','text-green-darker'],
                                                'medium' => ['bg-yellow','text-yellow-darker'],
                                                'high', 'critical' => ['bg-red','text-red-darker'],
                                                default =>  ['bg-green','text-green-darker'],
                                            };
                                        @endphp
                                        <div class="flex items-center gap-x-2 p-1.5 rounded-lg {{$background}}">
                                             <x-heroicon-o-cog-6-tooth class="w-5 h-5 {{$textIcon}}"/>
                                             <span class="{{$textIcon}}">{{ $taskPriority->label() }}</span>
                                        </div>
                                    </div>
                                    <h3 class="text-primary">{{ $task->title }}</h3>
                                    <p class="text-gray-400 font-semibold line-clamp-1"> {{ $task->description }}</p>
                                    <div class="flex items-center justify-between mt-3">
                                        <div class="flex items-center gap-x-3">
                                            <img loading="lazy" class="rounded-full w-10 h-10" src="{{$task->assignedTo->image}}">
                                        </div>
                                        <div class="flex items-center gap-x-3">
                                            <x-heroicon-o-chat-bubble-oval-left class="w-5 h-5 text-gray-500"/>
                                            <x-heroicon-o-chat-bubble-oval-left class="w-5 h-5 text-gray-500"/>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
