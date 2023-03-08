<script>

    document.addEventListener('livewire:load', function() {
      const queryString = window.location.search;
  const urlParams = new URLSearchParams(queryString);
  const booking = urlParams.get('booking')
  if(booking == 'success'){
    iziToast.success({
                title: 'Success',
                message: '{{ trans('update.booking_done') }}',
                position: 'topRight'
            });
  }

        Livewire.on('bookingCompleted', postId => {
            iziToast.success({
                title: 'Success',
                message: '{{ trans('update.booking_done') }}',
                position: 'topRight'
            });
        });
        Livewire.on('bookingError', postId => {
            iziToast.error({
                title: 'Error',
                message: '{{ trans('update.booking_error') }}',
                position: 'topRight'
            });
        });
        Livewire.on('alreadyReserved', postId => {
            iziToast.error({
                title: 'Error',
                message: '{{ trans('update.already_reserved') }}',
                position: 'topRight'
            });
        });
        Livewire.on('reservedByDifferent', postId => {
            iziToast.error({
                title: 'Error',
                message: '{{ trans('update.reservedd') }}',
                position: 'topRight'
            });
        });
        Livewire.on('trialUsed', postId => {
            iziToast.error({
                title: 'Error',
                message: '{{ trans('update.trial_used') }}',
                position: 'topRight'
            });
        });
        Livewire.on('weeklyCompleted', postId => {
            iziToast.show({
                id: 'show',
                title: 'Oops!',
                icon: 'icon-drafts',
                class: 'custom1',
                displayMode: 2,
                message: '{{ trans('update.weekly_completed') }}',
                position: 'bottomCenter',
                backgroundColor: 'red',
                messageColor: 'white',
                balloon: true,

            });

        });
        Livewire.on('longerThanSubscription', postId => {
            iziToast.show({
                id: 'show',
                title: 'Oops!',
                icon: 'icon-drafts',
                class: 'custom1',
                displayMode: 2,
                message: '{{ trans('update.longerThanSubscription') }}',
                position: 'bottomCenter',
                backgroundColor: 'red',
                messageColor: 'white',
                balloon: true,

            });

        });
    })
</script>
