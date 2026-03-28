<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Conversation;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\On;
use Livewire\Component;
use Vinkla\Hashids\Facades\Hashids;

class ChatManager extends Component
{
    public array $openedConversations = [];

    #[On('new-chat')]
    public function addConversation($member): void
    {
        $decodedMemberId = Hashids::decode((string) $member['member_id']);

        if (empty($decodedMemberId)) {
            Log::error(sprintf('Failed to decode the member_id in %s.', __CLASS__), [
                'member' => $member,
            ]);
            $this->dispatch('notify', message: __('An error occured. Please try again'), type: 'error');

            return;
        }

        $member['member_id'] = $decodedMemberId[0];

        $validator = Validator::make($member, [
            'member_id' => 'required|exists:users,id',
            'member_name' => 'required|string',
        ]);

        if ($validator->fails()) {
            Log::error(sprintf('Invalid conversation data received in %s', __CLASS__), [
                'errors' => $validator->errors()->toArray(),
                'data' => $member,
            ]);
            $this->dispatch('notify', message: __('An error occured. Please try again later.'), type: 'error');

            return;
        }

        $member_id = $member['member_id'];

        try {
            $conversation_id = Auth::user()->conversations()->whereHas('users', function ($query) use ($member_id) {
                return $query->where('users.id', $member_id);
            })->first()?->id;

            if ($conversation_id && array_key_exists($conversation_id, $this->openedConversations)) {
                $this->dispatch("show-conversation-$conversation_id");

                return;
            }

            if (! $conversation_id) {
                DB::transaction(function () use ($member_id, &$conversation_id) {
                    $conversation = Conversation::create();
                    $conversation->users()->attach([$member_id, Auth::id()]);
                    $conversation_id = $conversation->id;
                });
            }

            $this->openedConversations[$conversation_id] = $member['member_name'];

        } catch (Exception $e) {
            Log::error(sprintf('The conversation is not created in %s: %s', __CLASS__, $e->getMessage()));
            $this->dispatch('notify', message: __('An error occured. Please try again later.'), type: 'error');
        }
    }

    public function render()
    {
        return view('livewire/components/chat-manager');
    }
}
