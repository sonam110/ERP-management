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
        @forelse($leads as $lead)
            <tr>
                <td>{{ $lead->name }}</td>
                <td>{{ $lead->subject }}</td>
                <td>{{ $lead->phone }}</td>
                <td>{{ $lead->stage->name ?? '-' }}</td>
                <td>
                    @foreach($lead->users as $user)
                        <a href="#" class="btn btn-sm mr-1 p-0 rounded-circle">
                            <img alt="image" data-toggle="tooltip" data-original-title="{{$user->name}}" src="{{ $user->avatar ? asset('/storage/uploads/avatar/'.$user->avatar) : asset('/storage/uploads/avatar/avatar.png') }}" class="rounded-circle" width="25" height="25">
                        </a>
                    @endforeach
                </td>
                <td class="Action">
                    <span>
                        @can('view lead')
                            @if($lead->is_active)
                                <div class="action-btn bg-warning ms-2">
                                    <a href="{{route('leads.show', $lead->id)}}" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-size="xl" data-bs-toggle="tooltip" title="{{__('View')}}" data-title="{{__('Lead Detail')}}">
                                        <i class="ti ti-eye text-white"></i>
                                    </a>
                                </div>
                            @endif
                        @endcan
                        @can('edit lead')
                            <div class="action-btn bg-info ms-2">
                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-url="{{ route('leads.edit', $lead->id) }}" data-ajax-popup="true" data-size="xl" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-title="{{__('Lead Edit')}}">
                                    <i class="ti ti-pencil text-white"></i>
                                </a>
                            </div>
                        @endcan
                        @can('edit lead')
                            <div class="action-btn bg-secondary ms-2">
                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-url="{{ route('leads.follow_up', $lead->id) }}" data-ajax-popup="true" data-size="lg" data-bs-toggle="tooltip" title="{{__('Add Follow Up')}}" data-title="{{__('Add Follow Up')}}">
                                    <i class="ti ti-plus text-white"></i>
                                </a>
                            </div>
                        @endcan
                        @can('delete lead')
                            <div class="action-btn bg-danger ms-2">
                                {!! Form::open(['method' => 'DELETE', 'route' => ['leads.destroy', $lead->id], 'id' => 'delete-form-'.$lead->id]) !!}
                                <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}"><i class="ti ti-trash text-white"></i></a>
                                {!! Form::close() !!}
                            </div>
                        @endcan
                    </span>
                </td>
            </tr>
        @empty
            <tr class="font-style">
                <td colspan="6" class="text-center">{{ __('No data available in table') }}</td>
            </tr>
        @endforelse
    </tbody>
</table>
