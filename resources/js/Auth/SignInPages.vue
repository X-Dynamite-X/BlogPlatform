<template>
    <!-- Overlay -->
        <div class="fixed inset-0 bg-black bg-opacity-20 flex items-center justify-center z-40">
            <!-- Modal -->
            <section class="w-full max-w-lg m-auto bg-white rounded-2xl shadow-lg drop-shadow p-4">
                <div class="py-3">
                    <div class="border-b-4 border-gray-600 my-2">
                        <h1 class="text-center text-green-700 text-3xl font-bold">SignIn Page</h1>
                    </div>
                    <div class="p-2 rounded-lg">
                        <form action="" @submit.prevent="sendDataForm">
                            <div class="mb-4 flex items-center">
                                <label for="username"   class="w-1/3 text-gray-700 font-bold"> User Name </label>
                                <input type="text" name="name" id="username" class="w-2/3 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500" :placeholder="`Enter your User Name`">
                            </div>
                            <div class="mb-4 flex items-center">
                                <label for="email"   class="w-1/3 text-gray-700 font-bold"> Email </label>
                                <input type="email" name="email"  id="email" class="w-2/3 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500" :placeholder="`Enter your Email`">
                            </div>
                            <div class="mb-4 flex items-center">
                                <label for="password"   class="w-1/3 text-gray-700 font-bold"> password</label>
                                <input type="password" name="password" id="password" class="w-2/3 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500" :placeholder="`Enter your password`">
                            </div>
                            <div class="mb-4 flex items-center">
                                <label for="password_confirmation"   class="w-1/3 text-gray-700 font-bold"> password_confirmation</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="w-2/3 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500" :placeholder="`Enter your password`">
                            </div>
                            <div class="flex items-center justify-center mt-4">
                                <button type="submit" class="bg-green-500 mx-2 text-white font-bold py-2 px-4 rounded-lg hover:bg-green-600 focus:outline-none focus:shadow-outline">SignIn</button>
                            </div>
                            <div class="flex items-center justify-center mt-4 ">
                                <router-link to="login"   class=" mx-2 text-black font-bold py-2 px-4 border-b hover:border-blue-800 ">Login</router-link>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </template>
<script>
export default {

}
</script>
