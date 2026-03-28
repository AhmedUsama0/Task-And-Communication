<div x-data="{ collapsed: false, visible: true, typingUser: null }"
    x-show="visible"
    x-on:show-conversation-{{ $conversationId }}.window="visible = true"
    x-on:typing-indicator.window="
        if ($event.detail.typing) {
            console.log($event.detail.typing);
            typingUser = $event.detail.user;
            if (typingTimeout) clearTimeout(typingTimeout);
            typingTimeout = setTimeout(() => { typingUser = null; typingTimeout = null; }, 3000);
        } else {
            if (typingUser && typingUser.id === $event.detail.user_id) typingUser = null;
            if (typingTimeout) { clearTimeout(typingTimeout); typingTimeout = null; }
        }
    "
    class="w-80 rounded-lg overflow-hidden flex flex-col-reverse"
    :class="collapsed ? '' : 'border border-grey-200 shadow-lg'"
>
    <div class="header p-3 bg-primary text-white flex items-center justify-between order-last">
        <p @click="collapsed = !collapsed" class="font-medium truncate cursor-pointer flex-1">{{$receiver}}</p>
        <div class="flex items-center gap-x-1">
            <button @click="collapsed = !collapsed" class="p-1 hover:bg-blue-600 rounded transition-colors">
                <span class="transition-transform duration-200 block" :class="{ 'rotate-180': !collapsed }">
                    <x-heroicon-o-chevron-up class="w-5 h-5" />
                </span>
            </button>
            <button @click="visible = false" class="p-1 hover:bg-blue-600 rounded transition-colors">
                <x-heroicon-o-x-mark class="w-5 h-5" />
            </button>
        </div>
    </div>
    <div x-show="!collapsed" class="bg-white">
        <div x-show="typingUser" x-cloak class="px-4 py-2 bg-grey-50 border-b border-grey-100">
            <p class="text-xs text-gray-500 italic" x-text="typingUser ? typingUser.first_name + ' {{ __('is typing...') }}' : ''"></p>
        </div>
        <div id="messages-{{ $conversationId }}" class="messages bg-grey-50 h-75 p-4 overflow-y-auto flex flex-col gap-y-3">
           @foreach ($messages as $message)
               <div wire:key="{{ $message['id'] }}" 
                    class="flex items-start gap-x-3 {{ $message['user']['id'] === Auth::id() ? 'flex-row-reverse' : '' }}">
                   <img class="rounded-full w-10 h-10 flex-shrink-0 border-2 border-white shadow-sm" src="{{ $message['user']['image'] }}">
                   <div class="flex flex-col {{ $message['user']['id'] === Auth::id() ? 'items-end' : 'items-start' }} max-w-[75%]">
                       <p class="text-xs text-gray-500 mb-1 px-1">
                           {{ $message['user']['id'] === Auth::id() ? __('You') : $message['user']['first_name'] }}
                       </p>
                       <div class="flex flex-col gap-2">
                           @if(!empty($message['attachments']))
                               <div class="flex flex-wrap gap-1.5 {{ $message['user']['id'] === Auth::id() ? 'justify-end' : 'justify-start' }}">
                                   @foreach($message['attachments'] as $attachment)
                                       <img src="{{ $attachment['path'] }}" 
                                            alt="attachment" 
                                            loading="lazy"
                                            class="w-20 h-20 object-cover rounded-lg border border-grey-200 shadow-sm cursor-pointer hover:opacity-90 transition-opacity">
                                   @endforeach
                               </div>
                           @endif
                           @if($message['content'])
                               <p class="p-3 rounded-lg {{ $message['user']['id'] === Auth::id() ? 'bg-primary-lighter text-gray-900' : 'bg-white text-gray-900 border border-grey-200' }} shadow-sm">
                                   {{ $message['content'] }}
                               </p>
                           @endif
                       </div>
                   </div>
               </div>
           @endforeach
        </div>
        <div class="border-t border-grey-200 p-2 bg-white" 
             x-data="{
                uploading: false, 
                progress: 0,
                removedIndices: [],
                isTyping: false,
                removeAttachment(index) {
                    this.removedIndices.push(index);
                },
                isRemoved(index) {
                    return this.removedIndices.includes(index);
                },
                async sendMessageAndScroll() {
                    $wire.stoppedTyping();
                    await $wire.sendMessage(this.removedIndices);
                    this.removedIndices = [];
                },
                startedTyping() {
                    if (!this.isTyping) {
                        this.isTyping = true;
                        $wire.startedTyping();
                    }
                },
                stoppedTyping() {
                    if (this.isTyping) {
                        this.isTyping = false;
                        $wire.stoppedTyping();
                    }
                }
             }"
             x-on:livewire-upload-start="uploading=true"
             x-on:livewire-upload-finish="uploading=false"
             x-on:livewire-upload-cancel="uploading=false"
             x-on:livewire-upload-error="uploading=false"
             x-on:livewire-upload-progress="progress = $event.detail.progress"
        >
            @if(count($attachments))
                <div class="flex items-center gap-2 flex-wrap px-2 py-2">
                    @foreach($attachments as $index => $attachment)
                        <div x-show="!isRemoved({{ $index }})" 
                             wire:key="{{ $attachment->getFilename() }}"
                             class="relative group"
                        >
                            <img src="{{ $attachment->temporaryUrl() }}" 
                                 alt="attachment" 
                                 class="w-16 h-16 object-cover rounded-lg border border-grey-200">
                            <button type="button"
                                    @click="removeAttachment({{ $index }})"
                                    class="absolute -top-1.5 -right-1.5 p-0.5 bg-red-500 text-white rounded-full 
                                           opacity-0 group-hover:opacity-100 transition-opacity shadow-sm hover:bg-red-600">
                                <x-heroicon-o-x-mark class="w-3.5 h-3.5" />
                            </button>
                        </div>
                    @endforeach
                </div>
            @endif
            <div x-show="uploading" x-cloak class="px-2 py-2">
                <div class="flex items-center gap-x-2">
                    <div class="flex-1 h-2 bg-grey-200 rounded-full overflow-hidden">
                        <div class="h-full bg-primary rounded-full transition-all duration-300 ease-out" :style="`width: ${progress}%`"></div>
                    </div>
                    <span class="text-xs text-gray-500 font-medium w-9" x-text="`${Math.round(progress)}%`"></span>
                    <button type="button" 
                            @click="$wire.cancelUpload('attachment')" 
                            class="p-1 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-full transition-colors">
                        <x-heroicon-o-x-mark class="w-4 h-4" />
                    </button>
                </div>
            </div>
           <textarea @keydown.enter.prevent="sendMessageAndScroll()" 
                 @input="startedTyping()"
                 @input.debounce.2000ms="stoppedTyping()"
                 class="form-input resize-none border-0 focus:ring-0 p-0 px-2"
                 name="message"
                 id="message-{{ $conversationId }}"
                 rows="2"
                 placeholder="Type a message..."
                 wire:model="message"></textarea>
           <div class="flex items-center justify-between pt-2">
               <div class="flex items-center gap-x-1">
                   <label class="p-1.5 text-gray-500 hover:text-primary hover:bg-primary-lighter rounded-lg cursor-pointer transition-colors">
                       <x-heroicon-o-paper-clip class="w-5 h-5" />
                       <input type="file" wire:model="attachments" class="hidden" multiple />
                   </label>
                   <button type="button" class="p-1.5 text-gray-500 hover:text-primary hover:bg-primary-lighter rounded-lg transition-colors">
                       <x-heroicon-o-face-smile class="w-5 h-5" />
                   </button>
               </div>
               <button @click="sendMessageAndScroll()" 
                       class="btn-icon btn-primary"
                       :disabled="!$wire.message && !$wire.attachments.length"
                >
                   <x-heroicon-o-paper-airplane class="w-5 h-5" />
               </button>
           </div>
        </div>
    </div>
    @script
    <script>
        const conversationId = {{ $conversationId }};
        const currentUserId = {{ Auth::id() }};

        window.Echo.channel('conversation-' + conversationId)
            .listen('MessageSent', ({message}) => {
                if (message['sender_id'] === currentUserId) {
                    return;
                }
                $wire.updateMessagesInRealTime(message);
            })
            .listen('UserTyping', (payload) => {
                if (payload.user_id === currentUserId) return;
                window.dispatchEvent(new CustomEvent('typing-indicator', {
                    detail: { user_id: payload.user_id, typing: payload.typing, user: payload.user }
                }));
            });
    </script>
    @endscript
</div>
