<div>
    <div class="modal fade @if ($show === true) show @endif" id="myExampleModal"
        style="display: @if ($show === true) block
       @else
               none @endif;" tabindex="-1"
        role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
            @if ($currentStep == 1)
                @include('livewire.steps.subscription_step')
            {{-- @elseif($currentStep == 2)
                @include('livewire.steps.payment_step') --}}
            @elseif($currentStep == 2)
                @include('livewire.steps.card_step')
            @elseif($currentStep == 4)
                @include('livewire.steps.result_step')
            @endif

        </div>
    </div>
    <!-- Let's also add the backdrop / overlay here -->
    <div class="modal-backdrop fade show" id="backdrop"
        style="display: @if ($show === true) block
       @else
               none @endif;"></div>
</div>
