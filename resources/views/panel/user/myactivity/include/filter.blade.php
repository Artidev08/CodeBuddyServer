<div class="side-slide" style="right: -100%;">
    <div class="filter">
       <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5 class=" mb-0" style="
            font-size: 16px;
            font-weight: 500;
            text-transform: initial;
             color: #737373;
             ">  @lang('user/ui.filter') </h5>
            <button type="button" class="close off-canvas mb-0" data-type="close" style="color: #7b7373;
            border: none;
            font-size: 14px;
            font-weight: 600;
        }">X</button>
        </div>
        <div class="card-body">
            <form class="d-flex" action="{{ route('panel.user.my-activity.index') }}" method="get" id="TableForm">
                <div class="row">
                    <div class="col-12 form-group">
                        <label for="">From</label>
                        <input type="date" name="from" class="form-control" value="{{ request()->get('from') }}">
                    </div>
                    <div class="col-12 form-group">
                        <label for="">To</label>
                        <input type="date" name="to" class="form-control" value="{{ request()->get('to') }}">
                    </div>

                    <div class="col-12" style="margin-top:10px;">
                        <button type="submit" class="btn btn-primary">@lang('user/ui.apply_filter')</button>
                        <a href="{{ route('panel.user.my-activity.index') }}" id="reset" type="button" class="btn btn-light ml-2">@lang('user/ui.reset')</a>
                    </div>
                </div>
            </form>            
        </div>
       </div>
    </div>
</div>
