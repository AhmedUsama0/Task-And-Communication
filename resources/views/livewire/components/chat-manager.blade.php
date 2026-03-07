<div class="chats-container absolute bottom-0 left-0 w-full flex gap-x-3 p-4 z-10">
    @foreach ($openedConversations as $conversationId => $receiver)
        @livewire('conversation', ['conversationId' => $conversationId, 'receiver' => $receiver], key($conversationId))
    @endforeach
</div>
