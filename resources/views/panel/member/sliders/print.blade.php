@extends('layouts.empty')
@section('title', 'Slider Print')
@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <table id="article_table" class="table">
                        <thead>
                            <tr>
                                <th class="col-1">@lang('admin/tooltip.iD') </th>
                                <th class="col-2"> @lang('admin/ui.title')</th>
                                <th class="col-3"> @lang('admin/tooltip.visibility') </th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (@$sliders->count() > 0)
                                @foreach (@$sliders as $slider)
                                    <tr>
                                        <td class="col-2">{{ @$slider->getPrefix() }}</td>
                                        <td class="col-1">{{ @$slider->title ?? '--' }}</td>
                                        <td class="col-1">{{ @$slider->status == 1 ? 'Published' : 'Unpublished' }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center" colspan="8">@include('panel.admin.include.components.no-data-img')</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
