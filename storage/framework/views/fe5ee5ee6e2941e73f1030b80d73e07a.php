<?php echo e(Form::open(array('url' => 'account-assets'))); ?>

<div class="modal-body">
    
    <?php
        $plan= \App\Models\Utility::getChatGPTSettings();
    ?>
    <?php if($plan->chatgpt == 1): ?>
    <div class="text-end">
        <a href="#" data-size="md" class="btn  btn-primary btn-icon btn-sm" data-ajax-popup-over="true" data-url="<?php echo e(route('generate',['account asset'])); ?>"
           data-bs-placement="top" data-title="<?php echo e(__('Generate content with AI')); ?>">
            <i class="fas fa-robot"></i> <span><?php echo e(__('Generate with AI')); ?></span>
        </a>
    </div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-12 form-group">
            <?php echo e(Form::label('employee_id', __('Employee'),['class'=>'form-label'])); ?>

            <?php echo e(Form::select('employee_id', $employee,null, array('class' => 'form-control select2','id'=>'choices-multiple1'))); ?>

        </div>
        <!-- Sonam -->
        <div class="col-5 form-group">
            <?php echo e(Form::label('asset_master_id', __('Select Asset'),['class'=>'form-label'])); ?>

              <select class="form-control select2 asset-select" id="choices-multiple1"  required="required" name="asset_master_id[]"> 
                <option value="" selected>--Select--</option>
                <?php $__currentLoopData = $assetMasters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $aseetm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($aseetm->id); ?>" data-qty="<?php echo e($aseetm->available_quantity); ?>"> <?php echo e($aseetm->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </select>
              <span style="color:red;display:block" id="avqty" class="avqty"> Available quantity : 0</span>
        </div>
        <div class="col-5 form-group">
            <?php echo e(Form::label('assign_quantity', __('Quantity'),['class'=>'form-label'])); ?>

           <?php echo e(Form::number('assign_quantity[]',null,array('class'=>'form-control','placeholder'=>__('uantity'),'required'=> 'required','min'=>'1'))); ?>

        </div>
        <div class="col-2 form-group appenBtn">
           <label for="addbtn" class="form-label"></label>
           <button type="button"  class="btn  btn-primary" id="addButton">Add New</button>
        </div>
         <div id="textBoxContainer"></div>


    </div>
</div>
<div class="modal-footer">
    <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="<?php echo e(__('Create')); ?>" class="btn  btn-primary">
</div>
<?php echo e(Form::close()); ?>



<script>
    $(document).ready(function(){
        // Add text box
        $("#addButton").click(function(){
            var textBoxHtml = '<div class="row textBoxWrapper" > <div class="col-5 form-group"> <select class="form-control select2 asset-select" id="choices-multiple1" required="required" name="asset_master_id[]">option value="" selected>--Select--</option> <?php foreach ($assetMasters as $key => $row): ?> <option value="<?php echo $row->id ?>" data-qty="<?php echo $row->available_quantity ?>"><?php echo $row->name ?></option> <?php endforeach ?> </select> </div> <div class="col-5 form-group"><input class="form-control" placeholder="quantity" required="required" name="assign_quantity[]" type="number" id="assign_quantity" min="1"> </div> <div class="col-2 form-group"> <button type="button" class="removeButton btn btn-danger ">-</button> </div> </div>';
            $("#textBoxContainer").append(textBoxHtml);
        });

        // Remove text box
        $("#textBoxContainer").on("click", ".removeButton", function(){
            $(this).closest(".textBoxWrapper").remove();
        });
        

        
    });
 

</script>
<?php /**PATH E:\WORK\www\nrtgo-erp-saas\resources\views/assets/create.blade.php ENDPATH**/ ?>