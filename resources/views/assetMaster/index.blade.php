@extends('layouts.admin')

@section('page-title')
    {{__('Manage Asset Master')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Asset Master')}}</li>
@endsection

@section('action-button')
    <div class="all-button-box row d-flex justify-content-end">
        @can('create award')
            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-6">
            <a href="#" data-url="{{ route('assetmaster.create') }}" class="btn btn-xs btn-white btn-icon-only width-auto" data-ajax-popup="true" data-title="{{__('Create Asset Master')}}">
                <i class="fa fa-plus"></i> {{__('Create')}}
            </a>
            </div>

        @endcan
    </div>
@endsection
@section('action-btn')
    <div class="float-end">
        @can('create award')
        <a href="#" data-size="lg" data-url="{{ route('assetmaster.create') }}" data-ajax-popup="true"
           data-bs-toggle="tooltip" title="{{__('Create')}}" data-title="{{__('Create Asset Master')}}" class="btn btn-sm btn-primary"> 
            <i class="ti ti-plus"></i>
        </a>


        @endcan
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
            <div class="card-body table-border-style">
                    <div class="table-responsive">
                    <table class="table datatable">
                            <thead>
                            <tr>
                                <th>{{__('Unique Id')}}</th>
                                <th>{{__('Name')}}</th>
                                <th>{{__('Price')}}</th>
                                <th>{{__('Quantity')}}</th>
                                <th>{{__('Purchase Date')}}</th>
                                <th>{{__('Supported Date')}}</th>
                                <th>{{__('Description')}}</th>
                                <th>{{__('Date')}}</th>
                                @if(Gate::check('edit award') || Gate::check('delete award'))
                                    <th width="200px">{{__('Action')}}</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody class="font-style">
                            @foreach ($assetMasters as $asset)
                                <tr>
                                    <td>{{ $asset->unique_id }}</td>
                                    <td>{{ $asset->name }}</td>
                                    <td>{{ $asset->price }}</td>
                                    <td>{{ $asset->available_quantity }}</td>
                                    <td class="font-style">{{ \Auth::user()->dateFormat($asset->purchase_date) }}</td>
                                    <td class="font-style">{{ \Auth::user()->dateFormat($asset->supported_date) }}</td>
                                    <td>{{ $asset->description }}</td>
                                    <td>{{  \Auth::user()->dateFormat($asset->created_at )}}</td>
                                
                                    @if(Gate::check('edit award') || Gate::check('delete award'))
                                        <td class="Action">
                                            <span>
                                                @can('edit award')
                                                <div class="action-btn bg-primary ms-2">
                                                    <a href="#" data-url="{{ URL::to('assetmaster/'.$asset->id.'/edit') }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Edit Asset Master')}}" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-original-title="{{__('Edit')}}">
                                                        <i class="ti ti-pencil text-white"></i></a>
                                                </div>
                                                    @endcan
                                                @can('delete award')
                                                <div class="action-btn bg-danger ms-2">
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['assetmaster.destroy', $asset->id],'id'=>'delete-form-'.$asset->id]) !!}
                                                 <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$asset->id}}').submit();">
                                                     <i class="ti ti-trash text-white"></i></a>
                                                    {!! Form::close() !!}
                                                    </div>
                                                @endcan
                                            </span>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
