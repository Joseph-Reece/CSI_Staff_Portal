@props(['id' => 'modal', 'title' => 'Modal Title'])

<div x-data="{ open: false }">
    <!-- Trigger -->
    {{ $trigger }}

    <!-- Modal Background -->
    <div x-show="open"x-cloak  x-transition.opacity class="fixed inset-0 z-40 bg-black/50 flex items-center justify-center">

        <!-- Modal Content -->
        <div x-show="open" x-cloak x-transition class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 relative z-50">

            <h2 class="text-lg font-bold mb-4">{{ $title }}</h2>

            <!-- Slot for form/content -->
            <div>
                {{ $slot }}
            </div>

            <!-- Footer -->
            <!-- <div class="flex justify-end mt-4 gap-2">
                <button @click="open = false"
                        class="px-3 py-1 rounded bg-gray-300 hover:bg-gray-400">
                    Cancel
                </button>
            </div> -->
        </div>
    </div>
</div>