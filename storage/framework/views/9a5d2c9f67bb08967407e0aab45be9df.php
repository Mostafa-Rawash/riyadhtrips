<?php
$translation = $service->translate();
// print_r($translation);
$lang_local = app()->getLocale();
$tour_details = DB::table('bravo_tours')->where('id', $translation->origin_id)->get();

$query = DB::table('bravo_tours')->where('id', $translation->origin_id)->pluck('gallery');
$galleryArray = explode(',', $query);
$filePaths = DB::table('media_files')
    ->whereIn('id', $galleryArray)
    ->pluck('file_path');
    
?>

<div class="b-table-wrap">
    
    <br>
    <div class="customer-info invice_disc">
        <h4 class="hed"><strong><?php echo clean($translation->title); ?></strong></h4>
        <br>
        <br>
        
        <div class="image_bx">
            <?php 
                foreach ($filePaths as $filePath) {
                    ?>
                        <img style="width: 220px;" src="<?php echo e(public_path('uploads/' . $filePath)); ?>" alt="<?php echo e(setting_item('site_title')); ?>">
                    <?php 
                }
            ?>
        </div>
        
        <span><?php echo clean($translation->duration); ?></span>
        <span><?php echo clean($translation->content); ?></span>
        <h3>Included/Excluded</h3>
        <table class="box_flex">
            <tbody>
                <tr>
                    <td valign="top">
                        <ul class="in_disc">
                            <?php $__currentLoopData = $translation->include; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li> <img style="max-width: 12px; margin-right: 5px;" src="<?php echo e(public_path( 'uploads/check_icn.png')); ?>" alt="<?php echo e(setting_item("site_title")); ?>"> <?php echo e($item['title']); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </td>
                    <td valign="top">
                        <ul class="in_disc">
                        <?php $__currentLoopData = $translation->exclude; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li> <img style="max-width: 12px; margin-right: 5px;" src="<?php echo e(public_path( 'uploads/cross_icn.png')); ?>" alt="<?php echo e(setting_item("site_title")); ?>"> <?php echo e($item['title']); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>
<div class="text-center mt20">
    <a href="<?php echo e(route("user.booking_history")); ?>" target="_blank" class="btn btn-primary manage-booking-btn"><?php echo e(__('Manage Bookings')); ?></a>
</div>
<?php /**PATH /home/riyaoeiu/public_html/modules/Tour/Views/emails/new_booking_detail_downloads.blade.php ENDPATH**/ ?>