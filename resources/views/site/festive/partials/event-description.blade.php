<div class=" row p-0 py-3 border rounded-2 mb-10">
    <div class="col-xl-12">
        <div class="row p-0 rounded-2">
            
                <div class="col-lg-12">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h1 class="text-dark fs-30 mb-0">About {{ $event->name }}! </h1>
                        </div>
                    </div>
                    <p class="fs-18 lh-xs mb-0">{!! $event->description  !!} </p>
                    <span>{{$event->keywords}}</span>
                </div>
            </div>
        </div>
    </div>

</div>
