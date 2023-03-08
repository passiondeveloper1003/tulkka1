<div class="modal-content animate__bounceIn @if(!$hasError)bg-success @else bg-danger  @endif">
    <div class="modal-header">
        <h5 class="modal-title mx-2">Payment</h5>
        <button class="close" type="button" aria-label="Close" wire:click.prevent="doClose()">
          <img src="{{ url('/assets/default/img/close.png') }}">
        </button>
    </div>
    <div class="modal-body text-center flex-md-row flex-column  p-40">
        @if ($hasError)
        <div style="min-height: 360px" class="alert alert-danger d-flex flex-column justify-content-center" role="alert">
          <i class="fa-solid fa-circle-xmark font-48 text-white"></i>
          <h4 class="alert-heading font-24 text-white mt-4">Problem happened!</h4>
          <p class="font-16 mt-4 text-white">{{serialize($hasError)}}</p>
      </div>

        @else
            <div style="min-height: 360px" class="alert d-flex flex-column justify-content-center" role="alert">
              <i class="fa-regular fa-circle-check font-48 text-primary"></i>
                <h4 class="alert-heading font-24 mt-4">Well done!</h4>
                <p class="font-16 mt-4">Your Subscription is successful. </p>
            </div>
        @endif

    </div>
</div>
