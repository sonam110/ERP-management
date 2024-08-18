<?php echo e(Form::model($assetmaster,array('route' => array('assetmaster.update', $assetmaster->id), 'method' => 'PUT'))); ?>

<div class="modal-body">
    
    <?php
        $plan= \App\Models\Utility::getChatGPTSettings();
    ?>
    <?php if($plan->chatgpt == 1): ?>
    <div class="text-end">
        <a href="#" data-size="md" class="btn  btn-primary btn-icon btn-sm" data-ajax-popup-over="true" data-url="<?php echo e(route('generate',['assetmaster'])); ?>"
           data-bs-placement="top" data-title="<?php echo e(__('Generate content with AI')); ?>">
            <i class="fas fa-robot"></i> <span><?php echo e(__('Generate with AI')); ?></span>
        </a>
    </div>
    <?php endif; ?>
    
    <div class="row">
       <div class="form-group col-md-4 col-lg-4">
            <?php echo e(Form::label('name', __('Name'),['class'=>'form-label'])); ?>

            <?php echo e(Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter name'),'required'=>'required'))); ?>

        </div>
        <div class="form-group col-md-4 col-lg-4">
            <?php echo e(Form::label('price', __('Price'),['class'=>'form-label'])); ?>

            <?php echo e(Form::number('price', null, [
                'class' => 'form-control',
                'placeholder' => __('Enter Price'),
                'required' => 'required',
                'step' => '.01'
            ])); ?>

        </div>
       
        <div class="form-group col-md-4 col-lg-4">
            <?php echo e(Form::label('available_quantity',__('Available Quantity'),['class'=>'form-label'])); ?>

            <?php echo e(Form::number('available_quantity',null,array('class'=>'form-control','placeholder'=>__('Enter Available Quantity'),'required'=>'required','min'=>'0'))); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('purchase_date', __('Purchase Date'),['class'=>'form-label'])); ?>

            <?php echo e(Form::date('purchase_date',null, array('class' => 'form-control '))); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('supported_date', __('Supported Date'),['class'=>'form-label'])); ?>

            <?php echo e(Form::date('supported_date',null, array('class' => 'form-control '))); ?>

        </div>
        <div class="form-group col-md-12">
            <?php echo e(Form::label('description',__('Description'))); ?>

            <?php echo e(Form::textarea('description',null,array('class'=>'form-control','placeholder'=>__('Enter Description')))); ?>

        </div>

    </div>
</div>

<div class="modal-footer">
        <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn  btn-light" data-bs-dismiss="modal">
        <input type="submit" value="<?php echo e(__('Update')); ?>" class="btn  btn-primary">
</div>
<?php echo e(Form::close()); ?>

<?php /**PATH E:\WORK\www\nrtgo-erp-saas\resources\views/assetmaster/edit.blade.php ENDPATH**/ ?>