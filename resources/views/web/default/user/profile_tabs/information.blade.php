<div>
{{-- @if(!isset($learningMaterialsExtraDescription) and count($learningMaterialsExtraDescription))
    <div class="mt-20 rounded-sm border bg-info-light p-15">
        <h3 class="font-16 text-secondary font-weight-bold mb-15">{{ trans('update.what_you_will_learn') }}</h3>

        @foreach($learningMaterialsExtraDescription as $learningMaterial)
            <p class="d-flex align-items-start font-14 text-gray mt-10">
                <i data-feather="check" width="18" height="18" class="mr-10 webinar-extra-description-check-icon"></i>
                <span class="">{{ $learningMaterial->value }}</span>
            </p>
        @endforeach
    </div>
@endif --}}

{{--course description--}}
@if(isset($user->about))
    <div class="mt-20">

        <div class="mt-15 course-description">
            {!! clean($user->about) !!}
        </div>
    </div>
@endif
{{-- ./ course description--}}

{{-- @if(!isset($companyLogosExtraDescription) and count($companyLogosExtraDescription))
    <div class="mt-20 rounded-sm border bg-white p-15">
        <div class="mb-15">
            <h3 class="font-16 text-secondary font-weight-bold">{{ trans('update.suggested_by_top_companies') }}</h3>
            <p class="font-14 text-gray mt-5">{{ trans('update.suggested_by_top_companies_hint') }}</p>
        </div>

        <div class="row">
            @foreach($companyLogosExtraDescription as $companyLogo)
                <div class="col text-center">
                    <img src="{{ $companyLogo->value }}" class="webinar-extra-description-company-logos" alt="{{ trans('update.company_logos') }}">
                </div>
            @endforeach
        </div>
    </div>
@endif --}}

{{-- @if(!isset($requirementsExtraDescription) and count($requirementsExtraDescription))
    <div class="mt-20">
        <h3 class="font-16 text-secondary font-weight-bold mb-15">{{ trans('update.requirements') }}</h3>

        @foreach($requirementsExtraDescription as $requirementExtraDescription)
            <p class="d-flex align-items-start font-14 text-gray mt-10">
                <i data-feather="check" width="18" height="18" class="mr-10 webinar-extra-description-check-icon"></i>
                <span class="">{{ $requirementExtraDescription->value }}</span>
            </p>
        @endforeach
    </div>
@endif --}}


{{-- @if(!isset($course->prerequisites) and $course->prerequisites->count() > 0)

    <div class="mt-20">
        <h2 class="section-title after-line">{{ trans('public.prerequisites') }}</h2>

        @foreach($course->prerequisites as $prerequisite)
            @if($prerequisite->prerequisiteWebinar)
                @include('web.default.includes.webinar.list-card',['webinar' => $prerequisite->prerequisiteWebinar])
            @endif
        @endforeach
    </div>
@endif --}}

@include('web.default.includes.teacher_comments',[
        'comments' => $user->takenComments,
        'inputName' => 'user_id',
        'inputValue' => $user->id,
    ])

</div>
