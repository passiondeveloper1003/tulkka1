<div class="tab-pane mt-3 fade" id="financial" role="tabpanel" aria-labelledby="financial-tab">
    <div class="row">
        <div class="col-12 col-md-6">
            <form action="/admin/users/{{ $user->id .'/financialUpdate' }}" method="Post">
                {{ csrf_field() }}

                <div class="form-group">
                    <label>{{ trans('financial.account_type') }}</label>
                    <input type="text" name="account_type"
                           class="form-control "
                           value="{{ !empty($user) ? $user->account_type : old('account_type') }}"
                           placeholder="{{ trans('financial.account_type') }}"/>
                </div>

                <div class="form-group">
                    <label>{{ trans('financial.iban') }}</label>
                    <input type="text" name="iban"
                           class="form-control "
                           value="{{ !empty($user) ? $user->iban : old('iban') }}"
                           placeholder="{{ trans('financial.iban') }}"/>
                </div>

                <div class="form-group">
                    <label>{{ trans('financial.account_id') }}</label>
                    <input type="text" name="account_id"
                           class="form-control "
                           value="{{ !empty($user) ? $user->account_id : old('account_id') }}"
                           placeholder="{{ trans('financial.account_id') }}"/>
                </div>

                <div class="form-group mt-15">
                    <label class="input-label">{{ trans('financial.identity_scan') }}</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <button type="button" class="input-group-text admin-file-manager" data-input="identity_scan" data-preview="holder">
                                <i class="fa fa-chevron-up"></i>
                            </button>
                        </div>
                        <input type="text" name="identity_scan" id="identity_scan" value="{{ !empty($user->identity_scan) ? $user->identity_scan : old('identity_scan') }}" class="form-control"/>
                        <div class="input-group-append">
                            <button type="button" class="input-group-text admin-file-view" data-input="identity_scan">
                                <i class="fa fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>{{ trans('financial.address') }}</label>
                    <input type="text" name="address"
                           class="form-control "
                           value="{{ !empty($user) ? $user->address : old('address') }}"
                           placeholder="{{ trans('financial.address') }}"/>
                </div>

                @if(!$user->isUser())
                    <div class="form-group">
                        <label>{{ trans('admin/main.user_commission') }} (%)</label>
                        <input type="text" name="commission"
                               class="form-control "
                               value="{{ !empty($user) ? $user->commission : old('commission') }}"
                               placeholder="{{ trans('admin/main.user_commission_placeholder') }}"/>
                    </div>
                @endif

                <div class="form-group mb-0 d-flex align-items-center">
                    <div class="custom-control custom-switch d-block">
                        <input type="checkbox" name="financial_approval" class="custom-control-input" id="verifySwitch" {{ (($user->financial_approval) or (old('financial_approval') == 'on')) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="verifySwitch"></label>
                    </div>
                    <label for="verifySwitch">{{ trans('admin/main.financial_approval') }}</label>
                </div>

                <div class=" mt-4">
                    <button class="btn btn-primary">{{ trans('admin/main.submit') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
