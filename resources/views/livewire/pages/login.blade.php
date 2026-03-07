<div class="bg-gray-900 col-span-full min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="bg-gray-800 rounded-lg shadow-2xl border border-gray-700 p-8">
            <div class="mb-8 text-center">
                <h1 class="text-h2 text-white mb-2">{{__('Welcome Back')}}</h1>
                <p class="text-gray-400 text-sm">{{__('Sign in to your account to continue')}}</p>
            </div>

            <form wire:submit="authenticate">
                
                <div class="mb-5">
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-300">
                        {{__('Email')}}
                    </label>
                    <input type="email" 
                           id="email"
                           placeholder="{{__('Enter your email')}}" 
                           wire:model="email"
                           class="shadow-sm bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 placeholder-gray-400"
                    />
                    @error('email')
                        <span class="text-sm text-red-400 mt-1 block">{{$message}}</span>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-300">
                        {{__('Password')}}
                    </label>
                    <input type="password" 
                           id="password"
                           wire:model="password"
                           placeholder="{{__('Enter your password')}}"
                           class="shadow-sm bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 placeholder-gray-400"
                    />
                    @error('password')
                        <span class="text-sm text-red-400 mt-1 block">{{$message}}</span>
                    @enderror
                </div>

                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <input id="remember" 
                               type="checkbox" 
                               wire:model="remember"
                               class="w-4 h-4 border border-gray-600 rounded bg-gray-700 focus:ring-2 focus:ring-primary text-primary"
                        />
                        <label for="remember" class="ml-2 text-sm text-gray-300 cursor-pointer">
                            {{__('Remember me')}}
                        </label>
                    </div>
                </div>

                <button type="submit"
                        class="btn btn-primary w-full">
                   {{__('Login')}}
                </button>
            </form>
        </div>
    </div>
</div>
