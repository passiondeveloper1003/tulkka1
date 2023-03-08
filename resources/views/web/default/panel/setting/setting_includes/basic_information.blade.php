<section class="bg-white br-5 settings-user-profile">
    <!-- <h2 class="section-title after-line">{{ trans('financial.account') }}</h2> -->
    <div class="row h-100">
        <div class="col-md-3 text-center d-flex flex-column justify-content-center align-items-center">
            <img src="{{ $authUser->getAvatar(100) }}" class="settings-user-avatar" alt="{{ $authUser->full_name }}">
            <h5 class="settings-user-name text-center">{{ $authUser->full_name }}</h5>
            <button class="btn btn-sm text-primary mt-20">{{ trans('update.edit_photo') }}</button>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="input-label">{{ trans('auth.name') }}</label>
                <input type="text" name="full_name" value="{{ (!empty($user) and empty($new_user)) ? $user->full_name : old('full_name') }}" class="form-control @error('full_name')  is-invalid @enderror" placeholder=""/>
                @error('full_name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="form-group">
                <label class="input-label">{{ trans('public.email') }}</label>
                <input type="text" name="email" value="{{ (!empty($user) and empty($new_user)) ? $user->email : old('email') }}" class="form-control @error('email')  is-invalid @enderror" placeholder=""/>
                @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="form-group settings-timezone">
                <label class="input-label">{{ trans('update.timezone') }}</label>
                <select name="timezone" class="form-control select2" data-allow-clear="false">
                    <option value="" {{ empty($user->timezone) ? 'selected' : '' }} disabled>{{ trans('public.select') }}</option>
                    @foreach(getListOfTimezones() as $timezone)
                        <option value="{{ $timezone }}" @if(!empty($user) and $user->timezone == $timezone) selected @endif>{{ $timezone }}</option>
                    @endforeach
                </select>
                @error('timezone')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="input-label">{{ trans('public.mobile') }}</label>
                <input type="tel" name="mobile" value="{{ (!empty($user) and empty($new_user)) ? $user->mobile : old('mobile') }}" class="form-control @error('mobile')  is-invalid @enderror" placeholder=""/>
                @error('mobile')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="form-group">
                <label class="input-label">{{ trans('auth.language') }}</label>
                <select name="language" class="form-control">
                    <option value="">{{ trans('auth.language') }}</option>
                    @foreach($userLanguages as $lang => $language)
                        <option value="{{ $lang }}" @if(!empty($user) and mb_strtolower($user->language) == mb_strtolower($lang)) selected @endif>{{ $language }}</option>
                    @endforeach
                </select>
                @error('language')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="input-label">{{ trans('auth.password') }}</label>
                <input type="password" name="password" value="{{ old('password') }}" class="form-control @error('password')  is-invalid @enderror" placeholder=""/>
                @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="form-group">
                <label class="input-label">{{ trans('auth.password_repeat') }}</label>
                <input type="password" name="password_confirmation" value="{{ old('password_confirmation') }}" class="form-control @error('password_confirmation')  is-invalid @enderror" placeholder=""/>
                @error('password_confirmation')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="d-flex justify-content-between pt-25">
                <button class="btn btn-sm btn-outline-primary settings-btn">{{ trans('update.cancel') }}</button>
                <button class="btn btn-sm btn-primary settings-btn">{{ trans('update.save_change') }}</button>
            </div>
        </div>
    </div>

</section>

<div class="row mt-20 settings-bottom-sec">
    <div class="col-12 col-md-3">
        <div class="p-20 bg-white br-10 text-center settings-bottom-item">
            <h4 class="font-20 font-weight-normal">{{ trans('update.your_plan') }}</h4>
            <h3 class="font-20 mt-20">Premium</h3>
            <p class="font-14">Paid up to <span>11.02.2023</span></p>
            <button class="btn btn-sm btn-outline-primary settings-btn mt-30">Change</button>
        </div>
    </div>
    <div class="col-12 col-md-3">
        <div class="p-20 bg-white br-10 text-center settings-bottom-item">
            <h4 class="font-20 font-weight-normal">{{ trans('update.connect_calendar') }}</h4>
            <div class="mt-20 d-flex align-items-center font-14">
                <img src="/assets/default/img/section-icons/google.png" style="margin: 2px;"/>
                <span class="ml-15 font-14">Google calendar</span>
            </div>
            <div class="mt-10 d-flex align-items-center font-14">
                <img src="/assets/default/img/section-icons/calendar.png" />
                <span class="ml-15 font-14">Microsoft calendar</span>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-3">
        <div class="p-20 bg-white br-10 text-center settings-bottom-item">
            <h4 class="font-20 font-weight-normal">{{ trans('update.receving_reminder') }}</h4>
            <div class="d-flex justify-content-between align-items-center custom-sm-switch mt-15">
                <label class="cursor-pointer input-label" for="newsletterSwitch1">{{ trans('update.24_hours_before_class') }}</label>
                <div class="custom-control custom-switch pl-0 m-0">
                    <input type="checkbox" name="24_hours_before" class="custom-control-input" id="newsletterSwitch1" {{ (!empty($user) and $user->newsletter) ? 'checked' : '' }}>
                    <label class="custom-control-label" for="newsletterSwitch1"></label>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-1 custom-sm-switch">
                <label class="cursor-pointer input-label" for="newsletterSwitch2">{{ trans('update.1_hour_before_class') }}</label>
                <div class="custom-control custom-switch pl-0 m-0">
                    <input type="checkbox" name="1_hour_before" class="custom-control-input" id="newsletterSwitch2" {{ (!empty($user) and $user->newsletter) ? 'checked' : '' }}>
                    <label class="custom-control-label" for="newsletterSwitch2"></label>
                </div>
            </div> 
            <div class="d-flex justify-content-between align-items-center mt-1 custom-sm-switch">
                <label class="cursor-pointer input-label" for="newsletterSwitch3">{{ trans('update.30_mins_before_class') }}</label>
                <div class="custom-control custom-switch pl-0 m-0">
                    <input type="checkbox" name="30_min_before" class="custom-control-input" id="newsletterSwitch3" {{ (!empty($user) and $user->newsletter) ? 'checked' : '' }}>
                    <label class="custom-control-label" for="newsletterSwitch3"></label>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-1 custom-sm-switch">
                <label class="cursor-pointer input-label" for="newsletterSwitch4">{{ trans('update.once_class_starts') }}</label>
                <div class="custom-control custom-switch pl-0 m-0">
                    <input type="checkbox" name="one_class_starts" class="custom-control-input" id="newsletterSwitch4" {{ (!empty($user) and $user->newsletter) ? 'checked' : '' }}>
                    <label class="custom-control-label" for="newsletterSwitch4"></label>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-3">
        <div class="p-20 bg-white br-10 text-center settings-bottom-item">
            <h4 class="font-20 font-weight-normal">{{ trans('update.channels') }}</h4>
            <div class="d-flex justify-content-between align-items-center custom-sm-switch mt-15">
                <label class="cursor-pointer input-label" for="newsletterSwitch5">Join Whatsapp</label>
                <div class="custom-control custom-switch pl-0 m-0">
                    <input type="checkbox" name="join_newsletter" class="custom-control-input" id="newsletterSwitch5" {{ (!empty($user) and $user->newsletter) ? 'checked' : '' }}>
                    <label class="custom-control-label" for="newsletterSwitch5"></label>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center custom-sm-switch mt-10">
                <label class="cursor-pointer input-label" for="newsletterSwitch6">Join Email</label>
                <div class="custom-control custom-switch pl-0 m-0">
                    <input type="checkbox" name="join_newsletter" class="custom-control-input" id="newsletterSwitch6" {{ (!empty($user) and $user->newsletter) ? 'checked' : '' }}>
                    <label class="custom-control-label" for="newsletterSwitch6"></label>
                </div>
            </div>
        </div>
    </div>
</div>

