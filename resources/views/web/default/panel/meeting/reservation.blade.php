@extends(getTemplate() . '.panel.layouts.panel_layout')
@push('styles_top')
    <link rel="stylesheet" href="/assets/default/css/css-stars.css">
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
@endpush
@section('content')
    @livewire('class-list', ['student' => request()->get('student_id', ''), 'from' => request()->get('from', ''), 'to' => request()->get('to', ''), 'teacher' => request()->get('instructor_id', ''), 'status' => request()->get('status', ''), 'started' => request()->get('open_meetings', '')])
    @livewire('reschedule-modal')
    @livewire('feedback-modal')
    @livewire('homework-modal')
    @livewire('add-to-calendar-modal')
    @livewire('zoom-modal')
    @include('web.default.panel.meeting.join_modal')
    @include('web.default.includes.booking_actions')
@endsection

@push('scripts_bottom')
    <script>
        var instructor_contact_information_lang = '{{ trans('panel.instructor_contact_information') }}';
        var student_contact_information_lang = '{{ trans('panel.student_contact_information') }}';
        var email_lang = '{{ trans('public.email') }}';
        var phone_lang = '{{ trans('public.phone') }}';
        var location_lang = '{{ trans('update.location') }}';
        var close_lang = '{{ trans('public.close') }}';
        var finishReserveHint = '{{ trans('meeting.finish_reserve_modal_hint') }}';
        var finishReserveConfirm = '{{ trans('meeting.finish_reserve_modal_confirm') }}';
        var finishReserveCancel = '{{ trans('meeting.finish_reserve_modal_cancel') }}';
        var finishReserveTitle = '{{ trans('meeting.finish_reserve_modal_title') }}';
        var finishReserveSuccess = '{{ trans('meeting.finish_reserve_modal_success') }}';
        var finishReserveSuccessHint = '{{ trans('meeting.finish_reserve_modal_success_hint') }}';
        var finishReserveFail = '{{ trans('meeting.finish_reserve_modal_fail') }}';
        var finishReserveFailHint = '{{ trans('meeting.finish_reserve_modal_fail_hint') }}';
    </script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="/assets/default/js/panel/meeting/contact-info.min.js"></script>
    <script src="/assets/default/js/panel/meeting/reserve_meeting.min.js"></script>
    <script>
        document.addEventListener('livewire:load', function() {
            Livewire.on('feedbackSent', postId => {
                iziToast.success({
                    title: 'Success',
                    message: 'Your Feedback Successfully sent',
                    position: 'topRight'
                });
            });
            Livewire.on('homeworkSent', postId => {
                iziToast.success({
                    title: 'Success',
                    message: 'Your Homework Successfully sent',
                    position: 'topRight'
                });
            });
        })
    </script>
@endpush
