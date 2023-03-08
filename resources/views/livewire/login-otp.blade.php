<div>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="form-group">
        <label class="input-label" for="username">{{ trans('auth.email_or_mobile') }}:</label>
        <input wire:model="emailOrPhone" type="text" class="form-control @error('emailOrPhone') is-invalid @enderror"
            id="username" value="{{ old('emailOrPhone') }}" aria-describedby="emailHelp">
        @error('emailOrPhone')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="form-group">
        <label class="input-label" for="password">{{ trans('auth.password') }}:</label>
        <input wire:model="password" type="password" class="form-control @error('password')  is-invalid @enderror"
            id="password" aria-describedby="passwordHelp">

        @error('password')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    @if ($showSms)
        <div class="form-group">
            <label class="input-label" for="password">{{ trans('auth.smscode') }}:</label>
            <input wire:model="smscode" class="form-control @error('smscode')  is-invalid @enderror" id="smscode"
                aria-describedby="smscode">

            @error('smscode')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    @endif
    <a wire:click="login()" id="submitlogin" class="btn btn-primary btn-block mt-20">{{ trans('auth.login') }}</a>
    @if (session()->has('message'))
        <div class="alert text-primary">
            {{ session('message') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="text-danger mt-4">
            {{ session('error') }}
        </div>
    @endif


</div>
