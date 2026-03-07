<?php

namespace App\Livewire;

use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class ChatManager extends Component
{
    public array $openedConversations = [];

    #[On('new-chat')]
    public function addConversation($conversation): void
    {
        ['receiver' => $receiver, 'conversation_id' => $conversation_id] = $conversation;

        if (array_key_exists($conversation_id, $this->openedConversations)) {
            $this->dispatch("show-conversation-$conversation_id");

            return;
        }

        if (! $conversation_id) {
            $conversation = Conversation::create();
            // There are other methods can be used to attach users to a conversation, but this is the most direct one
            $conversation->users()->attach([$receiver['member_id'], Auth::id()]);
            $conversation_id = $conversation->id;
        }

        $this->openedConversations[$conversation_id] = $receiver;
    }

    public function render()
    {
        return view('livewire/components/chat-manager');
    }
}
