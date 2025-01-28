@extends('layouts.main')
@section('title', 'Cron Diagnosis')
@section('content')
    @php
        $openvasShown = false;
        $openzapShown = false;
        $breadcrumb_arr = [
            ['name'=>'Notes', 'url'=> "javascript:void(0);", 'class' => 'active']
        ];
        $laravelCrons = getAllCrons(); 

    @endphp
    <!-- push external head elements to head -->
    @push('head')
    @endpush
    <div class="container-fluid" id="container">
       
      <div class="row">
       
        <div class="col-lg-12 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                        <h3>Crons</h3>
                </div>
                <div class="card-body p-0">
                  <div class="table-responsive">
                    <table id="lead_table" class="table">
                        <thead>
                            <tr>
                                <th class="text-center" width="5%">Sno.</th>
                                <th class="no-export" width="65%">Name</th>
                                <th class="col_2" width="10%">Duration</th>
                                <th class="col_2" width="15%">Run At</th>
                                <th class="col_4" width="10%">Run</th>
                            </tr>
                        </thead>
                        <tbody>
                          @foreach ($laravelCrons as $cron)
                            @php
                                $runAt = \Carbon\Carbon::parse(getSetting($cron['run_at']));
                                $isLessThanDurationMinutes = $runAt->diffInMinutes(now()) < ($cron['duration'] ?? 20);
                            @endphp
                            <tr>
                              <td class="text-center">{{$loop->iteration}}</td>
                              <td>
                                  <h6 class="mb-0 fw-800" title="{{ $cron['short_description'] }}">
                                      {{$cron['name']}}
                                      @if($isLessThanDurationMinutes)
                                          <i class="fa fa-check-circle text-success"></i>
                                      @else
                                          <i class="fa fa-times text-danger"></i>
                                      @endif
                                  </h6>
                                  @if($cron['short_description'])
                                      <span class="text-muted">{{ $cron['short_description'] }}</span>
                                  @endif
                                </div>
                              </td>
                              <td>
                                <span class="text-muted">{{ convertMinutes($cron['duration']) }}</span>
                              </td>
                              <td>
                                  <span class="text-muted">{{  $runAt->diffForHumans()  }}</span>
                              </td> 
                              <td>
                                <a href="{{url($cron['url'])}}" target="_blank" class="btn btn-icon btn-primary btn-sm ml-0"><i class="ik ik-play"></i></a>
                              </td>
                            </tr>
                          @endforeach
                        </tbody>   
                    </table>
                </div>
                </div>
            </div>
        </div>
      </div>
    </div>
 @endsection

