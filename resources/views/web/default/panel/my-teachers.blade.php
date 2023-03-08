@extends(getTemplate() . '.panel.layouts.panel_layout')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/select2/select2.min.css">
@endpush

@section('content')
    <section>
        <div class="d-flex flex-row justify-content-between">
            <h2 class="font-18 font-weight-normal">{{ trans('panel.total_teachers') }}</h2>
        </div>
        <div class="activities-container">
            <div class="row row-40 mt-15">
                <div class="col-12 col-md-6 col-xl-3">
                    <div class="d-flex justify-content-center stats-card">
                        <div class="d-flex flex-column align-items-center text-center mt-40">
                            <img src="/assets/default/img/classroom.svg" width="36" height="36" alt="">
                            <strong class="font-36 font-weight-bold mt-20">{{ count($user->teachers()) ?? 0 }}</strong>
                            <span class="font-16 text-dark-blue text-gray mt-20">{{ trans('quiz.total_teachers') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
          <!-- <div class="d-flex flex-column flex-md-row">
            

        </div> -->

        </div>
    </section>

    {{-- <section class="mt-25">
        <h2 class="section-title">{{ trans('quiz.filter_homeworks') }}</h2>

        <div class="panel-section-card py-20 px-25 mt-20">
            <form action="/panel/homeworks" method="get" class="row">
                <div class="col-12 col-lg-4">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label class="input-label">{{ trans('public.from') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="dateInputGroupPrepend">
                                            <i data-feather="calendar" width="18" height="18" class="text-white"></i>
                                        </span>
                                    </div>
                                    <input type="text" name="from" autocomplete="off"
                                        class="form-control @if (!empty(request()->get('from'))) datepicker @else datefilter @endif"
                                        aria-describedby="dateInputGroupPrepend" value="{{ request()->get('from', '') }}" />
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label class="input-label">{{ trans('public.to') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="dateInputGroupPrepend">
                                            <i data-feather="calendar" width="18" height="18" class="text-white"></i>
                                        </span>
                                    </div>
                                    <input type="text" name="to" autocomplete="off"
                                        class="form-control @if (!empty(request()->get('to'))) datepicker @else datefilter @endif"
                                        aria-describedby="dateInputGroupPrepend" value="{{ request()->get('to', '') }}" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <div class="form-group">
                                <label class="input-label">{{ trans('quiz.teacher') }}</label>
                                <select name="people_id" class="form-control select2"
                                    data-placeholder="{{ trans('public.all') }}">
                                    <option value="all">{{ trans('public.all') }}</option>


                                    @if (!$authUser->isTeacher())
                                        @foreach ($teachers as $teacher)
                                            <option value="{{ $teacher->id }}"
                                                @if (request()->get('people_id') == $teacher->id) selected @endif>
                                                {{ $teacher->full_name }}</option>
                                        @endforeach
                                        @else
                                        @foreach ($students as $student)
                                        <option value="{{ $student->id }}"
                                            @if (request()->get('people_id') == $student->id) selected @endif>
                                            {{ $student->full_name }}</option>
                                    @endforeach
                                    @endif

                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="row">

                                <div class="col-12 col-lg-12">
                                    <div class="form-group">
                                        <label class="input-label">{{ trans('public.status') }}</label>
                                        <select class="form-control" id="status" name="status">
                                            <option value="all">{{ trans('update.all') }}</option>
                                            <option value="active" @if (request()->get('status') == 'active') selected @endif>
                                                {{ trans('public.active') }}</option>
                                            <option value="pending" @if (request()->get('status') == 'pending') selected @endif>
                                                {{ trans('public.pending') }}</option>
                                            <option value="ended" @if (request()->get('status') == 'ended') selected @endif>
                                                {{ trans('public.ended') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-2 d-flex align-items-center justify-content-end">
                    <button type="submit"
                        class="btn btn-sm btn-primary w-100 mt-2">{{ trans('public.show_results') }}</button>
                </div>
            </form>
        </div>
    </section> --}}

    <section class="mt-35">
        <div class="d-flex align-items-start align-items-md-center justify-content-between flex-column flex-md-row">
            <h2 class="font-18 font-weight-normal">{{ trans('quiz.teachers_list') }}</h2>


        </div>

        @if (count($user->teachers()))
            <div class="panel-section-card py-20 px-25 mt-20">
                <div class="row">
                    <div class="col-12 ">
                        <div class="table-responsive">
                            <table class="table text-center custom-table">
                                <thead>
                                    <tr>
                                        <th class="text-left">{{ trans('public.img') }}</th>
                                        <th class="text-center">{{ trans('public.name') }}</th>
                                        <th class="text-center">{{ trans('public.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($user->teachers() as $teacher)
                                        <tr>
                                            <td>
                                                <div class="user-inline-avatar d-flex align-items-center">
                                                    <div class="avatar bg-gray200">
                                                        <img src="{{ $teacher->getAvatar() }}" class="img-cover"
                                                            alt="">
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="">
                                                <div class="d-block">{{ $teacher->full_name }}</div>
                                            </td>
                                            <td>
                                                <a href="{{url("users/$teacher->id/profile")}}"  class="btn btn-primary btn-sm">{{trans('update.profile')}}</a>
                                            </td>


                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @else
            @include(getTemplate() . '.includes.no-result', [
                'file_name' => 'quiz.png',
                'title' => trans('quiz.quiz_no_result'),
                'hint' => nl2br(trans('quiz.quiz_no_result_hint')),
                'btn' => ['url' => '/panel/quizzes/new', 'text' => trans('quiz.create_a_quiz')],
            ])
        @endif

        <div style="height: 150px;"></div>
    </section>


@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="/assets/default/vendors/select2/select2.min.js"></script>

    <script src="/assets/default/js/panel/quiz_list.min.js"></script>
@endpush
