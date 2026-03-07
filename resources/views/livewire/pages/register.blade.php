<div class="bg-gray-800 col-span-full">
    <div class="container h-screen flex items-center justify-center">
        <form class="w-1/3" wire:submit="register">
            @csrf
            <div class="mb-5">
                <label for="firstname" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"><?= __('First Name') ?></label>
                <input type="text" id="firstname"
                    class="form-input"
                    wire:model="first_name" />
                @error('firstname')
                    <span class="text-danger">{{$message}}</span>
                @enderror
            </div>
            <div class="mb-5">
                <label for="lastname" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"><?= __('Last Name') ?></label>
                <input type="text" id="lastname"
                    class="form-input"
                    wire:model="last_name" />
                @error('lastname')
                    <span class="text-danger">{{$message}}</span>
                @enderror
            </div>
            <div class="mb-5">
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{__('Email')}}</label>
                <input type="email" id="email"
                    class="form-input"
                    placeholder="name@flowbite.com" wire:model="email" />
                @error('email')
                    <span class="text-danger">{{$message}}</span>
                @enderror
            </div>
            <div class="mb-5">
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    {{ __('Password') }}
                </label>
                <input type="password" id="password"
                    class="form-input"
                    wire:model="password"/>
                @error('password')
                    <span class="text-danger">{{$message}}</span>
                @enderror
            </div>
            <button type="submit"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 w-full"
                ><?= __('Register') ?>
            </button>
            @error('general')
                <span class="text-danger">{{$message}}</span>
            @enderror
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                {{__('Already have an account.')}}
                <a class="font-medium text-blue-600 hover:underline dark:text-blue-500" 
                    href="{{route('login',['locale' => App::currentLocale()])}}">
                    {{__('Login Now')}}
                </a>
            </p>
        </form>
    </div>
</div>