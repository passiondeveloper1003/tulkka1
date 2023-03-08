@extends('admin.layouts.app')

@push('styles_top')
    <link href="/assets/default/vendors/sortable/jquery-ui.min.css"/>
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{!empty($forum) ?trans('/admin/main.edit'): trans('admin/main.new') }} {{ trans('update.forum') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="/admin/">{{ trans('admin/main.dashboard') }}</a>
                </div>
                <div class="breadcrumb-item active">
                    <a href="/admin/forums">{{ trans('update.forum') }}</a>
                </div>
                <div class="breadcrumb-item">{{!empty($forum) ?trans('/admin/main.edit'): trans('admin/main.new') }}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12 col-md-6 col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <form action="/admin/forums/{{ !empty($forum) ? $forum->id.'/update' : 'store' }}"
                                  method="Post">
                                {{ csrf_field() }}

                                @if(!empty(getGeneralSettings('content_translate')))
                                    <div class="form-group">
                                        <label class="input-label">{{ trans('auth.language') }}</label>
                                        <select name="locale" class="form-control {{ !empty($forum) ? 'js-edit-content-locale' : '' }}">
                                            @foreach($userLanguages as $lang => $language)
                                                <option value="{{ $lang }}" @if(mb_strtolower(request()->get('locale', app()->getLocale())) == mb_strtolower($lang)) selected @endif>{{ $language }}</option>
                                            @endforeach
                                        </select>
                                        @error('locale')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                @else
                                    <input type="hidden" name="locale" value="{{ getDefaultLocale() }}">
                                @endif

                                <div class="form-group">
                                    <label class="input-label">{{ trans('admin/main.icon') }}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <button type="button" class="input-group-text admin-file-manager " data-input="icon" data-preview="holder">
                                                <i class="fa fa-upload"></i>
                                            </button>
                                        </div>
                                        <input type="text" name="icon" id="icon" value="{{ !empty($forum) ? $forum->icon : old('icon') }}" class="form-control @error('icon') is-invalid @enderror"/>
                                        <div class="invalid-feedback">@error('icon') {{ $message }} @enderror</div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>{{ trans('/admin/main.title') }}</label>
                                    <input type="text" name="title"
                                           class="form-control  @error('title') is-invalid @enderror"
                                           value="{{ !empty($forum) ? $forum->title : old('title') }}"
                                           placeholder="{{ trans('admin/main.choose_title') }}"/>
                                    @error('title')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>{{ trans('/admin/main.description') }}</label>
                                    <textarea type="text" name="description" rows="4"
                                              class="form-control  @error('description') is-invalid @enderror"
                                    >{{ !empty($forum) ? $forum->description : old('description') }}</textarea>
                                    @error('description')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="input-label d-block">{{ trans('admin/main.group') }}</label>
                                    <select name="group_id" class="form-control @error('group_id') is-invalid @enderror">
                                        <option value="">{{ trans('admin/main.all') }}</option>

                                        @foreach($userGroups as $userGroup)
                                            <option value="{{ $userGroup->id }}" @if(!empty($forum) and $forum->group_id == $userGroup->id) selected @endif>{{ $userGroup->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">@error('group_id') {{ $message }} @enderror</div>
                                </div>

                                <div class="form-group">
                                    <label class="input-label d-block">{{ trans('admin/main.role') }}</label>
                                    <select name="role_id" class="form-control @error('role_id') is-invalid @enderror">
                                        <option value="">{{ trans('admin/main.all') }}</option>

                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" @if(!empty($forum) and $forum->role_id == $role->id) selected @endif>{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="text-muted text-small mt-1">{{ trans('update.forum_group_role_hint') }}</div>
                                    <div class="invalid-feedback">@error('role_id') {{ $message }} @enderror</div>
                                </div>

                                <div class="form-group custom-switches-stacked">
                                    <label class="custom-switch pl-0 d-flex align-items-center">
                                        <input type="hidden" name="status" value="disabled">
                                        <input type="checkbox" name="status" id="forumStatusSwitch" value="active" {{ (empty($forum) or $forum->status == 'active') ? 'checked="checked"' : '' }} class="custom-switch-input"/>
                                        <span class="custom-switch-indicator"></span>
                                        <label class="custom-switch-description mb-0 cursor-pointer" for="forumStatusSwitch">{{ trans('admin/main.active') }}</label>
                                    </label>
                                </div>


                                <div class="form-group custom-switches-stacked">
                                    <label class="custom-switch pl-0 d-flex align-items-center">
                                        <input type="hidden" name="close" value="0">
                                        <input type="checkbox" name="close" id="forumCloseSwitch" value="1" {{ (!empty($forum) and $forum->close) ? 'checked="checked"' : '' }} class="custom-switch-input"/>
                                        <span class="custom-switch-indicator"></span>
                                        <label class="custom-switch-description mb-0 cursor-pointer" for="forumCloseSwitch">{{ trans('admin/main.closed') }}</label>
                                    </label>
                                    <div class="text-muted text-small mt-1">{{ trans('update.closed_forum_hint') }}</div>
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input id="hasSubCategory" type="checkbox" name="has_sub"
                                               class="custom-control-input" {{ (!empty($subForums) and !$subForums->isEmpty()) ? 'checked' : '' }}>
                                        <label class="custom-control-label"
                                               for="hasSubCategory">{{ trans('update.include_sub_forums') }}</label>
                                    </div>
                                </div>

                                <div id="subCategories" class="ml-0 {{ (!empty($subForums) and !$subForums->isEmpty()) ? '' : ' d-none' }}">
                                    <div class="d-flex align-items-center justify-content-between mb-4">
                                        <strong class="d-block">{{ trans('update.new_sub_forums') }}</strong>

                                        <button type="button" class="btn btn-success add-btn"><i class="fa fa-plus"></i> Add</button>
                                    </div>

                                    <ul class="draggable-lists list-group">

                                        @if((!empty($subForums) and !$subForums->isEmpty()))
                                            @foreach($subForums as $key => $subForum)
                                                <li class="form-group list-group  border rounded-lg p-2">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text cursor-pointer move-icon">
                                                                <i class="fa fa-arrows-alt"></i>
                                                            </div>
                                                        </div>

                                                        <input type="text" name="sub_forums[{{ $subForum->id }}][title]"
                                                               class="form-control w-auto flex-grow-1"
                                                               placeholder="{{ trans('admin/main.choose_title') }}"
                                                               value="{{ $subForum->title }}"
                                                        />

                                                        <div class="input-group-append">
                                                            <button type="button" class="btn remove-btn btn-danger"><i class="fa fa-times"></i></button>
                                                        </div>
                                                    </div>

                                                    <div class="form-group mb-0 mt-1">
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <button type="button" class="input-group-text admin-file-manager " data-input="icon_{{ $subForum->id }}" data-preview="holder">
                                                                    <i class="fa fa-upload"></i>
                                                                </button>
                                                            </div>
                                                            <input type="text" name="sub_forums[{{ $subForum->id }}][icon]" id="icon_{{ $subForum->id }}" class="form-control" value="{{ $subForum->icon }}" placeholder="{{ trans('admin/main.icon') }}"/>
                                                        </div>
                                                    </div>

                                                    <textarea name="sub_forums[{{ $subForum->id }}][description]"
                                                              class="form-control w-auto flex-grow-1 mt-1" placeholder="{{ trans('update.forum_description') }}">{{ $subForum->description }}</textarea>

                                                    <div class="form-group mb-0 mt-1">
                                                        <label class="input-label d-block mb-0">{{ trans('admin/main.group') }}</label>
                                                        <select name="sub_forums[{{ $subForum->id }}][group_id]" class="form-control">
                                                            <option value="">{{ trans('admin/main.all') }}</option>

                                                            @foreach($userGroups as $userGroup)
                                                                <option value="{{ $userGroup->id }}" @if(!empty($subForum) and $subForum->group_id == $userGroup->id) selected @endif>{{ $userGroup->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="form-group mb-0 mt-1">
                                                        <label class="input-label d-block mb-0">{{ trans('admin/main.role') }}</label>
                                                        <select name="sub_forums[{{ $subForum->id }}][role_id]" class="form-control">
                                                            <option value="">{{ trans('admin/main.all') }}</option>

                                                            @foreach($roles as $role)
                                                                <option value="{{ $role->id }}" @if(!empty($subForum) and $subForum->role_id == $role->id) selected @endif>{{ $role->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="form-group mb-0 mt-1 custom-switches-stacked">
                                                        <label class="custom-switch pl-0 d-flex align-items-center mb-0">
                                                            <input type="hidden" name="sub_forums[{{ $subForum->id }}][status]" value="disabled">
                                                            <input type="checkbox" name="sub_forums[{{ $subForum->id }}][status]" id="forumStatusSwitch_{{ $subForum->id }}" value="active" {{ (!empty($subForum) and $subForum->status == 'active') ? 'checked="checked"' : '' }} class="custom-switch-input"/>
                                                            <span class="custom-switch-indicator"></span>
                                                            <label class="custom-switch-description mb-0 cursor-pointer" for="forumStatusSwitch_{{ $subForum->id }}">{{ trans('admin/main.active') }}</label>
                                                        </label>
                                                    </div>


                                                    <div class="form-group mb-0 mt-1 custom-switches-stacked">
                                                        <label class="custom-switch pl-0 d-flex align-items-center mb-0">
                                                            <input type="hidden" name="sub_forums[{{ $subForum->id }}][close]" value="0">
                                                            <input type="checkbox" name="sub_forums[{{ $subForum->id }}][close]" id="forumCloseSwitch_{{ $subForum->id }}" value="1" {{ (!empty($subForum) and $subForum->close) ? 'checked="checked"' : '' }} class="custom-switch-input"/>
                                                            <span class="custom-switch-indicator"></span>
                                                            <label class="custom-switch-description mb-0 cursor-pointer" for="forumCloseSwitch_{{ $subForum->id }}">{{ trans('admin/main.close') }}</label>
                                                        </label>
                                                    </div>
                                                </li>
                                            @endforeach
                                        @endif
                                    </ul>
                                </div>

                                <div class="text-right mt-4">
                                    <button class="btn btn-primary">{{ trans('admin/main.submit') }}</button>
                                </div>
                            </form>

                            <li class="form-group main-row list-group d-none border rounded-lg p-2">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text cursor-pointer move-icon">
                                            <i class="fa fa-arrows-alt"></i>
                                        </div>
                                    </div>

                                    <input type="text" name="sub_forums[record][title]"
                                           class="form-control w-auto flex-grow-1"
                                           placeholder="{{ trans('admin/main.choose_title') }}"/>

                                    <div class="input-group-append">
                                        <button type="button" class="btn remove-btn btn-danger"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>

                                <div class="form-group mb-0 mt-1">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <button type="button" class="input-group-text admin-file-manager " data-input="icon_record" data-preview="holder">
                                                <i class="fa fa-upload"></i>
                                            </button>
                                        </div>
                                        <input type="text" name="sub_forums[record][icon]" id="icon_record" class="form-control" placeholder="{{ trans('admin/main.icon') }}"/>
                                    </div>
                                </div>

                                <textarea name="sub_forums[record][description]"
                                          class="form-control w-auto flex-grow-1 mt-1" placeholder="{{ trans('update.category_description') }}"></textarea>

                                <div class="form-group mb-0 mt-1">
                                    <label class="input-label d-block mb-0">{{ trans('admin/main.group') }}</label>
                                    <select name="sub_forums[record][group_id]" class="form-control">
                                        <option value="" selected disabled>{{ trans('admin/main.select_users_group') }}</option>

                                        @foreach($userGroups as $userGroup)
                                            <option value="{{ $userGroup->id }}">{{ $userGroup->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group mb-0 mt-1">
                                    <label class="input-label d-block">{{ trans('admin/main.role') }}</label>
                                    <select name="sub_forums[record][role_id]" class="form-control">
                                        <option value="">{{ trans('admin/main.all') }}</option>

                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group mb-0 mt-1 custom-switches-stacked">
                                    <label class="custom-switch pl-0 d-flex align-items-center mb-0">
                                        <input type="hidden" name="sub_forums[record][status]" value="disabled">
                                        <input type="checkbox" name="sub_forums[record][status]" id="forumStatusSwitch_record" value="active" checked="checked" class="custom-switch-input"/>
                                        <span class="custom-switch-indicator"></span>
                                        <label class="custom-switch-description mb-0 cursor-pointer" for="forumStatusSwitch_record">{{ trans('admin/main.active') }}</label>
                                    </label>
                                </div>


                                <div class="form-group mb-0 mt-1 custom-switches-stacked">
                                    <label class="custom-switch pl-0 d-flex align-items-center mb-0">
                                        <input type="hidden" name="sub_forums[record][close]" value="0">
                                        <input type="checkbox" name="sub_forums[record][close]" id="forumCloseSwitch_record" value="1" class="custom-switch-input"/>
                                        <span class="custom-switch-indicator"></span>
                                        <label class="custom-switch-description mb-0 cursor-pointer" for="forumCloseSwitch_record">{{ trans('admin/main.close') }}</label>
                                    </label>
                                </div>
                            </li>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/sortable/jquery-ui.min.js"></script>

    <script src="/assets/default/js/admin/categories.min.js"></script>
@endpush
