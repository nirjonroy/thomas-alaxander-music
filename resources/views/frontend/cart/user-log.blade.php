@extends('frontend.app')
@section('title', 'Home')

@push('css')
@endpush

@section('content')
<section class="log-register-main py-8 pt-32 lg:pt-40 bg-gray-50 dark:bg-gray-900">
    <div class="container mx-auto">
        <div class="flex justify-center">
            <div class="w-full max-w-2xl bg-white rounded-lg shadow-md dark:bg-gray-800 dark:border-gray-700">
                <div class="login-reg-tabs">
                    <ul class="flex border-b mb-4" id="myTab" role="tablist">
                        <li class="flex-1 text-center">
                            <button 
                                class="py-2 px-4 w-full text-lg font-semibold border-b-2 border-transparent hover:border-primary-600 focus:border-primary-600 dark:text-white active" 
                                id="login-tab" 
                                data-bs-toggle="tab" 
                                data-bs-target="#login-tab-pane" 
                                type="button" 
                                role="tab" 
                                aria-controls="login-tab-pane" 
                                aria-selected="true">
                                Login
                            </button>
                        </li>
                        <li class="flex-1 text-center">
                            <button 
                                class="py-2 px-4 w-full text-lg font-semibold border-b-2 border-transparent hover:border-primary-600 focus:border-primary-600 dark:text-white" 
                                id="reg-tab" 
                                data-bs-toggle="tab" 
                                data-bs-target="#reg-tab-pane" 
                                type="button" 
                                role="tab" 
                                aria-controls="reg-tab-pane" 
                                aria-selected="false">
                                Registration
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content p-6" id="myTabContent">
                        <!-- Login Form -->
                        <div class="tab-pane fade show active" id="login-tab-pane" role="tabpanel" aria-labelledby="login-tab">
                            <form action="{{ url('login') }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <input type="email" name="email" class="w-full p-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Enter email / phone number" required>
                                    @error('email')
                                    <span class="text-sm text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <input type="password" name="password" class="w-full p-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Password" required>
                                    @error('password')
                                    <span class="text-sm text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>
                                <button type="submit" class="w-full py-2 bg-orange-600 text-white rounded-lg transition duration-200">Login Now</button>
                                <div class="w-full text-center mt-4">
                                    <a class="text-base font-bold mb-4 text-orange-400 hover:underline" data-bs-toggle="modal" href="#forget-pass">Are you new here? Please Register First !!!</a>
                                </div>
                            </form>
                        </div>

                        <!-- Registration Form -->
                        <div class="tab-pane pt-4 fade" id="reg-tab-pane" role="tabpanel" aria-labelledby="reg-tab">
                            <form action="{{ url('register') }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <input type="text" name="name" class="w-full p-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Enter Your Name" required>
                                    @error('name')
                                    <span class="text-sm text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <input type="text" name="phone" class="w-full p-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Phone (+1 XXXXXXXXX)" required>
                                    @error('phone')
                                    <span class="text-sm text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <input type="email" name="email" class="w-full p-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Enter email address (example@example.com)" required>
                                    @error('email')
                                    <span class="text-sm text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <input type="password" name="password" class="w-full p-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Enter Password (Minimum 8 characters)" required>
                                    @error('password')
                                    <span class="text-sm text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <input type="password" name="password_confirmation" class="w-full p-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Confirm Password" required>
                                </div>
                                <button type="submit" class="w-full py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-200">Create Account</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('js')
@endpush
