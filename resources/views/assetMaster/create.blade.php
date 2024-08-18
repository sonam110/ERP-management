{{Form::open(array('url'=>'assetmaster','method'=>'post'))}}
<div class="modal-body">
    {{-- start for ai module--}}
    @php
        $plan= \App\Models\Utility::getChatGPTSettings();
    @endphp
    @if($plan->chatgpt == 1)
    <div class="text-end">
        <a href="#" data-size="md" class="btn  btn-primary btn-icon btn-sm" data-ajax-popup-over="true" data-url="{{ route('generate',['assetmaster']) }}"
           data-bs-placement="top" data-title="{{ __('Generate content with AI') }}">
            <i class="fas fa-robot"></i> <span>{{__('Generate with AI')}}</span>
        </a>
    </div>
    @endif
    {{-- end for ai module--}}

    <div class="row">
       
        <div class="form-group col-md-4 col-lg-4">
            {{ Form::label('name', __('Name'),['class'=>'form-label'])}}
            {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter name'),'required'=>'required'))}}
        </div>
        <div class="form-group col-md-4 col-lg-4">
            {{ Form::label('price', __('Price'),['class'=>'form-label'])}}
            {{ Form::number('price', null, [
                'class' => 'form-control',
                'placeholder' => __('Enter Price'),
                'required' => 'required',
                'step' => '.01'
            ]) }}
        </div>
       
        <div class="form-group col-md-4 col-lg-4">
            {{Form::label('available_quantity',__('Available Quantity'),['class'=>'form-label'])}}
            {{Form::number('available_quantity',null,array('class'=>'form-control','placeholder'=>__('Enter Available Quantity'),'required'=>'required','min'=>'0')) }}
        </div>
         <div class="form-group col-md-6">
            {{ Form::label('purchase_date', __('Purchase Date'),['class'=>'form-label']) }}
            {{ Form::date('purchase_date','', array('class' => 'form-control')) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('supported_date', __('Supported Date'),['class'=>'form-label']) }}
            {{ Form::date('supported_date','', array('class' => 'form-control')) }}
        </div>
        <div class="form-group col-md-12">
            {{Form::label('description',__('Description'))}}
            {{Form::textarea('description',null,array('class'=>'form-control','placeholder'=>__('Enter Description')))}}
        </div>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn  btn-primary">
</div>

    {{Form::close()}}
