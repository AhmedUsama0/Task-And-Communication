<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Events\MessageSent;
use App\Events\UserTyping;
use App\Jobs\ProcessImageUpload;
use App\Models\Attachment;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class Conversation extends Component
{
    use WithFileUploads;

    public string $message;

    /**
     * @var array<TemporaryUploadedFile>
     */
    public array $attachments = [];

    #[Locked]
    public int $conversationId;

    #[Locked]
    public string $receiver;

    public array $messages = [];

    /**
     * Conversation constructor.
     */
    public function mount(): void
    {
        $this->loadMessages();
    }

    /**
     * load messages for a conversation.
     */
    protected function loadMessages(): void
    {
        // $this->conversation = ConversationModel::select('id')
        //     ->where('id', $this->conversationId)
        //     ->with([
        //         'messages:id,content,sender_id,conversation_id,created_at',
        //         'messages.user:id,first_name,image'
        //     ])->get()->toArray();

        $this->messages = Message::select('id', 'content', 'sender_id', 'created_at')
            ->where('conversation_id', $this->conversationId)
            ->with(['user:id,first_name,image', 'attachments:id,message_id,path'])
            ->get()
            ->toArray();
    }

    /**
     * Send a message to the conversation.
     *
     * @param  array  $removedFilenames  Filenames of attachments removed on the frontend
     */
    public function sendMessage(array $removedIndices = []): void
    {
        // $this->validate([
        //     'message' => 'required_without:attachments',
        //     'attachments' => 'required_without:message',
        // ]);

        $message = Message::create([
            'content' => trim($this->message),
            'sender_id' => Auth::id(),
            'conversation_id' => $this->conversationId,
        ])->load('user');

        $savedAttachments = $this->handleAttachments($message->id, $removedIndices);

        // Push it instantly to display it directly to the sender
        $this->messages[] = [
            'id' => $message->id,
            'content' => $message->content,
            'sender_id' => $message->sender_id,
            'created_at' => $message->created_at?->toISOString(),
            'user' => [
                'id' => $message->user->id,
                'first_name' => $message->user->first_name,
                'image' => $message->user->image,
            ],
            'attachments' => $savedAttachments,
        ];

        $this->reset(['message', 'attachments']);
        MessageSent::dispatch($this->conversationId, $message);
    }

    /**
     * Notify the channel that the current user started typing.
     */
    public function startedTyping(): void
    {
        UserTyping::dispatch($this->conversationId, (int) Auth::id(), true);
    }

    /**
     * Notify the channel that the current user stopped typing.
     */
    public function stoppedTyping(): void
    {
        UserTyping::dispatch($this->conversationId, (int) Auth::id(), false);
    }

    /**
     * Update messages in real time.
     */
    public function updateMessagesInRealTime(array $message): void
    {
        // Since we push the sender's message directly in sendMessage then we don't include it here to avoid duplication
        if (in_array($message['id'], array_column($this->messages, 'id'))) {
            return;
        }

        $this->messages[] = [
            'id' => $message['id'],
            'content' => $message['content'],
            'sender_id' => $message['sender_id'],
            'created_at' => $message['created_at'],
            'user' => [
                'id' => $message['user']['id'],
                'first_name' => $message['user']['first_name'],
                'image' => $message['user']['image'],
            ],
            'attachments' => $message['attachments'] ?? [],
        ];
    }

    /**
     * Processing attachments asynchronously via queue.
     */
    public function handleAttachments(int $messageId, array $removedIndices = []): array
    {
        $keptAttachments = $removedIndices ? array_filter(
            $this->attachments,
            fn ($index) => ! in_array($index, $removedIndices),
            ARRAY_FILTER_USE_KEY,
        ) : $this->attachments;

        $savedAttachments = [];

        // Store the images locally first so worker can pick them later
        foreach ($keptAttachments as $attachment) {
            $fileName = sprintf(
                '%s_%s_%s_%s',
                Auth::id(),
                $this->conversationId,
                time(),
                $attachment->getClientOriginalName(),
            );

            $finalPath = 'profile-pictures/'.$fileName;

            // It stores in local by default according to the filesystem configurations
            $attachment->storeAs(path: 'tmp-attachments', name: $fileName);
            ProcessImageUpload::dispatch('tmp-attachments/'.$fileName, $finalPath);

            // Save attachment record to database
            $savedAttachment = Attachment::create([
                'message_id' => $messageId,
                'path' => $finalPath,
            ]);

            $savedAttachments[] = [
                'id' => $savedAttachment->id,
                'path' => $savedAttachment->path,
            ];
        }

        return $savedAttachments;
    }

    public function render()
    {
        return view('livewire/components/conversation');
    }
}
