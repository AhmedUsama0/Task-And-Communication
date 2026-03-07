<nav class="h-17.5 col-span-full lg:col-span-10 bg-white border-b border-gray-200 relative">
    <div class="px-3 py-3 lg:px-5 lg:pl-3 h-full">
      <div class="flex items-center justify-between h-full">
          <button @click="Alpine.store('sidebar').toggle()" 
                  class="p-2 rounded-lg lg:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200"
          >
             <x-heroicon-o-bars-3 class="w-6 h-6 text-gray-500" />
           </button>
          @auth
            @livewire('search')
          @endauth
          {{-- <div class="flex items-center">
              <select @change="$wire.changeLocale($el.value)" name="lang" id="lang">
                <option value="en" {{App::currentLocale() === 'en' ? 'selected' : ''}}>English</option>
                <option value="de" {{App::currentLocale() === 'de' ? 'selected' : ''}}>Deutsh</option>
              </select>
              </div>
            </div>
          </div> --}}
          <div class="relative" x-data="{isOpen: false}" @click.outside="isOpen = false">
            <div @click="isOpen = !isOpen" 
                 class="flex items-center gap-x-2 cursor-pointer p-2 rounded-lg hover:bg-grey-100 transition-colors"
            >
              <img class="rounded-full h-10 w-10 border-2 border-grey-200" src="{{Auth::user()->image}}">
              <span class="text-sm font-medium text-gray-700">{{Auth::user()->getFullName()}}</span>
              <span class="transition-transform duration-200" :class="{'rotate-180': isOpen}">
                <x-heroicon-o-chevron-down class="w-4 h-4 text-gray-500" />
              </span>
            </div>

            <ul x-cloak
                x-show="isOpen"
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="absolute right-0 top-full mt-2 w-56 bg-white rounded-lg shadow-lg border border-grey-200 overflow-hidden z-50"
            >
              <li class="px-4 py-3 border-b border-grey-200">
                <p class="text-sm font-medium text-gray-900">{{Auth::user()->getFullName()}}</p>
                <p class="text-xs text-gray-500 truncate">{{Auth::user()->email}}</p>
              </li>
              <li>
                <label class="flex items-center gap-x-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-primary-lighter hover:text-primary transition-colors cursor-pointer">
                  <x-heroicon-o-photo class="w-4 h-4" />
                  <span>{{__('Change Picture')}}</span>
                  <input type="file" wire:model="photo" class="hidden">
                </label>
                @error('photo') 
                  <p class="px-4 py-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
                <div wire:loading wire:target="photo" class="px-4 py-1 text-xs text-gray-500">
                  Uploading...
                </div>
                @if ($photo)
                  <div class="px-4 py-2 border-t border-grey-100">
                    <img src="{{ $photo->temporaryUrl() }}" class="w-16 h-16 rounded-full object-cover mb-2">
                    <button wire:click="save" class="text-xs bg-primary text-white px-3 py-1 rounded-lg hover:bg-blue-600 transition-colors">
                      {{__('Save')}}
                    </button>
                  </div>
                @endif
              </li>
              <li>
                <a href="#" class="flex items-center gap-x-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-primary-lighter hover:text-primary transition-colors">
                  <x-heroicon-o-cog-6-tooth class="w-4 h-4" />
                  <span>{{__('Settings')}}</span>
                </a>
              </li>
              <li class="border-t border-grey-200">
                <button @click="$wire.logout" class="w-full flex items-center gap-x-2 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                  <x-heroicon-o-arrow-right-on-rectangle class="w-4 h-4" />
                  <span>{{__('Logout')}}</span>
                </button>
              </li>
            </ul>
          </div>
    </div>
</nav>