<div>
    <!-- <h2 class="font-18 font-weight-normal">Classes</h2> -->
    <section>
        <div class="activities-container">
            <div class="row row-40 class-first-row">
                <div class="col-12 col-md-4 col-xl-3 mb-20">
                    <div class="d-flex justify-content-center stats-card px-1">
                        <div class="d-flex flex-column align-items-center text-center mt-40">
                            <img src="/assets/default/img/classroom.svg" width="36" height="36" alt="">
                            <strong class="font-36 font-weight-bold mt-20">{{ $openReserveCount ?? 0 }}</strong>
                            <span class="font-16 text-dark-blue text-gray mt-10">{{ trans('panel.open_meetings') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4 col-xl-3 mb-20">
                    <div
                        class=" d-flex justify-content-center stats-card px-1">
                        <div class="d-flex flex-column align-items-center text-center mt-40">
                            <img src="/assets/default/img/classroom2.svg" width="36" height="36" alt="">
                            <strong class="font-36 font-weight-bold mt-20">{{ $totalReserveCount ?? 0 }}</strong>
                            <span
                                class="font-16 text-dark-blue text-gray  mt-10">{{ trans('panel.total_comp_meetings') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4 col-xl-3 mb-20">
                    <div
                        class="d-flex justify-content-center stats-card px-1">
                        <div class="d-flex flex-column align-items-center text-center mt-40">
                            <img src="/assets/default/img/classroom3.svg" width="36" height="36" alt="">
                            <strong class="font-36 text-dark-blue font-weight-bold mt-20">{{ $totalCount }}</strong>
                            <span class="font-16 text-gray  mt-10">{{ trans('panel.active_hours') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <h2 class="font-18 font-weight-normal">{{ trans('panel.filter_meetings') }}</h2>

        <div class="panel-section-card py-20 px-25 mt-20">
            <form class="row">
                <div class="col-12 col-lg-5">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label class="input-label">{{ trans('public.from') }}</label>
                                <div class="input-group">
                                    <input type="text" name="from" autocomplete="off"
                                        class="form-control @if (!empty(request()->get('from'))) datepicker @else datefilter @endif"
                                        aria-describedby="dateInputGroupPrepend"
                                        value="{{ request()->get('from', '') }}" />
                                    <span style="position:absolute; right:26px;top:12px;"
                                        class="fa-regular fa-calendar text-gray"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label class="input-label">{{ trans('public.to') }}</label>
                                <div class="input-group">
                                    <input wire:model="to" type="text" name="to" autocomplete="off"
                                        class="form-control @if (!empty(request()->get('to'))) datepicker @else datefilter @endif"
                                        aria-describedby="dateInputGroupPrepend"
                                        value="{{ request()->get('to', '') }}" />
                                    <span style="position:absolute; right:26px;top:12px;"
                                        class="fa-regular fa-calendar text-gray"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-5">
                    <div class="row">
                        <div class="col-12 col-lg-9">
                            <div class="row">
                                <div class="col-12 col-lg-8">
                                    <div class="form-group">
                                        @if (!$authUser->isTeacher())
                                            <label class="input-label">{{ trans('public.instructor') }}</label>
                                        @else
                                            <label class="input-label">{{ trans('public.students') }}</label>
                                        @endif
                                        @if (!$authUser->isTeacher())
                                            <select name="instructor_id" class="form-control select2 ">
                                                <option value="all">{{ trans('webinars.all_instructors') }}</option>

                                                @foreach ($instructors as $instructor)
                                                    <option value="{{ $instructor->id }}"
                                                        @if (request()->get('instructor_id') == $instructor->id) selected @endif>
                                                        {{ $instructor->full_name }}</option>
                                                @endforeach
                                            </select>
                                        @else
                                            <select name="student_id" class="form-control select2 ">
                                                <option value="all">{{ trans('webinars.all_students') }}</option>
                                                @foreach ($students as $student)
                                                    <option value="{{ $student->id }}"
                                                        @if (request()->get('student_id') == $student->id) selected @endif>
                                                        {{ $student->full_name }}</option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12 col-lg-4">
                                    <div class="form-group">
                                        <label class="input-label">{{ trans('public.status') }}</label>
                                        <select class="form-control" id="status" name="status" style="width: 111px;">
                                            <option>{{ trans('public.all') }}</option>
                                            <option value="started"
                                                {{ request()->get('status') === 'started' ? 'selected' : '' }}>
                                                {{ trans('public.started') }}</option>
                                            <option value="ended"
                                                {{ request()->get('status') === 'ended' ? 'selected' : '' }}>
                                                {{ trans('public.finished') }}</option>
                                            <option value="pending"
                                                {{ request()->get('status') === 'pending' ? 'selected' : '' }}>
                                                {{ trans('public.pending') }}</option>
                                            <option value="pending"
                                                {{ request()->get('status') === 'canceled' ? 'selected' : '' }}>
                                                {{ trans('public.canceled') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-2 d-flex align-items-center justify-content-end">
                    <button type="submit"
                        class="btn btn-sm btn-primary w-100 mt-2 rounded">{{ trans('public.show_results') }}</button>
                </div>
            </form>
        </div>
    </section>


    <section class="mt-35 d-flex flex-column">
        <div class="d-flex align-items-start align-items-md-center justify-content-between flex-column flex-md-row">
            <h2 class="font-18 font-weight-normal">{{ trans('panel.meeting_list') }}</h2>

            <form
                class="d-flex align-items-center flex-row-reverse flex-md-row justify-content-start justify-content-md-center mt-20 mt-md-0">
                <label class="cursor-pointer mb-0 mr-10 text-gray font-14"
                    for="openMeetingResult">{{ trans('panel.show_only_open_meetings') }}</label>
                <div class="custom-control custom-switch">
                    <input type="checkbox" name="open_meetings" class="js-panel-list-switch-filter custom-control-input"
                        id="openMeetingResult" {{ request()->get('open_meetings', '') == 'on' ? 'checked' : '' }}>
                    <label class="custom-control-label" for="openMeetingResult"></label>
                </div>
            </form>
        </div>

        @if ($reserveMeetings->count() > 0)
            <div class="panel-section-card py-20 px-25 mt-20">
                <div class="row">
                    <div class="col-12 ">
                        <div class="table-responsive">
                            <table class="table text-center custom-table">
                                <thead>
                                    <tr>
                                        @if (!$authUser->isTeacher())
                                            <th>{{ trans('public.instructor') }}</th>
                                        @else
                                            <th>{{ trans('public.student') }}</th>
                                        @endif
                                        <th class="text-center">{{ trans('public.date') }}</th>
                                        <th class="text-center">{{ trans('public.time') }}</th>
                                        <th class="text-center">{{ trans('public.status') }}</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($reserveMeetings as $ReserveMeeting)
                                        <tr>
                                            <td class="text-left">
                                                <div class="user-inline-avatar d-flex align-items-center">
                                                    <div class="avatar bg-gray200">
                                                        <img src="{{ !$authUser->isTeacher() ? $ReserveMeeting->teacher->getAvatar() : $ReserveMeeting->student->getAvatar() }}"
                                                            class="img-cover" alt="">
                                                    </div>
                                                    <div class=" ml-5">
                                                        <span
                                                            class="d-block font-weight-500">{{ !$authUser->isTeacher() ? $ReserveMeeting->teacher->full_name : $ReserveMeeting->student->full_name }}</span>
                                                        <span
                                                            class="mt-5 font-12 text-gray d-block">{{ !$authUser->isTeacher() ? $ReserveMeeting->teacher->email : $ReserveMeeting->student->email }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <span
                                                    class="font-weight-500">{{ \Carbon\Carbon::parse($ReserveMeeting->meeting_start)->setTimeZone($authUser->timezone)->toDateString() }}</span>
                                            </td>
                                            <td class="align-middle">
                                                <span>{{ \Carbon\Carbon::parse($ReserveMeeting->meeting_start)->setTimeZone($authUser->timezone)->toTimeString() }}</span>
                                            </td>
                                            <td class="align-middle">
                                                @switch($ReserveMeeting->status)
                                                    @case('pending')
                                                        <span
                                                            class="text-warning font-weight-500">{{ trans('public.pending') }}</span>
                                                    @break

                                                    @case('started')
                                                        <span
                                                            class="text-dark-blue font-weight-500">{{ trans('public.open') }}</span>
                                                    @break

                                                    @case('ended')
                                                        <span
                                                            class="text-primary font-weight-500">{{ trans('public.finished') }}</span>
                                                    @break

                                                    @case('canceled')
                                                        <span
                                                            class="text-danger font-weight-500">{{ trans('public.canceled') }}</span>
                                                    @break
                                                @endswitch
                                            </td>
                                            <td>
                                                @switch($ReserveMeeting->status)
                                                    @case('pending')
                                                        <button class="btn btn-sm">{{ trans('public.join_the_class') }}</button>
                                                    @break

                                                    @case('started')
                                                        <button class="btn btn-sm">{{ trans('public.join_the_class') }}</button>
                                                    @break

                                                    @case('ended')
                                                        <button class="btn btn-sm disable" disabled>{{ trans('public.join_the_class') }}</button>
                                                    @break

                                                    @case('canceled')
                                                        <button class="btn btn-sm disable" disabled>{{ trans('public.join_the_class') }}</button>
                                                    @break
                                                @endswitch
                                            </td>
                                            <td class="align-middle text-right">

                                                <input type="hidden"
                                                    class="js-meeting-password-{{ $ReserveMeeting->id }}"
                                                    value="{{ $ReserveMeeting->password }}">
                                                <input type="hidden"
                                                    class="js-meeting-link-{{ $ReserveMeeting->id }}"
                                                    value="{{ $ReserveMeeting->link }}">


                                                <div class="d-flex align-items-center table-actions ">
                                                    @if ($ReserveMeeting->join_url and $ReserveMeeting->status == 'started' && !$authUser->isTeacher())
                                                        <a target="_blank" href="{{ $ReserveMeeting->join_url }}"
                                                            data-reserve-id="{{ $ReserveMeeting->id }}"
                                                            class="{{-- js-join-reserve --}} btn-transparent webinar-actions d-block mr-10 text-primary">{{ trans('footer.join_lesson') }}</a>
                                                    @endif
                                                    @if (!$authUser->isTeacher() && $ReserveMeeting->feedback)
                                                        <a target="_blank"
                                                            href="/panel/feedbacks/{{ $ReserveMeeting->feedback->id }}"
                                                            data-reserve-id="{{ $ReserveMeeting->id }}"
                                                            class="{{-- js-join-reserve --}} btn-transparent webinar-actions d-block mr-10">
                                                            <img src="/assets/default/img/section-icons/Vector-1.svg"
                                                                alt="">
                                                        </a>
                                                    @endif

                                                    @if ($ReserveMeeting->admin_url and $ReserveMeeting->status == 'started' && $authUser->isTeacher())
                                                        <a target="_blank" href="{{ $ReserveMeeting->admin_url }}"
                                                            data-reserve-id="{{ $ReserveMeeting->id }}"
                                                            class="{{-- js-join-reserve --}} btn-transparent webinar-actions d-block mr-10">{{ trans('footer.start') }}</a>
                                                    @endif
                                                    @if ($authUser->isTeacher() && $ReserveMeeting->status != 'ended')
                                                        <a onclick='Livewire.emit("showZoomModal","{{ $ReserveMeeting->join_url }}","{{ $ReserveMeeting->admin_url }}","{{ $ReserveMeeting->id }}")'
                                                            class="{{-- contact-info --}} btn-transparent {{-- webinar-actions --}} d-block mr-10">{{ trans('panel.enter_zoom_url') }}</a>
                                                    @endif
                                                    {{-- <a href="" target="_blank"
                                                        class="webinar-actions d-block mt-10 font-weight-normal">{{ trans('public.add_to_calendar') }}</a> --}}
                                                    @if ($ReserveMeeting->status == 'ended' && $authUser->isTeacher())
                                                        <a onclick='Livewire.emit("showFeedbackModal","{{ $ReserveMeeting->student_id }}","{{ $ReserveMeeting->teacher_id }}","{{ $ReserveMeeting->id }}")'
                                                            class="{{-- contact-info --}} btn-transparent {{-- webinar-actions --}} d-block mr-10">{{ trans('panel.give_feedback') }}</a>
                                                        <a onclick='Livewire.emit("showHomeworkModal","{{ $ReserveMeeting->student->full_name }}","{{ $ReserveMeeting->student_id }}","{{ $ReserveMeeting->teacher_id }}","{{ $ReserveMeeting->id }}")'
                                                            class="{{-- contact-info --}} btn-transparent {{-- webinar-actions --}} d-block mr-10">{{ trans('panel.give_homework') }}</a>
                                                    @endif
                                                    <a wire:click="goToChat({{ $ReserveMeeting->teacher_id }})"
                                                        class="contact-info btn-transparent webinar-actions d-block mr-10">
                                                        <img src="/assets/default/img/section-icons/chat.svg"
                                                            alt="">
                                                    </a>
                                                    
                                                    <a wire:click="goToEvaluation({{ $ReserveMeeting->feedback_id }})"
                                                        class="contact-info btn-transparent webinar-actions d-block mr-10">
                                                        <img src="/assets/default/img/section-icons/evaluation.svg"
                                                            alt="">
                                                    </a>
                                                    <a href="/panel/feedbacks/{{ $ReserveMeeting->teacher_id }}/details"
                                                        class="contact-info btn-transparent webinar-actions d-block mr-10">
                                                        <img src="/assets/default/img/section-icons/homework.svg"
                                                            alt="">
                                                    </a>
                                                    @if (
                                                        $ReserveMeeting->status == 'pending' &&
                                                            \Carbon\Carbon::parse($ReserveMeeting->meeting_start)->diffInHours(\Carbon\Carbon::now()) > 4)
                                                        <a onclick='Livewire.emit("showRescheduleModal","{{ $ReserveMeeting->teacher_id }}","{{ $ReserveMeeting->id }}")'
                                                            type="button" data-id="{{ $ReserveMeeting->id }}"
                                                            class="webinar-actions {{-- js-finish-meeting-reserve --}} d-block btn-transparent ml-10 font-weight-normal">
                                                            <img src="/assets/default/img/section-icons/Vector-2.svg"
                                                                alt="">
                                                        </a>
                                                    @endif
                                                </div>

                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="my-30 mx-auto">
                {{ $reserveMeetings->links() }}
            </div>
        @else
            @include(getTemplate() . '.includes.no-result', [
                'file_name' => 'meeting.png',
                'title' => trans('panel.meeting_no_result'),
                'hint' => nl2br(trans('panel.meeting_no_result_hint')),
            ])
        @endif
    </section>
</div>
