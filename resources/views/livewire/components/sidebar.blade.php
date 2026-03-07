<aside :class="{'-translate-x-full' : !Alpine.store('sidebar').isOpen}" 
       class="fixed top-[69px] row-span-full w-full transition-transform bg-gray-50 border-r border-gray-200 
              lg:translate-x-0 lg:static lg:col-span-2" 
>
    <div class="h-full px-3 pb-4 pt-4 overflow-y-auto">
       <ul class="space-y-2 font-medium">
          <li class="flex items-center gap-x-2 p-2 cursor-pointer text-gray-400 hover:text-gray-600 rounded-lg 
                   hover:bg-grey-200"
              wire:click="$dispatch('switch-component',{component: 'profile'})"
          >
             <x-heroicon-o-home class="w-5 h-5" />
             <span>{{__('Profile')}}</span>
          </li>
          <li class="text-gray-400" x-data="{isOpen: false}">
             <div @click="isOpen = !isOpen" 
                  class="p-2 cursor-pointer flex items-center gap-x-2 hover:text-gray-600 hover:bg-grey-200 rounded-lg"
              >
               <x-heroicon-o-folder-minus class="w-5 h-5" />
               <span class="flex-1">{{__('Projects')}}</span>
               <span class="transition-transform" :class="{'rotate-180': isOpen}">
                  <x-heroicon-o-chevron-down class="w-4 h-4" />
               </span>
             </div>

             <ul x-ref="scrollContainer" 
                 class="flex flex-col gap-y-3 pl-10 max-h-0 overflow-hidden transition-all relative" 
                 :style="isOpen ? 'max-height: ' + $refs.scrollContainer.scrollHeight + 'px' : ''"
             >
               <div x-show="isOpen"
                    class="absolute top-0 left-4 w-px bg-gray-400"
                    :style="isOpen ? 'height: ' + ($refs.scrollContainer.scrollHeight - 21) + 'px' : ''"
               ></div>

               @foreach ($my_projects as $project) 
                <li class="text-sm relative p-3"
                    wire:key="{{$project->id}}" 
                    wire:click="$dispatch('switch-component',@js(['component' => 'project-details', 'data' => ['project_id' => $project->id]]))"
                    role="button"
                >
                   <span class="absolute left-[-23px] top-1/2 h-px w-4 bg-gray-400" x-show="isOpen"></span>
                   {{$project->title}}
                </li>
               @endforeach
             </ul>
          </li>
          <li class="flex items-center gap-x-2 p-2 cursor-pointer text-gray-400 hover:text-gray-600 rounded-lg hover:bg-grey-200">
             <x-heroicon-o-users class="w-5 h-5" />
             <span class="flex-1">{{__('Members')}}</span>
             <x-heroicon-o-chevron-down class="w-4 h-4" />
          </li>
          <li class="flex items-center gap-x-2 p-2 cursor-pointer text-gray-400 hover:text-gray-600 rounded-lg hover:bg-grey-200">
             <x-heroicon-o-cog-6-tooth class="w-5 h-5" />
             <span>{{__('Settings')}}</span>
          </li>
       </ul>
    </div>
</aside>
