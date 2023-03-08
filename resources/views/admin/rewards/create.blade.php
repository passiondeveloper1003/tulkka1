@extends('admin.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/sweetalert2/dist/sweetalert2.min.css">
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('update.rewards') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="/admin/">{{ trans('admin/main.dashboard') }}</a></div>
                <div class="breadcrumb-item">{{ trans('update.rewards') }}</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">

                        <div class="card-header">
                            @can('admin_rewards_items')
                                <button type="button" class="js-add-new-reward btn btn-success btn-sm">
                                    <i class="fa fa-plus"></i>
                                    {{ trans('update.new_condition') }}
                                </button>
                            @endcan
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped font-14">
                                    <tr>
                                        <th>{{ trans('admin/main.title') }}</th>
                                        <th>{{ trans('update.score') }}</th>
                                        <th>{{ trans('admin/main.status') }}</th>
                                        <th>{{ trans('admin/main.created_at') }}</th>
                                        <th>{{ trans('admin/main.actions') }}</th>
                                    </tr>

                                    @if(!empty($rewards))
                                        @foreach($rewards as $reward)
                                            <tr>
                                                <td>{{ trans('update.reward_type_'.$reward->type) }}</td>
                                                <td>{{ $reward->score }}</td>
                                                <td>{{ trans('admin/main.'.$reward->status) }}</td>
                                                <td>{{ dateTimeFormat($reward->created_at,'j M Y') }}</td>
                                                <td>
                                                    @can('admin_rewards_items')
                                                        <button type="button" class="js-edit-reward btn-transparent btn-sm text-primary" data-id="{{ $reward->id }}" data-toggle="tooltip" data-placement="top" title="{{ trans('admin/main.edit') }}">
                                                            <i class="fa fa-edit"></i>
                                                        </button>
                                                    @endcan

                                                    @can('admin_rewards_item_delete')
                                                        @include('admin.includes.delete_button',['url' => '/admin/rewards/items/'.$reward->id.'/delete' , 'btnClass' => 'btn-sm'])
                                                    @endcan
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <div class="modal fade" id="rewardSettingModal" tabindex="-1" aria-labelledby="contactMessageLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{trans('update.new_condition')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label class="input-label">{{trans('update.condition')}}</label>
                            <select name="type" class="form-control">
                                <option selected disabled>--</option>

                                @foreach(\App\Models\Reward::getTypesLists() as $type)
                                    <option value="{{ $type }}">{{ trans('update.reward_type_'.$type) }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="js-score-input form-group">
                            <label class="input-label">{{trans('update.score')}}</label>
                            <input type="number" name="score" class="form-control"/>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="js-condition-input form-group d-none ">
                            <label class="input-label">{{trans('update.value')}}</label>
                            <input type="text" name="condition" class="form-control"/>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="status" id="statusSwitch" class="custom-control-input" checked>
                            <label class="custom-control-label" for="statusSwitch">{{ trans('admin/main.active') }}</label>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="js-save-reward btn btn-primary">{{trans('admin/main.save')}}</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('admin/main.close') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/sweetalert2/dist/sweetalert2.min.js"></script>
    <script>
        var saveSuccessLang = '{{ trans('webinars.success_store') }}';
    </script>

    <script src="/assets/default/js/admin/rewards_items.min.js"></script>
@endpush
