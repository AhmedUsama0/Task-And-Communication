<div x-data="{
        isOpen: false,
        task: null,
        comments: [],
        statuses: null,
    }"
    x-cloak
    x-show="isOpen"
    x-on:open-task.window="
        isOpen = true;
        task = $event.detail.task;
        statuses = $event.detail.statuses;
        $wire.set('activeTaskId', task.id);
        $wire.fetchComments().then(result => {
            comments = result;
        });
    "
    class="fixed inset-0 w-full h-full z-20 flex items-center justify-center"
>
    <div
        x-show="isOpen"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-50"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-50"
        x-transition:leave-end="opacity-0"
        class="absolute inset-0 bg-black opacity-50"
    ></div>

    <div
        x-show="isOpen"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:scale-90"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:scale-90"
        @click.outside="isOpen = false"
        class="bg-white rounded-lg p-6 relative z-20 mx-auto max-w-3xl shadow-2xl"
    >
        <template x-if="task && statuses">
            <div class="grid grid-cols-12 min-h-64">
                <select name="status"
                        @change.prevent="$wire.changeTaskStatus($el.value)"
                        class="rounded-lg col-span-12 w-fit"
                >
                    <template x-for="(status, id) in statuses" :key="id">
                        <option
                            :value="id"
                            x-text="status"
                            :selected="status == task.status.status"
                        ></option>
                    </template>
                </select>
                <div class="task-details-left border-r col-span-7 pr-1">
                    <h2 x-text="task.title" class="mt-2 mb-1"></h2>
                    <p x-text="task.description"></p>
                </div>
                <div class="task-details-right col-span-5 pl-1">
                    <div class="flex flex-col gap-y-2">
                        <div class="flex items-center gap-x-3">
                            <span>{{__('Reported By')}}</span>
                            <span x-text="task.created_by.first_name + task.created_by.last_name"></span>
                        </div>
                        <div class="flex items-center gap-x-3">
                            <span>{{__('Assigned To')}}</span>
                            <span x-text="task.assigned_to.first_name"></span>
                        </div>
                        <div class="flex items-center gap-x-3">
                            <span>{{__('Priority')}}</span>
                            <span x-text="task.priority"></span>
                        </div>
                    </div>
                </div>
                <template x-if="!comments">
                    <div class="comments text-h1">Loading.....</div>
                </template>
                <div class="flex flex-col gap-y-3 col-span-full border-t mt-5 overflow-y-auto max-h-125">
                    <template x-for="comment in comments" :key="comment.id">
                        <div class="comment p-3 bg-grey-50">
                            <div class="flex gap-x-3 items-center mb-3">
                                <img class="h-10 w-10 rounded-full"
                                    src="https://i0.wp.com/nik.art/wp-content/uploads/2024/06/4-things-happy-people-dont-do-cover.png?resize=750%2C410&ssl=1"
                                    alt="member"
                                />
                                <span x-text="comment.comment_by.first_name + comment.comment_by.last_name">
                                </span>
                            </div>
                            <p x-text="comment.content"></p>
                        </div>
                    </template>
                </div>

                <div class="mt-4 col-span-full">
                    <textarea wire:model="comment" wire:keydown.enter.prevent="handleNewComment" class="form-input" name="comment" id="comment"  placeholder="Your comment here..."></textarea>
                    @error('comment') <span>{{ $message }}</span> @enderror
                    @error('activeTaskId') <span>{{ $message }}</span> @enderror
                </div>
            </div>
            {{-- Comments --}}
        </template>
    </div>
</div>
