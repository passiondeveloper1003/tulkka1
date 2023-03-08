@extends('admin.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/sweetalert2/dist/sweetalert2.min.css">
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('admin/main.sales') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="/admin/">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ trans('admin/main.sales') }}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{trans('update.total_success_orders')}}</h4>
                            </div>
                            <div class="card-body">
                                {{ $successOrders['count'] }}
                            </div>
                            <div class="text-primary font-weight-bold">
                                {{ addCurrencyToPrice($successOrders['amount']) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-danger">
                            <i class="fas fa-times-circle"></i></div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{trans('update.total_canceled_orders')}}</h4>
                            </div>
                            <div class="card-body">
                                {{ $canceledOrders['count'] }}
                            </div>
                            <div class="text-success font-weight-bold">
                                {{ addCurrencyToPrice($canceledOrders['amount']) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="fas fa-hourglass-half"></i></div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{trans('update.total_waiting_orders')}}</h4>
                            </div>
                            <div class="card-body">
                                {{ $waitingOrders['count'] }}
                            </div>
                            <div class="text-danger font-weight-bold">
                                {{ addCurrencyToPrice($waitingOrders['amount']) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-shopping-basket"></i></div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{trans('update.total_orders')}}</h4>
                            </div>
                            <div class="card-body">
                                {{ $totalOrders['count'] }}
                            </div>
                            <div class="text-danger font-weight-bold">
                                {{ addCurrencyToPrice($totalOrders['amount']) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <section class="card">
                <div class="card-body">
                    <form method="get" class="mb-0">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{ trans('admin/main.search') }}</label>
                                    <input type="text" class="form-control" name="item_title" value="{{ request()->get('item_title') }}">
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{ trans('admin/main.start_date') }}</label>
                                    <div class="input-group">
                                        <input type="date" id="fsdate" class="text-center form-control" name="from" value="{{ request()->get('from') }}" placeholder="Start Date">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{ trans('admin/main.end_date') }}</label>
                                    <div class="input-group">
                                        <input type="date" id="lsdate" class="text-center form-control" name="to" value="{{ request()->get('to') }}" placeholder="End Date">
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{ trans('admin/main.status') }}</label>
                                    <select name="status" data-plugin-selectTwo class="form-control populate">
                                        <option value="">{{ trans('admin/main.all_status') }}</option>
                                        @foreach(\App\Models\ProductOrder::$status as $str)
                                            @if($str != \App\Models\ProductOrder::$pending)
                                                <option value="{{ $str }}" @if(request()->get('status') == $str) selected @endif>{{ trans('update.product_order_status_'.$str) }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="input-label">{{ trans('admin/main.seller') }}</label>
                                    <select name="seller_ids[]" multiple="multiple" data-search-option="just_organization_and_teacher_role" class="form-control search-user-select2"
                                            data-placeholder="{{ trans('update.search_seller') }}">

                                        @if(!empty($sellers) and $sellers->count() > 0)
                                            @foreach($sellers as $seller)
                                                <option value="{{ $seller->id }}" selected>{{ $seller->full_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="input-label">{{ trans('update.customer') }}</label>
                                    <select name="customer_ids[]" multiple="multiple" data-search-option="just_student_role" class="form-control search-user-select2"
                                            data-placeholder="{{ trans('public.search_user') }}">

                                        @if(!empty($customers) and $customers->count() > 0)
                                            @foreach($customers as $customer)
                                                <option value="{{ $customer->id }}" selected>{{ $customer->full_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group mt-1">
                                    <label class="input-label mb-4"> </label>
                                    <input type="submit" class="text-center btn btn-primary w-100" value="{{ trans('admin/main.show_results') }}">
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
                            @can('admin_store_products_orders_export')
                                <a href="/admin/store/orders/export?{{ !empty($inHouseOrders) ? 'in-house-orders=true&' : '' }}{{ http_build_query(request()->all()) }}" class="btn btn-primary">{{ trans('admin/main.export_xls') }}</a>
                            @endcan
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped font-14">
                                    <tr>
                                        <th>#</th>
                                        <th class="text-left">{{ trans('update.customer') }}</th>
                                        <th class="text-left">{{ trans('admin/main.seller') }}</th>
                                        <th>{{ trans('admin/main.type') }}</th>
                                        <th>{{ trans('update.quantity') }}</th>
                                        <th>{{ trans('admin/main.paid_amount') }}</th>
                                        <th>{{ trans('admin/main.discount') }}</th>
                                        <th>{{ trans('admin/main.tax') }}</th>
                                        <th>{{ trans('admin/main.date') }}</th>
                                        <th>{{ trans('admin/main.status') }}</th>
                                        <th width="120">{{ trans('admin/main.actions') }}</th>
                                    </tr>

                                    @foreach($orders as $order)
                                        <tr>
                                            <td>{{ $order->id }}</td>

                                            <td class="text-left">
                                                {{ !empty($order->buyer) ? $order->buyer->full_name : '' }}
                                                <div class="text-primary text-small font-600-bold">ID : {{  !empty($order->buyer) ? $order->buyer->id : '' }}</div>
                                            </td>

                                            <td class="text-left">
                                                {{ !empty($order->seller) ? $order->seller->full_name : '' }}
                                                <div class="text-primary text-small font-600-bold">ID : {{  !empty($order->seller) ? $order->seller->id : '' }}</div>
                                            </td>

                                            <td>
                                                @if(!empty($order->product))
                                                    <span>{{ trans('update.product_type_'.$order->product->type) }}</span>
                                                @endif
                                            </td>

                                            <td>
                                                <span>{{ $order->quantity }}</span>
                                            </td>

                                            <td>
                                                @if(!empty($order->sale))
                                                    <span class="">{{ addCurrencyToPrice(handlePriceFormat($order->sale->total_amount)) }}</span>
                                                @endif
                                            </td>

                                            <td>
                                                @if(!empty($order->sale))
                                                    <span class="">{{ addCurrencyToPrice(handlePriceFormat($order->sale->discount)) }}</span>
                                                @endif
                                            </td>

                                            <td>
                                                @if(!empty($order->sale))
                                                    <span class="">{{ addCurrencyToPrice(handlePriceFormat($order->sale->tax)) }}</span>
                                                @endif
                                            </td>

                                            <td>{{ dateTimeFormat($order->created_at, 'j F Y H:i') }}</td>

                                            <td>
                                                @if($order->status == \App\Models\ProductOrder::$waitingDelivery)
                                                    <span class="text-warning">{{ trans('update.product_order_status_waiting_delivery') }}</span>
                                                @elseif($order->status == \App\Models\ProductOrder::$success)
                                                    <span class="text-dark-blue">{{ trans('update.product_order_status_success') }}</span>
                                                @elseif($order->status == \App\Models\ProductOrder::$shipped)
                                                    <span class="text-warning">{{ trans('update.product_order_status_shipped') }}</span>
                                                @elseif($order->status == \App\Models\ProductOrder::$canceled)
                                                    <span class="text-danger">{{ trans('update.product_order_status_canceled') }}</span>
                                                @endif
                                            </td>

                                            <td>
                                                @can('admin_store_products_orders_invoice')
                                                    @if(!empty($order->product))
                                                        <a href="/admin/store/orders/{{ $order->id }}/invoice" target="_blank" title="{{ trans('admin/main.invoice') }}"><i class="fa fa-print" aria-hidden="true"></i></a>
                                                    @endif
                                                @endcan

                                                @can('admin_store_products_orders_refund')
                                                    @include('admin.includes.delete_button',[
                                                            'url' => '/admin/store/orders/'. $order->id .'/refund',
                                                            'tooltip' => trans('admin/main.refund'),
                                                            'btnIcon' => 'fa-times-circle'
                                                        ])
                                                @endcan

                                                @if($order->status == \App\Models\ProductOrder::$waitingDelivery)
                                                    @can('admin_store_products_orders_tracking_code')
                                                        <button type="button"
                                                                data-sale-id="{{ $order->sale_id }}"
                                                                data-product-order-id="{{ $order->id }}"
                                                                data-toggle="tooltip" title="{{ trans('update.enter_tracking_code') }}"
                                                                class="js-enter-tracking-code btn-transparent text-primary">
                                                            <i class="fa fa-map"></i>
                                                        </button>
                                                    @endcan
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach

                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $orders->appends(request()->input())->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection

@push('scripts_bottom')
    <script>
        var enterTrackingCodeModalTitleLang = '{{ trans('update.enter_tracking_code') }}';
        var trackingCodeLang = '{{ trans('update.tracking_code') }}';
        var addressLang = '{{ trans('update.address') }}';
        var saveLang = '{{ trans('public.save') }}';
        var closeLang = '{{ trans('public.close') }}';
        var trackingCodeSaveSuccessLang = '{{ trans('update.tracking_code_success_save') }}';
    </script>

    <script src="/assets/default/vendors/sweetalert2/dist/sweetalert2.min.js"></script>
    <script src="/assets/default/js/admin/store/orders.min.js"></script>
@endpush
