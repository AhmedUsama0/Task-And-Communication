<div x-data="initModal({{$taskId}})" x-show="isModalShown"
    class="overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
            <div class="p-4 md:p-5 text-center">
                <x-heroicon-o-check-circle />
                <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure you want to delete
                    this product?</h3>
                <button type="button" @click="$dispatch('delete-task-{{$taskId}}')"
                    class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                    Yes, I'm sure
                    {{$taskId}}
                </button>
                <button @click="closeModal()"
                    class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">No,
                    cancel</button>
            </div>
        </div>
    </div>
</div>

<script>
    function initModal(taskId) {
        return {
            isModalShown: false,

            init() {
                window.addEventListener(`modal-state-${taskId}`, (event) => {
                    this.isModalShown = event.detail.modal_state_opened;
                });

                // console.log("delete-task-"{{ $taskId }});
            },

            closeModal() {
                this.isModalShown = false;
            }
        }
    }
</script>
