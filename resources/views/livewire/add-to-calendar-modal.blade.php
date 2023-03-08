<div>
    <div>
        <div class="modal fade @if ($show === true) show @endif" id="myExampleModal"
            style="display: @if ($show === true) block
 @else
         none @endif;" tabindex="-1"
            role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content animate__bounceIn">
                    <div class="modal-header">
                        <h5 class="modal-title font-20 mx-2" id="exampleModalLabel">{{ trans('update.booking_completed') }}
                        </h5>
                        <button class="close" type="button" aria-label="Close" wire:click.prevent="doClose()">
                            <img class="" src="{{ url('/assets/default/img/close.png') }}">
                        </button>
                    </div>
                    <div class="modal-body d-flex flex-column align-items-md-center text-center p-20">
                        <div class="row w-100 d-flex justify-content-center">
                            <div><i style="font-size: 86px;" class="fa-regular fa-circle-check text-primary"></i>
                                <div class="mt-2">{{ trans('update.booking_done') }}</div>
                            </div>
                            <div class="col-12 d-flex flex-column align-items-center">
                                <div class="form-group mt-4 w-100">
                                    <label class="font-weight-bold font-16 d-block"
                                        for="comment">{{ trans('update.add_to_calendar') }}</label>
                                    <a target="_blank" href="{{ $googleLink }}" class="btn btn-sm btn-primary mt-2"><i
                                            class="fa-brands fa-google mx-2"></i>{{ trans('update.google_calendar') }}</a>
                                    <a target="_blank" href="{{ $outlookLink }}"
                                        class="btn btn-sm btn-primary ml-2 mt-2"><i
                                            class="fa-brands fa-microsoft mx-2"></i>{{ trans('update.outlook_calendar') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- Let's also add the backdrop / overlay here -->
        <div class="modal-backdrop fade show" id="backdrop"
            style="display: @if ($show === true) block
 @else
         none @endif;"></div>
    </div>

</div>
