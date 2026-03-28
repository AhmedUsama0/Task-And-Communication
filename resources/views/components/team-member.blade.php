<div wire:key="{{$member->id}}"
    x-data="{isOpen: false}"
    @click.outside="isOpen = false"
    class="relative flex-shrink-0"
>
    <div @click="isOpen = !isOpen"
        class="flex items-center bg-grey-50 rounded-full py-2 px-1 
                                    border border-gray-200 cursor-pointer hover:bg-grey-100 transition-colors">
        <img class="absolute top-0 left-0 h-full w-10 rounded-full"
            src="{{$member->image}}"
            alt="member" />
        <span class="pl-10 pr-2 text-sm">{{$member->first_name}}</span>
    </div>

    @if ($member->id !== Auth::id())
        <ul x-cloak
            x-show="isOpen"
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="absolute top-full left-0 mt-2 w-40 bg-white rounded-lg shadow-lg border border-grey-200 overflow-hidden z-50">
            <li>
                <button
                    wire:click="$dispatch('new-chat', @js(['member' => ['member_id' => Hashids::encode($member->id), 'member_name' => $member->getFullName()]]));
                    isOpen = false"
                    class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-primary-lighter hover:text-primary transition-colors flex items-center gap-x-2">
                    <x-heroicon-o-chat-bubble-oval-left class="w-4 h-4" />
                    <span>{{__('Send Message')}}</span>
                </button>
            </li>
        </ul>
    @endif
</div>
