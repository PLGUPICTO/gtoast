<div>
    <script>
        window.AppUserId = @json(auth()->id());
    </script>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
    ])

    <button id="hidden-action" class="hidden" @click=""></button>

    <!-- Toast container -->
    <div 
        x-data="{ toasts: [] }"
        style="position: fixed; top: 20px; left: 50%; transform: translateX(-50%); z-index: 9999; display: flex; flex-direction: column; align-items: center; width: 100%; max-width: 400px;"
        @notify.window="
            // Add new toast at the top
            toasts.unshift({
                id: Date.now(),
                message: $event.detail.message ?? $event.detail,
            });

            // Keep max 5 toasts
            if (toasts.length > 5) {
                toasts.pop();
            }

            // Play sound
            new Audio('/sounds/alarm.mp3').play();

            // Trigger a hidden button click (auto interaction)
            document.getElementById('hidden-action').click();
        "
    >
        <!-- Render toasts -->
       <template x-for="toast in toasts" :key="toast.id">
        <div 
            x-show="true"
            x-transition
            x-init="setTimeout(() => { toasts = toasts.filter(t => t.id !== toast.id) }, 10000)"
            style="position: relative; background-color: #fef9c3; color: #000; padding: 16px; border-radius: 8px; border: 1px solid #eab308; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin-bottom: 12px; width: 100%;"
        >
            <!-- Close button -->
            <button 
                type="button" 
                style="position: absolute; top: 8px; right: 8px; background: none; border: none; cursor: pointer; color: #000;"
                @click="toasts = toasts.filter(t => t.id !== toast.id)"
            >
                <span class="sr-only">Dismiss</span>
                <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- Content -->
            <div style="display: flex; align-items: start;">
                <!-- Icon -->
                <div style="flex-shrink: 0; margin-top: 2px;">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:#ca8a04;" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.72-1.36 3.485 0l6.518 11.61c.75 1.338-.213 3.01-1.742 3.01H3.48c-1.53 0-2.493-1.672-1.743-3.01l6.52-11.61zM11 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm-1-2a.75.75 0 0 1-.75-.75V7a.75.75 0 0 1 1.5 0v4.25A.75.75 0 0 1 10 12z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <!-- Message -->
                <div style="margin-left: 12px; flex: 1;">
                    <p style="font-size: 14px; font-weight: 500;" x-text="toast.message"></p>
                </div>
            </div>
        </div>
    </template>



    </div>
</div>
