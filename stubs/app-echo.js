setTimeout(() => {
    window.Echo.private(`App.Models.User.${window.AppUserId}`)
        .listen('FirstEvent', (e) => {
            console.log('test');

            Livewire.dispatch('notify', {
                message: e.message ?? 'New broadcast received!'
            });
        });
}, 200);
