@extends('layouts.empty')
@section('title', 'SeoTag Print')
@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="table-responsive">
                    <table id="user_table" class="table p-0">
                        <thead>
                            <tr>
                                <th class="col_1">#</th>
                                <th class="col_2"> @lang('admin/ui.code') </th>
                                <th class="col_3"> @lang('admin/ui.title') </th>
                                <th class="col_4"> @lang('admin/ui.keyWord') </th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count(@$seoTags) > 0)
                            @foreach (@$seoTags as $seoTag)
                            <tr>
                                <td class="col_1">{{ @$seoTag->getPrefix() }}</td>
                                <td class="col_2">{{ @$seoTag->code ?? '--' }}</td>
                                <td class="col_3">{{ @$seoTag->title ?? '--' }}</td>
                                <td class="col_4">{{ @$seoTag->keyword ?? '--' }}</td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td class="text-center" colspan="8">
                                    @include('panel.admin.include.components.no-data-img')</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection