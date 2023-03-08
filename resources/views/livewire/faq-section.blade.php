<div class="container p-0">
    <div class="content text-center">
        <div class="font-40 text-center mt-4 position-relative d-inline">{{ trans('update.faq') }}
            <div class="custom-highlight position-absolute right-0"><img src="/assets/default/img/highlight.png" /></div>
        </div>
        <div class="mt-4">
            <div wire:click="setActiveSection('general')"
                class="btn @if ($activeSection == 'general') btn-primary @else btn-gray @endif rounded mx-2">{{ trans('update.general') }}</div>
            <div wire:click="setActiveSection('lessons')"
                class="btn @if ($activeSection == 'lessons') btn-primary @else btn-gray @endif rounded mx-2">{{ trans('update.lessons') }}
            </div>
            <div wire:click="setActiveSection('payment')"
                class="btn @if ($activeSection == 'payment') btn-primary @else btn-gray @endif rounded mx-2">{{ trans('update.payment') }}
            </div>
        </div>

        <div class="panel-group text-center mt-4" id="accordion" role="tablist" aria-multiselectable="true">
            <div class="panel panel-default mt-2  @if ($activeSection == 'lessons') d-block @else d-none @endif">
                <div class="panel-heading" id="headingOne" role="tab">
                    <h4 class="panel-title"><a class="rounded-lg p-20" role="button" data-toggle="collapse"
                            data-parent="#accordion" href="#collapseOne" aria-expanded="true"
                            aria-controls="collapseOne">{{ trans('textdata.faq-q-1') }}
                            <i class="pull-right fa fa-plus"></i></a>
                    </h4>
                </div>
                <div class="p-2 p-2 panel-collapse collapse in" id="collapseOne" role="tabpanel"
                    aria-labelledby="headingOne">
                    <div class="panel-body">
                        <p>{{ trans('textdata.faq-a-1') }}</p>
                    </div>
                </div>
            </div>
            <div class="panel panel-default mt-2 @if ($activeSection == 'lessons') d-block @else d-none @endif">
                <div class="panel-heading" id="headingTwo" role="tab">
                    <h4 class="panel-title"><a class="collapsed rounded-lg p-20" role="button" data-toggle="collapse"
                            data-parent="#accordion" href="#collapseTwo" aria-expanded="false"
                            aria-controls="collapseTwo">{{ trans('textdata.faq-q-2') }}<i
                                class="pull-right fa fa-plus"></i></a></h4>
                </div>
                <div class="p-2 panel-collapse collapse" id="collapseTwo" role="tabpanel" aria-labelledby="headingTwo">
                    <div class="panel-body">
                        <p>{{ trans('textdata.faq-a-2') }}</p>
                    </div>
                </div>
            </div>
            <div class="panel panel-default mt-2 @if ($activeSection == 'general') d-block @else d-none @endif">
                <div class="panel-heading" id="headingThree" role="tab">
                    <h4 class="panel-title"><a class="collapsed rounded-lg p-20" role="button" data-toggle="collapse"
                            data-parent="#accordion" href="#collapseThree" aria-expanded="false"
                            aria-controls="collapseThree">{{ trans('textdata.faq-q-3') }}<i class="pull-right fa fa-plus"></i></a></h4>
                </div>
                <div class="p-2 panel-collapse collapse" id="collapseThree" role="tabpanel"
                    aria-labelledby="headingThree">
                    <div class="panel-body">
                        <p>
                        {{ trans('textdata.faq-a-3') }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="panel panel-default mt-2 @if ($activeSection == 'general') d-block @else d-none @endif">
                <div class="panel-heading" id="headingThree" role="tab">
                    <h4 class="panel-title"><a class="collapsed rounded-lg p-20" role="button" data-toggle="collapse"
                            data-parent="#accordion" href="#collapse4" aria-expanded="false"
                            aria-controls="collapse4">{{ trans('textdata.faq-q-4') }}
                            <i class="pull-right fa fa-plus"></i></a></h4>
                </div>
                <div class="p-2 panel-collapse collapse" id="collapse4" role="tabpanel" aria-labelledby="headingThree">
                    <div class="panel-body">
                        <p>{{ trans('textdata.faq-a-4') }}</p>
                    </div>
                </div>
            </div>
            <div class="panel panel-default mt-2 @if ($activeSection == 'payment') d-block @else d-none @endif">
                <div class="panel-heading" id="headingThree" role="tab">
                    <h4 class="panel-title"><a class="collapsed rounded-lg p-20" role="button" data-toggle="collapse"
                            data-parent="#accordion" href="#collapse5" aria-expanded="false"
                            aria-controls="collapse5">{{ trans('textdata.faq-q-5') }}<i class="pull-right fa fa-plus"></i></a></h4>
                </div>
                <div class="p-2 panel-collapse collapse" id="collapse5" role="tabpanel"
                    aria-labelledby="headingThree">
                    <div class="panel-body">
                        <p>{{ trans('textdata.faq-a-5') }}</p>
                    </div>
                </div>
            </div>
            <div class="panel panel-default mt-2 @if ($activeSection == 'general') d-block @else d-none @endif">
                <div class="panel-heading" id="headingThree" role="tab">
                    <h4 class="panel-title"><a class="collapsed rounded-lg p-20" role="button"
                            data-toggle="collapse" data-parent="#accordion" href="#collapse6" aria-expanded="false"
                            aria-controls="collapse6">{{ trans('textdata.faq-q-6') }}<i class="pull-right fa fa-plus"></i></a></h4>
                </div>
                <div class="p-2 panel-collapse collapse" id="collapse6" role="tabpanel"
                    aria-labelledby="headingThree">
                    <div class="panel-body">
                        <p>{{ trans('textdata.faq-a-6') }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="panel panel-default mt-2 @if ($activeSection == 'general') d-block @else d-none @endif">
                <div class="panel-heading" id="headingThree" role="tab">
                    <h4 class="panel-title"><a class="collapsed rounded-lg p-20" role="button"
                            data-toggle="collapse" data-parent="#accordion" href="#collapse7" aria-expanded="false"
                            aria-controls="collapse7">{{ trans('textdata.faq-q-7') }}
                            <i class="pull-right fa fa-plus"></i></a></h4>
                </div>
                <div class="p-2 panel-collapse collapse" id="collapse7" role="tabpanel"
                    aria-labelledby="headingThree">
                    <div class="panel-body">
                        <p>{{ trans('textdata.faq-a-7') }}</p>
                    </div>
                </div>
            </div>
            <div class="panel panel-default mt-2 @if ($activeSection == 'lessons') d-block @else d-none @endif">
                <div class="panel-heading" id="headingThree" role="tab">
                    <h4 class="panel-title"><a class="collapsed rounded-lg p-20" role="button"
                            data-toggle="collapse" data-parent="#accordion" href="#collapse8" aria-expanded="false"
                            aria-controls="collapse8">{{ trans('textdata.faq-q-8') }}<i class="pull-right fa fa-plus"></i></a></h4>
                </div>
                <div class="p-2 panel-collapse collapse" id="collapse8" role="tabpanel"
                    aria-labelledby="headingThree">
                    <div class="panel-body">
                        <p>
                        {{ trans('textdata.faq-a-8') }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="panel panel-default mt-2 @if ($activeSection == 'lessons') d-block @else d-none @endif">
                <div class="panel-heading" id="headingThree" role="tab">
                    <h4 class="panel-title"><a class="collapsed rounded-lg p-20" role="button"
                            data-toggle="collapse" data-parent="#accordion" href="#collapse9" aria-expanded="false"
                            aria-controls="collapse9">{{ trans('textdata.faq-q-9') }}<i class="pull-right fa fa-plus"></i></a></h4>
                </div>
                <div class="p-2 panel-collapse collapse" id="collapse9" role="tabpanel"
                    aria-labelledby="headingThree">
                    <div class="panel-body">
                        <p>
                        {{ trans('textdata.faq-a-9') }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="panel panel-default mt-2 @if ($activeSection == 'lessons') d-block @else d-none @endif">
                <div class="panel-heading" id="headingThree" role="tab">
                    <h4 class="panel-title"><a class="collapsed rounded-lg p-20" role="button"
                            data-toggle="collapse" data-parent="#accordion" href="#collapse10" aria-expanded="false"
                            aria-controls="collapse10">{{ trans('textdata.faq-q-10') }}
                            <i class="pull-right fa fa-plus"></i></a></h4>
                </div>
                <div class="p-2 panel-collapse collapse" id="collapse10" role="tabpanel"
                    aria-labelledby="headingThree">
                    <div class="panel-body">
                        <p>{{ trans('textdata.faq-a-10') }}</p>
                    </div>
                </div>
            </div>
            <div class="panel panel-default mt-2 @if ($activeSection == 'general') d-block @else d-none @endif">
                <div class="panel-heading" id="headingThree" role="tab">
                    <h4 class="panel-title"><a class="collapsed rounded-lg p-20" role="button"
                            data-toggle="collapse" data-parent="#accordion" href="#collapse11" aria-expanded="false"
                            aria-controls="collapse11">{{ trans('textdata.faq-q-11') }}<i class="pull-right fa fa-plus"></i></a></h4>
                </div>
                <div class="p-2 panel-collapse collapse" id="collapse11" role="tabpanel"
                    aria-labelledby="headingThree">
                    <div class="panel-body">
                        <p>
                        {{ trans('textdata.faq-a-11') }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="panel panel-default mt-2 @if ($activeSection == 'payment') d-block @else d-none @endif">
                <div class="panel-heading" id="headingThree" role="tab">
                    <h4 class="panel-title"><a class="collapsed rounded-lg p-20" role="button"
                            data-toggle="collapse" data-parent="#accordion" href="#collapse12" aria-expanded="false"
                            aria-controls="collapse12">{{ trans('textdata.faq-q-12') }}<i class="pull-right fa fa-plus"></i></a>
                    </h4>
                </div>
                <div class="p-2 panel-collapse collapse" id="collapse12" role="tabpanel"
                    aria-labelledby="headingThree">
                    <div class="panel-body">
                        <p> {{ trans('textdata.faq-a-12') }}</p>
                    </div>
                </div>
            </div>
          <div class="panel panel-default mt-2 @if ($activeSection == 'lessons') d-block @else d-none @endif">
                <div class="panel-heading" id="headingThree" role="tab">
                    <h4 class="panel-title"><a class="collapsed rounded-lg p-20" role="button"
                            data-toggle="collapse" data-parent="#accordion" href="#collapse13" aria-expanded="false"
                            aria-controls="collapse13">{{ trans('textdata.faq-q-13') }}<i class="pull-right fa fa-plus"></i></a></h4>
                </div>
                <div class="p-2 panel-collapse collapse" id="collapse13" role="tabpanel"
                    aria-labelledby="headingThree">
                    <div class="panel-body">
                        <p>{{ trans('textdata.faq-a-13') }} </p>
                    </div>
                </div>
            </div>
 {{--            <div class="panel panel-default mt-2 @if ($activeSection == 'payment') d-block @else d-none @endif">
                <div class="panel-heading" id="headingThree" role="tab">
                    <h4 class="panel-title"><a class="collapsed rounded-lg p-20" role="button"
                            data-toggle="collapse" data-parent="#accordion" href="#collapse14" aria-expanded="false"
                            aria-controls="collapse14">What do I do if I want
                            a refund?<i class="pull-right fa fa-plus"></i></a></h4>
                </div>
                <div class="p-2 panel-collapse collapse" id="collapse14" role="tabpanel"
                    aria-labelledby="headingThree">
                    <div class="panel-body">
                        <p>Contact support and let us know your situation. A refund will be processed
                            according to our policies as is applicable by the team.</p>
                    </div>
                </div>
            </div>
            <div class="panel panel-default mt-2 @if ($activeSection == 'payment') d-block @else d-none @endif">
                <div class="panel-heading" id="headingThree" role="tab">
                    <h4 class="panel-title"><a class="collapsed rounded-lg p-20" role="button"
                            data-toggle="collapse" data-parent="#accordion" href="#collapse15" aria-expanded="false"
                            aria-controls="collapse15">How can I get some
                            discounts?<i class="pull-right fa fa-plus"></i></a></h4>
                </div>
                <div class="p-2 panel-collapse collapse" id="collapse15" role="tabpanel"
                    aria-labelledby="headingThree">
                    <div class="panel-body">
                        <p>Every student can opt in to having a free trial class. Keep an eye out for
                            discounted rates throughout the year! We offer discounts in bundles, with
                            special packages, and deals made available permanently and seasonally. Look
                            for our packages and running discounts on the website for more details.</p>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
</div>
