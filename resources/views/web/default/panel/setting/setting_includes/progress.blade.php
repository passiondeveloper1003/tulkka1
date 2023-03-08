@php
    $progressSteps = [
        1 => [
            'lang' => 'public.basic_information',
            'icon' => 'basic-info',
        ],

        2 => [
            'lang' => 'public.images',
            'icon' => 'images',
        ],

        3 => [
            'lang' => 'public.about',
            'icon' => 'about',
        ],

        4 => [
            'lang' => 'public.educations',
            'icon' => 'graduate',
        ],

        5 => [
            'lang' => 'public.experiences',
            'icon' => 'experiences',
        ],

        6 => [
            'lang' => 'public.occupations',
            'icon' => 'skills',
        ],

        7 => [
            'lang' => 'public.identity_and_financial',
            'icon' => 'financial',
        ],
    ];

    if (!$user->isUser()) {
        $progressSteps[8] = [
            'lang' => 'public.zoom_api',
            'icon' => 'zoom',
        ];

        $progressSteps[9] = [
            'lang' => 'public.extra_information',
            'icon' => 'extra_info',
        ];
    }

    $currentStep = empty($currentStep) ? 1 : $currentStep;
@endphp

<div class="webinar-progress ">

    <!-- @foreach ($progressSteps as $key => $step)
        @if (!$user->isTeacher() && ($key == 6 || $key == 7))
            @continue
        @endif
        <a href="@if (!empty($organization_id)) /panel/manage/{{ $user_type ?? 'instructors' }}/{{ $user->id }}/edit/step/{{ $key }} @else /panel/setting/step/{{ $key }} @endif"
            class="d-flex align-items-center justify-content-center text-primary panel-shadow bg-white p-30 rounded {{ $key == $currentStep ? 'active-setting' : '' }}"
            data-toggle="tooltip" data-placement="top" title="{{ trans($step['lang']) }}">
            <div class="progress-item d-flex align-items-center">
                <div class="ml-10">
                    <h4 class="font-16 text-black font-weight-bold">{{ trans($step['lang']) }}</h4>
                </div>
            </div>
        </a>
    @endforeach -->
    <div class="row default-row">
        <div class="col-12 col-md-4">
            <div class="bg-white d-flex justify-content-center flex-column br-5 settings-header-card pl-45">
                <h3 class="font-18 font-weight-normal text-primary">{{ trans('update.settings_welcome_head') }}</h3>
                <p>{{ trans('update.settings_welcome_desc') }}</p>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="bg-white d-flex align-items-center justify-content-center flex-column br-5 settings-header-card">
                <h3 class="font-18 font-weight-normal text-primary">{{ trans('update.profile') }}</h3>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="bg-white d-flex align-items-center justify-content-center flex-column br-5 settings-header-card">
                <h3 class="font-18 font-weight-normal text-primary">{{ trans('update.biography') }}</h3>
            </div>
        </div>
    </div>
</div>
