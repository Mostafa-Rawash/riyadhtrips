<?php
 if (!empty($translation->plans)) {
    $title = __("Sample Itinerary");
 }
?>
<?php if(!empty($title)): ?>
<div class="g-include-exclude">
    <h3 class="section-title"><?php echo e($title); ?></h3>
    <div class="row">
        <?php if($translation->plans): ?>
            <div class="col-lg-12 col-md-12">
                
                <ul class="nav nav-tabs" id="plansTab" role="tablist">
                    <?php $__currentLoopData = $translation->plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $planKey => $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(is_array($plan) && isset($plan['name']) && $planKey !== '__plan_number__'): ?>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?php echo e($loop->first ? 'active' : ''); ?>"
                                    id="plan-<?php echo e($planKey); ?>-tab"
                                    data-bs-toggle="tab" 
                                    data-bs-target="#plan-<?php echo e($planKey); ?>"
                                    type="button"
                                    role="tab"
                                    aria-controls="plan-<?php echo e($planKey); ?>"
                                    aria-selected="<?php echo e($loop->first ? 'true' : 'false'); ?>">
                                    <?php echo e($plan['name']); ?>

                                </button>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
                
                
                <div class="tab-content" id="plansTabContent">
                    <?php $__currentLoopData = $translation->plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $planKey => $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(is_array($plan) && isset($plan['steps']) && $planKey !== '__plan_number__'): ?>
                            <div class="tab-pane fade <?php echo e($loop->first ? 'show active' : ''); ?>"
                                id="plan-<?php echo e($planKey); ?>"
                                role="tabpanel"
                                aria-labelledby="plan-<?php echo e($planKey); ?>-tab">
                                <div class="accordion plan-steps" id="accordion-plan-<?php echo e($planKey); ?>">
                                    <?php $__currentLoopData = $plan['steps']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stepKey => $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if(is_array($step) && isset($step['title'])): ?>
                                        <div class="row accordion-parent">
                                        <span class="step-day"><?php echo e($step['day']); ?></span>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="heading-<?php echo e($planKey); ?>-<?php echo e($stepKey); ?>">
                                                    <button class="accordion-button collapsed" type="button" 
                                                    data-bs-toggle="collapse" 
                                                    data-bs-target="#collapse-<?php echo e($planKey); ?>-<?php echo e($stepKey); ?>" 
                                                    aria-expanded="false" 
                                                    aria-controls="collapse-<?php echo e($planKey); ?>-<?php echo e($stepKey); ?>">
                                                    <i class="fa fa-circle step-icon"></i>
                                                            <span class="step-title"><?php echo e($step['title']); ?></span>
                                                    </button>
                                                </h2>
                                                <div id="collapse-<?php echo e($planKey); ?>-<?php echo e($stepKey); ?>" 
                                                     class="accordion-collapse collapse" 
                                                     aria-labelledby="heading-<?php echo e($planKey); ?>-<?php echo e($stepKey); ?>" 
                                                     data-bs-parent="#accordion-plan-<?php echo e($planKey); ?>">
                                                    <div class="accordion-body">
                                                        <?php echo e($step['description']); ?>

                                                        <?php if(!empty($step['images'])): ?>
                                                        <div class="step-images">
                                                            <?php $__currentLoopData = $step['images']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <img src="<?php echo e($image); ?>" alt="<?php echo e($step['title']); ?>">
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </div>
                                                       
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        </div>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?><?php /**PATH /media/rawash/New Volume/Dev/Laravel-RiydhTrips/themes/BC/Tour/Views/frontend/layouts/details/tour-plans-steps.blade.php ENDPATH**/ ?>