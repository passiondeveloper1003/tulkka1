<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>{{ $pageTitle ?? '' }} </title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- General CSS File -->
    <link rel="stylesheet" href="/assets/admin/vendor/bootstrap/bootstrap.min.css"/>
    <!-- <link rel="stylesheet" href="/assets/admin/vendor/bootstrap-5.0.2/css/bootstrap.min.css"/> -->
    <link rel="stylesheet" href="/assets/vendors/fontawesome/css/all.min.css"/>


    <link rel="stylesheet" href="/assets/admin/css/style.css">
    <link rel="stylesheet" href="/assets/admin/css/custom.css">
    <link rel="stylesheet" href="/assets/admin/css/components.css">

    <style>
        {!! !empty(getCustomCssAndJs('css')) ? getCustomCssAndJs('css') : '' !!}
    </style>
</head>
<body>

<div id="app">
    <section class="section">
        <div class="container mt-5">
            <div class="row">
                <div class="col-12 col-md-10 offset-md-1 col-lg-10 offset-lg-1">

                    <div class="card card-primary">
                        <div class="row m-0">
                            <div class="col-12 col-md-12">
                                <div class="card-body">

                                    <div class="section-body">
                                        <div class="invoice">
                                            <div class="invoice-print">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="invoice-title">
                                                            <h2>{{ $generalSettings['site_name'] }}</h2>
                                                            <div class="invoice-number">{{ trans('public.item_id') }}: #{{ $product->id }}</div>
                                                        </div>
                                                        <hr>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <address>
                                                                    <strong>{{ trans('admin/main.buyer') }}:</strong>
                                                                    <br>
                                                                    {{ $buyer->full_name }}
                                                                </address>
                                                            </div>
                                                            <div class="col-md-6 text-md-right">
                                                                <address>
                                                                    <strong>{{ trans('home.platform_address') }}:</strong><br>
                                                                    {!! nl2br(getContactPageSettings('address')) !!}
                                                                </address>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <address>
                                                                    <strong>{{ trans('admin/main.seller') }}:</strong><br>
                                                                    {{ $seller->full_name }}
                                                                </address>
                                                            </div>

                                                            <div class="col-md-6 text-md-right">
                                                                <address>
                                                                    <strong>{{ trans('panel.purchase_date') }}:</strong><br>
                                                                    {{ dateTimeFormat($sale->created_at,'Y M j | H:i') }}<br><br>
                                                                </address>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row mt-4">
                                                    <div class="col-md-12">
                                                        <div class="section-title">{{ trans('home.order_summary') }}</div>
                                                        <div class="table-responsive">
                                                            <table class="table table-striped table-hover table-md">
                                                                <tr>
                                                                    <th data-width="40">#</th>
                                                                    <th class="text-center">{{ trans('admin/main.type') }}</th>
                                                                    <th class="text-center">{{ trans('public.price') }}</th>
                                                                    <th class="text-center">{{ trans('panel.discount') }}</th>
                                                                    <th class="text-right">{{ trans('cart.total') }}</th>
                                                                </tr>

                                                                <tr>
                                                                    <td>{{ $product->id }}</td>
                                                                    <td class="text-center">{{ trans('update.product_type_'.$product->type) }}</td>
                                                                    <td class="text-center">
                                                                        @if(!empty($sale->amount))
                                                                            {{ addCurrencyToPrice($sale->amount) }}
                                                                        @else
                                                                            {{ trans('public.free') }}
                                                                        @endif
                                                                    </td>
                                                                    <td class="text-center">
                                                                        @if(!empty($sale->discount))
                                                                            {{ addCurrencyToPrice($sale->discount) }}
                                                                        @else
                                                                            -
                                                                        @endif
                                                                    </td>
                                                                    <td class="text-right">
                                                                        @if(!empty($sale->total_amount))
                                                                            {{ addCurrencyToPrice($sale->total_amount) }}
                                                                        @else
                                                                            0
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div class="row mt-4">

                                                            <div class="col-lg-6 text-left">
                                                                <div class="invoice-detail-item">
                                                                    <div class="invoice-detail-name">{{ trans('admin/main.item') }}</div>
                                                                    <div class="invoice-detail-value">{{ $product->title }}</div>
                                                                </div>

                                                                <div class="invoice-detail-item">
                                                                    <div class="invoice-detail-name">{{ trans('update.quantity') }}</div>
                                                                    <div class="invoice-detail-value">{{ $order->quantity }} {{ trans('cart.item') }}</div>
                                                                </div>

                                                                @if(!empty($order->specifications))
                                                                    <div class="invoice-detail-item">
                                                                        <div class="invoice-detail-name">{{ trans('update.specifications') }}</div>

                                                                        @foreach(json_decode($order->specifications,true) as $specificationKey => $specificationValue)
                                                                            <div class="invoice-detail-value">
                                                                                <span class="">{{ $specificationKey }}</span>
                                                                                <span class="ml-3">{{ $specificationValue }}</span>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                @endif

                                                                @if(!empty($order->message_to_seller))
                                                                    <div class="invoice-detail-item">
                                                                        <div class="invoice-detail-name">{{ trans('update.message_to_seller') }}</div>
                                                                        <div class="invoice-detail-value">{!! $order->message_to_seller !!}</div>
                                                                    </div>
                                                                @endif
                                                            </div>

                                                            <div class="col-lg-6 text-right">
                                                                <div class="invoice-detail-item">
                                                                    <div class="invoice-detail-name">{{ trans('cart.sub_total') }}</div>
                                                                    <div class="invoice-detail-value">{{ addCurrencyToPrice($sale->amount) }}</div>
                                                                </div>
                                                                <div class="invoice-detail-item">
                                                                    <div class="invoice-detail-name">{{ trans('cart.tax') }} ({{ getFinancialSettings('tax') }}%)</div>
                                                                    <div class="invoice-detail-value">
                                                                        @if(!empty($sale->tax))
                                                                            {{ addCurrencyToPrice($sale->tax) }}
                                                                        @else
                                                                            -
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="invoice-detail-item">
                                                                    <div class="invoice-detail-name">{{ trans('public.discount') }}</div>
                                                                    <div class="invoice-detail-value">
                                                                        @if(!empty($sale->discount))
                                                                            {{ addCurrencyToPrice($sale->discount) }}
                                                                        @else
                                                                            -
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <hr class="mt-2 mb-2">
                                                                <div class="invoice-detail-item">
                                                                    <div class="invoice-detail-name">{{ trans('cart.total') }}</div>
                                                                    <div class="invoice-detail-value invoice-detail-value-lg">
                                                                        @if(!empty($sale->total_amount))
                                                                            {{ addCurrencyToPrice($sale->total_amount) }}
                                                                        @else
                                                                            -
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="text-md-right">

                                                <button type="button" onclick="window.print()" class="btn btn-warning btn-icon icon-left"><i class="fas fa-print"></i> Print</button>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
</div>
</body>
