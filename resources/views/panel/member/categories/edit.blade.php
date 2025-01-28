@extends('layouts.main')
@section('title', $category->getPrefix() .' Categories Edit')
@section('content')
@php

    if (@$category->level == 1) {
        $page_title = 'Categories';
        $arr = null;
    } elseif (@$category->level == 2) {
        $page_title = 'Sub Categories';
        $arr = ['name' => $parent->name, 'url' => route('panel.admin.categories.index', @$category->category_type_id), 'class' => ''];
    } elseif (@$category->level == 3) {
        $page_title = 'Sub Sub Categories';
        $pre = @$category->parent_id - 1;
        $arr = ['name' => $parent->name, 'url' => route('panel.admin.categories.index', [@$category->category_type_id, 'level' => '2', 'parent_id' => $pre]), 'class' => ''];
    }
    $breadcrumb_arr = [['name' => $label, 'url' => route('panel.admin.categories.index', @$category->category_type_id), 'class' => ''], ['name' => $category->getPrefix(), 'url' => route('panel.admin.categories.index', @$category->category_type_id), 'class' => ''], ['name' => 'Edit', 'url' => route('panel.admin.categories.index', @$category->category_type_id), 'class' => 'active']];
@endphp

<div class="container-fluid">
    <div class="page-header">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="ik ik-grid bg-blue"></i>
                    <div class="d-inline">
                        <h5> @lang('admin/ui.edit')  {{ @$label }}</h5>
                        <span> @lang('admin/ui.update_a_record_for')  {{ @$label }}</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                @include('panel.admin.include.breadcrumb')
            </div>
        </div>
    </div>
    <div class="row">

        <div class="col-md-8 mx-auto">
            <div class="card ">
                <div class="card-header">
                    <h3> @lang('admin/ui.Update') {{ @$label }}</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('panel.admin.categories.update', @$category->id) }}" method="post"
                        enctype="multipart/form-data">
                        @csrf

                        <x-input name="request_with" placeholder="Enter Name" type="hidden" tooltip=""
                        regex="" validation="" value="update" />
                        <x-input name="parent_id" placeholder="Enter Name" type="hidden" tooltip=""
                        regex="" validation="" value="{{ request()->get('parent_id') }}" />

                        <div class="row">
                            <div class="col-md-12 mx-auto">
                                <div class="row d-none">
                                    <div class="col-sm-6">
                                        <div
                                            class="form-group {{ @$errors->has('category_type_id') ? 'has-error' : '' }}">
                                            <label
                                                for="category_type_id"> @lang('admin/ui.category') </label>
                                            {!! getHelp('Publicly readable name') !!}
                                            <select name="category_type_id" id="category_type_id"
                                                class="form-control select2">
                                                <option value="" readonly required>
                                                        @lang('admin/ui.select_category') </option>
                                                @foreach (@$categoryTypes as $index => $categoryType)
                                                    <option value="{{ @$categoryType->id }}"
                                                        {{ @$categoryType->id == @$category->category_type_id ? 'selected' : '' }}>
                                                        {{ @$categoryType->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group {{ @$errors->has('level') ? 'has-error' : '' }}">
                                            <label for="level"> @lang('admin/ui.level') </label>
                                            {!! getHelp('Publicly readable name') !!}
                                            <select name="level" id="level" class="form-control select2">
                                                <option value="" readonly required>
                                                        @lang('admin/ui.level')
                                                </option>
                                                @foreach (@$types as $index => $item)
                                                    <option value="{{ @$index }}"
                                                        {{ @$index == @$category->level ? 'selected' : '--' }}>
                                                        {{ @$item['label'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                     
                                        <x-label name="name" validation="category_group_remark"
                                        tooltip="edit_sub_category" />
                                    <x-textarea rows="3" regex="short_description"
                                        validation="common_short_description"
                                        value="{{ @$category->name  }}" name="name" id="name"
                                        placeholder="Enter Name" />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group ">
                                            <x-label name="icon" validation="" tooltip="icons" />
                                            <br>
                                                <div class="">
                                                <div class="input-group col-xs-12">

                                                    <x-input name="icon" type="file" tooltip="" regex="" validation=""
                                                    value="" class="file-upload-default" />
                                                    <div class="input-group col-xs-12">
                                                            <x-input name="icon" type="text" placeholder="Upload Icon"
                                                            tooltip="icons" regex="" validation="" value=""
                                                            class="file-upload-info" disabled />
                                                        <span class="input-group-append">
                                                            <button class="file-upload-browse btn btn-success"
                                                                type="button"> @lang('admin/ui.upload') </button>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary floating-btn ajax-btn">
                            @lang('admin/ui.save_update') </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
