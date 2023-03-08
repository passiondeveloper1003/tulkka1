<div>
    <div>
        <div class="modal fade @if ($show === true) show @endif"
            style="display: @if ($show === true) block
   @else
           none @endif;" tabindex="-1"
            role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content animate__bounceIn">
                    <div class="modal-header">
                        <h5 class="modal-title mx-2" id="exampleModalLabel">{{ trans('update.goals') }}</h5>
                        <button class="close" type="button" aria-label="Close" wire:click.prevent="doClose()">
                            <img class="" src="{{ url('/assets/default/img/close.png') }}">
                        </button>
                    </div>
                    <div class="modal-body d-flex flex-column align-items-md-center text-center p-40">
                        @if ($i == 1)
                            <div>
                                <div class="font-20 font-weight-normal">
                                    Do you want to cancel / reschedule the lesson?
                                </div>
                                <div class="mt-4">
                                    <button wire:click="setReschedule()"
                                        class="btn btn-primary btn-sm rounded mx-2">Reschedule</button>
                                    <button wire:click="cancelLesson()"
                                        class="btn btn-primary btn-sm rounded mx-2">Cancel</button>
                                </div>
                            </div>
                        @endif
                        @if ($i == 2)
                            <div>
                                @livewire('calendar', ['instructor' => $instructor, 'dashboard' => true,'reschedule' => true])
                            </div>
                        @endif
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
