@extends('layouts.admin')
@section('page-title')
    {{__('Manage Leads')}} @if($pipeline) - {{$pipeline->name}} @endif
@endsection

@push('css-page')
    <link rel="stylesheet" href="{{asset('css/summernote/summernote-bs4.css')}}">
@endpush
@push('script-page')
    <script src="{{asset('css/summernote/summernote-bs4.js')}}"></script>
    <script>
        $(document).on("change", ".change-pipeline select[name=default_pipeline_id]", function () {
            $('#change-pipeline').submit();
        });
    </script>
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Lead')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        <a href="{{ route('leads.index') }}" data-bs-toggle="tooltip" title="{{__('Kanban View')}}" class="btn btn-sm btn-primary">
            <i class="ti ti-layout-grid"></i>
        </a>
        <a href="#" data-size="md"  data-bs-toggle="tooltip" title="{{__('Import')}}" data-url="{{ route('leads.file.import') }}" data-ajax-popup="true" data-title="{{__('Import Lead CSV file')}}" class="btn btn-sm btn-primary">
            <i class="ti ti-file-import"></i>
        </a>
        <a href="{{route('leads.export')}}" data-bs-toggle="tooltip" title="{{__('Export')}}" class="btn btn-sm btn-primary">
            <i class="ti ti-file-export"></i>
        </a>
        <a href="#" data-size="lg" data-url="{{ route('leads.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create New User')}}" class="btn btn-sm btn-primary">
            <i class="ti ti-plus"></i>
        </a>
    </div>
@endsection

@section('content')
    @if($pipeline)
    <div class="row">
    <div class="col-sm-3">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mb-3 mb-sm-0">
                        <small class="text-muted">{{__('Total Follow Up')}}</small>
                        <h4 class="m-0">{{ $cnt_follow_up['total'] }}</h4>
                    </div>
                    <div class="col-auto">
                        <div class="theme-avtar bg-info">
                            <i class="ti ti-layers-difference"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mb-3 mb-sm-0">
                        <small class="text-muted">{{__('This Month Total Follow Up')}}</small>
                        <h4 class="m-0">{{ $cnt_follow_up['this_month'] }}</h4>
                    </div>
                    <div class="col-auto">
                        <div class="theme-avtar bg-primary">
                            <i class="ti ti-layers-difference"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mb-3 mb-sm-0">
                        <small class="text-muted">{{__('This Week Total Follow Up')}}</small>
                        <h4 class="m-0">{{ $cnt_follow_up['this_week'] }}</h4>
                    </div>
                    <div class="col-auto">
                        <div class="theme-avtar bg-warning">
                            <i class="ti ti-layers-difference"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mb-3 mb-sm-0">
                        <small class="text-muted">{{__('Last 30 Days Total Follow Up')}}</small>
                        <h4 class="m-0">{{ $cnt_follow_up['last_30days'] }}</h4>
                    </div>
                    <div class="col-auto">
                        <div class="theme-avtar bg-danger">
                            <i class="ti ti-layers-difference"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
               <div class="row">
                    <div class="col-2 form-group">
                    {{ Form::label('user_id', __('User'),['class'=>'form-label']) }}
                    {{ Form::select('user_id', $users,null, array('class' => 'form-control select','id'=>'user_filter')) }}
                   
                    </div>
                    <div class="col-2 form-group">
                    {{ Form::label('sources', __('Source'),['class'=>'form-label']) }}
                    {{ Form::select('sources', $sources,null, array('class' => 'form-control select','id'=>'source_filter')) }}
                   
                    </div>
                     <div class="col-2 form-group">
                    {{ Form::label('stage_id', __('Stage'),['class'=>'form-label']) }}
                    {{ Form::select('stage_id', $lead_stages,null, array('class' => 'form-control select','id'=>'stage_filter')) }}
                   
                    </div>
                     <div class="col-3 form-group">
                        {{ Form::label('From Date', __('From Date'),['class'=>'form-label']) }}
                        {{ Form::date('From Date',null, array('class' => 'form-control from_date','id'=>'from_date',)) }}
                        <span id="fromDate" style="color: red;"></span>
                    </div>
                    <div class="col-3 form-group">
                        {{ Form::label('To Date', __('To Date'),['class'=>'form-label']) }}
                        {{ Form::date('To Date',null, array('class' => 'form-control to_date','id'=>'to_date',)) }}
                        <span id="toDate"  style="color: red;"></span>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table datatable">
                                <thead>
                                <tr>
                                    <th>{{__('Name')}}</th>
                                    <th>{{__('Subject')}}</th>
                                    <th>{{__('Phone')}}</th>
                                    <th>{{__('Stage')}}</th>
                                    <th>{{__('Users')}}</th>
                                    <th>{{__('Action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($leads) > 0)
                                    @foreach ($leads as $lead)
                                        <tr>
                                            <td>{{ $lead->name }}</td>
                                            <td>{{ $lead->subject }}</td>
                                            <td>{{ $lead->phone }}</td>
                                            <td>{{  !empty($lead->stage)?$lead->stage->name:'-' }}</td>
                                            <td>
                                                @foreach($lead->users as $user)
                                                    <a href="#" class="btn btn-sm mr-1 p-0 rounded-circle">
                                                        <img alt="image" data-toggle="tooltip" data-original-title="{{$user->name}}" @if($user->avatar) src="{{asset('/storage/uploads/avatar/'.$user->avatar)}}" @else src="{{asset('/storage/uploads/avatar/avatar.png')}}" @endif class="rounded-circle " width="25" height="25">
                                                    </a>
                                                @endforeach
                                            </td>
                                            @if(Auth::user()->type != 'client')
                                                <td class="Action">
                                                    <span>
                                                    @can('view lead')
                                                            @if($lead->is_active)
                                                                <div class="action-btn bg-warning ms-2">
                                                                <a href="{{route('leads.show',$lead->id)}}" class="mx-3 btn btn-sm d-inline-flex align-items-center"  data-size="xl" data-bs-toggle="tooltip" title="{{__('View')}}" data-title="{{__('Lead Detail')}}">
                                                                    <i class="ti ti-eye text-white"></i>
                                                                </a>
                                                            </div>
                                                            @endif
                                                        @endcan
                                                        @can('edit lead')
                                                            <div class="action-btn bg-info ms-2">
                                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-url="{{ route('leads.edit',$lead->id) }}" data-ajax-popup="true" data-size="xl" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-title="{{__('Lead Edit')}}">
                                                                    <i class="ti ti-pencil text-white"></i>
                                                                </a>
                                                            </div>
                                                        @endcan
                                                         @can('edit lead')
                                                            <div class="action-btn bg-secondary ms-2">
                                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-url="{{ route('leads.follow_up',$lead->id) }}" data-ajax-popup="true" data-size="lg" data-bs-toggle="tooltip" title="{{__('Add Follow Up')}}" data-title="{{__('Add Follow Up')}}">
                                                                    <i class="ti ti-plus text-white"></i>
                                                                </a>
                                                            </div>
                                                        @endcan
                                                        @can('delete lead')
                                                            <div class="action-btn bg-danger ms-2">
                                                                {!! Form::open(['method' => 'DELETE', 'route' => ['leads.destroy', $lead->id],'id'=>'delete-form-'.$lead->id]) !!}
                                                                <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}" ><i class="ti ti-trash text-white"></i></a>

                                                                {!! Form::close() !!}
                                                             </div>

                                                        @endif
                                                    </span>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="font-style">
                                        <td colspan="6" class="text-center">{{ __('No data available in table') }}</td>
                                    </tr>
                                @endif

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection

@push('script-page')

<script>
    $(document).ready(function() {
        function fetchFilteredData() {
           // alert(1);
            var user = $('#user_filter').val();
            var source = $('#source_filter').val();
            var stage = $('#stage_filter').val();
            var fromDate = $('#from_date').val();
            var toDate = $('#to_date').val();
            
            $.ajax({
                url: '{{ route("leads.filtered") }}', // Update this route to your actual route
                method: 'GET',
                data: {
                    user: user,
                    source: source,
                    stage: stage,
                    from_date: fromDate,
                    to_date: toDate
                },
                success: function(response) {
                    // Assuming the response contains the HTML for the updated table
                    $('.datatable').html(response);
                },
                error: function(xhr) {
                    console.log('Error:', xhr.responseText);
                }
            });
        }
        
        // Attach change event to filter inputs
        $('#user_filter, #source_filter, #stage_filter, #from_date, #to_date').on('change', function() {
            fetchFilteredData();
        });
    });
</script>
@endpush
