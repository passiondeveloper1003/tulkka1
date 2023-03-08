@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="/admin/">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ trans('admin/main.offline_payments') }}</div>
            </div>
        </div>

        <div class="section-body">

            <section class="card">
                <div class="card-body">
                    <form class="mb-0">
                        <input type="hidden" name="page_type" value="{{ $pageType }}">

                        <div class="row">
                            <div class="@if($pageType == 'requests') col-md-3 @else col-md-4 @endif">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.search')}}</label>
                                    <input type="text" class="form-control text-center" name="search" value="{{ request()->get('search') }}">
                                </div>
                            </div>

                            <div class="@if($pageType == 'requests') col-md-3 @else col-md-4 @endif">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.start_date')}}</label>
                                    <div class="input-group">
                                        <input type="date" id="fsdate" class="text-center form-control" name="from" value="{{ request()->get('from') }}" placeholder="Start Date">
                                    </div>
                                </div>
                            </div>

                            <div class="@if($pageType == 'requests') col-md-3 @else col-md-4 @endif">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.end_date')}}</label>
                                    <div class="input-group">
                                        <input type="date" id="lsdate" class="text-center form-control" name="to" value="{{ request()->get('to') }}" placeholder="End Date">
                                    </div>
                                </div>
                            </div>

                            @if($pageType == 'history')
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="input-label">{{trans('admin/main.status')}}</label>
                                        <select name="status" data-plugin-selectTwo class="form-control populate">
                                            <option value="">{{trans('admin/main.all_status')}}</option>
                                            <option value="approved" @if(request()->get('status') == 'approved') selected @endif>{{trans('admin/main.approved')}}</option>
                                            <option value="reject" @if(request()->get('status') == 'reject') selected @endif>{{trans('admin/main.rejected')}}</option>
                                        </select>
                                    </div>
                                </div>
                            @endif

                            <div class="@if($pageType == 'requests') col-md-3 @else col-md-2 @endif">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.role')}}</label>
                                    <select name="role_id" data-plugin-selectTwo class="form-control populate">
                                        <option value="">{{trans('admin/main.all_roles')}}</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" @if($role->id == request()->get('role_id')) selected @endif>{{ $role->caption }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="@if($pageType == 'requests') col-md-3 @else col-md-2 @endif">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.user')}}</label>
                                    <select name="user_ids[]" multiple="multiple" class="form-control search-user-select2"
                                            data-placeholder="Search teachers">

                                        @if(!empty($users) and $users->count() > 0)
                                            @foreach($users as $user_filter)
                                                <option value="{{ $user_filter->id }}" selected>{{ $user_filter->full_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>


                            <div class="@if($pageType == 'requests') col-md-3 @else col-md-2 @endif">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.bank')}}</label>
                                    <select name="account_type" data-plugin-selectTwo class="form-control populate">
                                        <option value="">{{trans('admin/main.all_banks')}}</option>
                                        @if(!empty(getOfflineBanksTitle()) and count(getOfflineBanksTitle())) {
                                        @foreach(getOfflineBanksTitle() as $accountType)
                                            <option value="{{ $accountType }}" @if(request()->get('account_type') == $accountType) selected @endif>{{ $accountType }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>


                            <div class="@if($pageType == 'requests') col-md-3 @else col-md-2 @endif">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.filters')}}</label>
                                    <select name="sort" data-plugin-selectTwo class="form-control populate">
                                        <option value="">Filter Type</option>
                                        <option value="amount_asc" @if(request()->get('sort') == 'amount_asc') selected @endif>{{trans('admin/main.amount_ascending')}}</option>
                                        <option value="amount_desc" @if(request()->get('sort') == 'amount_desc') selected @endif>{{trans('admin/main.amount_descending')}}</option>
                                        <option value="pay_date_asc" @if(request()->get('sort') == 'pay_date_asc') selected @endif>{{trans('admin/main.Transaction_time_ascending')}}</option>
                                        <option value="pay_date_desc" @if(request()->get('sort') == 'pay_date_desc') selected @endif>{{trans('admin/main.Transaction_time_descending')}}</option>
                                    </select>
                                </div>
                            </div>


                            <div class="@if($pageType == 'requests') col-md-3 @else col-md-2 @endif">
                                <div class="form-group mt-1">
                                    <label class="input-label mb-4"> </label>
                                    <input type="submit" class="text-center btn btn-primary w-100" value="{{trans('admin/main.show_results')}}">
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </section>

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            @can('admin_offline_payments_export_excel')
                                <a href="/admin/financial/offline_payments/excel?{{ http_build_query(request()->all()) }}" class="btn btn-primary">{{ trans('admin/main.export_xls') }}</a>
                            @endcan
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped font-14">
                                    <tr>
                                        <th>{{trans('admin/main.user')}}</th>
                                        <th>{{trans('admin/main.role')}}</th>
                                        <th>{{trans('admin/main.amount')}}</th>
                                        <th>{{trans('admin/main.bank')}}</th>
                                        <th>{{trans('admin/main.referral_code')}}</th>
                                        <th>{{trans('admin/main.phone')}}</th>
                                        <th>{{trans('update.attachment')}}</th>
                                        <th width=180px>{{trans('admin/main.transaction_time')}}</th>

                                        @if($pageType == 'history')
                                            <th>{{trans('admin/main.status')}}</th>
                                        @endif

                                        @if($pageType == 'requests')
                                            <th width="150px">{{trans('admin/main.actions')}}</th>
                                        @endcan
                                    </tr>

                                    @if($offlinePayments->count() > 0)
                                        @foreach($offlinePayments as $offlinePayment)
                                            <tr>
                                                <td class="text-left">
                                                    {{ $offlinePayment->user->full_name }}
                                                </td>

                                                <td>{{ $offlinePayment->user->role->caption }}</td>

                                                <td>{{ addCurrencyToPrice($offlinePayment->amount) }}</td>

                                                <td>{{ $offlinePayment->bank }}</td>

                                                <td>
                                                    <span>{{ $offlinePayment->reference_number }}</span>
                                                </td>

                                                <td>{{ $offlinePayment->user->mobile }}</td>

                                                <td class="text-center align-middle">
                                                    @if(!empty($offlinePayment->attachment))
                                                        <a href="{{ $offlinePayment->getAttachmentPath() }}" target="_blank" class="text-primary">{{ trans('public.view') }}</a>
                                                    @else
                                                        ---
                                                    @endif
                                                </td>

                                                <td>{{ dateTimeFormat($offlinePayment->pay_date, 'j M Y H:i') }}</td>

                                                @if($pageType == 'history')
                                                    <td>
                                                        <span class="{{ ($offlinePayment->status == 'approved') ? 'text-success' : 'text-danger' }}">
                                                            @if($offlinePayment->status == 'approved')
                                                                <span class="text-success">{{ trans('financial.approved') }}</span>
                                                            @else
                                                                <span class="text-danger">{{ trans('public.rejected') }}</span>
                                                            @endif
                                                        </span>
                                                    </td>
                                                @endif

                                                @if($pageType == 'requests')
                                                    <td>
                                                        @if($offlinePayment->status == \App\Models\OfflinePayment::$waiting)
                                                            @can('admin_offline_payments_approved')
                                                                @include('admin.includes.delete_button',[
                                                                        'url' => '/admin/financial/offline_payments/'. $offlinePayment->id .'/approved',
                                                                        'tooltip' => trans('financial.approve'),
                                                                        'btnIcon' => 'fa-check'
                                                                    ])
                                                            @endcan

                                                            @can('admin_offline_payments_reject')
                                                                @include('admin.includes.delete_button',[
                                                                        'url' => '/admin/financial/offline_payments/'. $offlinePayment->id .'/reject',
                                                                        'tooltip' => trans('public.reject'),
                                                                        'btnIcon' => 'fa-times-circle',
                                                                        'btnClass' => 'ml-2',
                                                                    ])
                                                            @endcan
                                                        @endif
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    @endif

                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $offlinePayments->appends(request()->input())->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="card">
        <div class="card-body">
            <div class="section-title ml-0 mt-0 mb-3"><h5>{{trans('admin/main.hints')}}</h5></div>
            <div class="row">
                <div class="col-md-6">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('admin/main.offline_payment_hint_title_1')}}</div>
                        <div class=" text-small font-600-bold">{{trans('admin/main.offline_payment_hint_description_1')}}</div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('admin/main.offline_payment_hint_title_2')}}</div>
                        <div class=" text-small font-600-bold">{{trans('admin/main.offline_payment_hint_description_2')}}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

