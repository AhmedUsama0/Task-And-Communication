<div class="flex items-center bg-grey-50 w-full max-w-[50%] rounded-lg overflow-hidden">
    <x-heroicon-o-magnifying-glass class="w-6 h-6 text-gray-500 absolute left-6" />
    <input name="search"
        type="text"
        class="form-input border-0 rounded-none bg-transparent pl-10 focus:ring-0"
        placeholder="Search for task,project or team member"
        wire:model.live.debounce.500ms="query" />
    @if($query)
    <div class="absolute top-14.25 left-0 max-w-[50%] w-full bg-white rounded-lg shadow-xl border border-gray-200 overflow-hidden"
         x-cloak
         x-show="$wire.query"
    >
        <ul class="divide-y divide-gray-100 max-h-96 overflow-y-auto">
            @forelse($this->results as $result)
            <li>
                @php
                $isUser = $result instanceof \App\Models\User;
                @endphp
                <a
                    @if (!$isUser)
                    @click="$dispatch('open-task', {
                        task: {{ $result }}, 
                        statuses: {{ $result->project->statuses?->pluck('status', 'id') }}
                    })"
                    @endif
                    test="{{!$isUser && $result->relationLoaded('project') ? 'loaded' : 'not loaded'}}"
                    class="flex gap-x-3 items-center px-4 py-3 hover:bg-blue-50 transition-colors group">
                    <div class="flex-shrink-0">
                        @if($isUser)
                        <img class="rounded-full object-cover border border-gray-100"
                            width="40" height="40"
                            src="{{ $result->image }}"
                            alt="user" />
                        @else
                        <div class="w-10 h-10 rounded bg-gray-100 flex items-center justify-center text-gray-500 group-hover:bg-blue-100 group-hover:text-blue-600 transition-colors">
                            <x-heroicon-o-clipboard-document-list class="w-6 h-6" />
                        </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <p class="text-sm font-semibold text-gray-900 truncate group-hover:text-blue-700">
                                {{ $isUser ? $result->getFullName() : $result->title }}
                            </p>
                            <span class="text-[10px] uppercase tracking-widest px-1.5 py-0.5 rounded border {{ $isUser ? 'bg-purple-50 text-purple-600 border-purple-100' : 'bg-green-50 text-green-600 border-green-100' }}">
                                {{ $isUser ? __('User') : __('Task') }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 truncate">
                            @if($isUser)
                            {{__('Team Member')}}
                            @else
                            <span class="font-medium text-gray-700">{{ $result->project->title }}</span>
                            {{ sprintf("•%s%s", __('Assigned to '), $result->assignedTo->getFullName()) }}
                            @endif
                        </p>
                    </div>
                    <x-heroicon-s-chevron-right class="w-4 h-4 text-gray-300 group-hover:text-blue-400 flex-shrink-0" />
                </a>
            </li>
            @empty
            <li class="px-4 py-6 text-center text-gray-500 text-sm">
                {{__('No results found for')}}
                <span class="font-semibold">"{{ $query }}"</span>
            </li>
            @endforelse
        </ul>
    </div>
    @endif
</div>
