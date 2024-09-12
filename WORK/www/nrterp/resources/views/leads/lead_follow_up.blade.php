
@if(isset($follow_up))
    {{ Form::model($follow_up, array('route' => array('leads.follow_up.update', $lead->id, $follow_up->id), 'method' => 'PUT')) }}
@else
    {{ Form::open(array('route' => ['leads.follow_up.store',$lead->id])) }}
@endif

<div class="modal-body">
    {{-- start for ai module--}}
    @php
        $plan= \App\Models\Utility::getChatGPTSettings();
    @endphp
    @if($plan->chatgpt == 1)
    <div class="text-end">

        <strong>Note: You have an open follow-up for this lead. If you create a new one, the old one will be automatically closed.</strong>
        <a href="#" data-size="md" class="btn  btn-primary btn-icon btn-sm" data-ajax-popup-over="true" data-url="{{ route('generate',['lead_follow_up']) }}"
           data-bs-placement="top" data-title="{{ __('Generate content with AI') }}">
            <i class="fas fa-robot"></i> <span>{{__('Generate with AI')}}</span>
        </a>

    </div>
    @endif
    {{-- end for ai module--}}
    <div class="row">
        <div class="col-12 form-group">
            {{ Form::label('title', __('Title'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            {{ Form::text('title', null, array('class' => 'form-control','required'=>'required')) }}
        </div>
        
        <div class="col-12 form-group">
            {{ Form::label('description', __('Description'),['class'=>'form-label']) }}
            {{ Form::textarea('description',null, array('class' => 'summernote-simple')) }}
        </div>
        <div class="col-3 form-group">
            {{ Form::label('stage_id', __('Stage'),['class'=>'form-label']) }}
            {!! Form::select('stage_id', ['' => __('Select Stage')] + $lead_stages, null, ['class' => 'form-control', 'id' => 'stage_id']) !!}
        </div>
        <div class="col-3 form-group">
            {{ Form::label('date', __('Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
             {!! Form::date('date',null, ['class' => 'form-control','id'=>'date','required' => 'required']) !!}

        </div>
        <div class="col-3 form-group">
            {{ Form::label('time', __('Time'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            {{ Form::time('time', null, array('class' => 'form-control','id'=>'time','required'=>'required')) }}
        </div>
        @if(isset($follow_up))
         <div class="col-3 form-group">
            {{ Form::label('status', __('Status'),['class'=>'form-label']) }}
            <select name="status" class="form-control">
                <option value="" selected>Select Status</option>
                <option value="1" {{ ($follow_up->status=='1') ? 'selected' :''}}>Closed</option>
                <option value="0" {{ ($follow_up->status=='0') ? 'selected' :''}}>Opened</option>
            </select>
        </div>
         @endif
        
    </div>
</div>

<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    @if(isset($follow_up))
        <input type="submit" value="{{__('Update')}}" class="btn  btn-primary">
    @else
        <input type="submit" value="{{__('Add')}}" class="btn  btn-primary">
    @endif
</div>

{{Form::close()}}



<script>


    $('#date').daterangepicker({
        locale: {format: 'YYYY-MM-DD'},
        singleDatePicker: true,
    });
    $("#time").timepicker({
        icons: {
            up: 'ti ti-chevron-up',
            down: 'ti ti-chevron-down'
        }
    });
   

   
</script>