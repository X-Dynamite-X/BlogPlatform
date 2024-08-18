<template>
    <!-- Overlay -->
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-40">
            <!-- Modal -->
            <section class="w-full max-w-lg m-auto bg-white rounded-2xl shadow-lg drop-shadow p-4">
                <div class="py-3">
                    <div class="border-b-4 border-gray-600 my-2">
                        <h1 class="text-center text-red-700 text-3xl font-bold">Delete Model</h1>
                    </div>
                    <div class="p-2 rounded-lg">
                        <form action="" >
                            <div class="p-4 text-center">
                                <p class="text-gray-700 text-lg">Are you sure you want to delete this friend? This action cannot be undone.</p>
                            </div>
                            <div class="flex items-center justify-center mt-4">
                                <button type="button" class="bg-red-500 mx-2 text-white font-bold py-2 px-4 rounded-lg hover:bg-red-600 focus:outline-none focus:shadow-outline">Delete</button>
                                <button type="button" class="bg-orange-500 mx-2 text-white font-bold py-2 px-4 rounded-lg hover:bg-orange-600 focus:outline-none focus:shadow-outline">Exit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>


    </template>
