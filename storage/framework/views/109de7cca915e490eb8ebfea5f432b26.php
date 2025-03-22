<?php $__env->startPush('css'); ?>
<style type="text/css">
    html,
    body {
        background: #f0f0f0;
        color: #1a2b48;
        font-family: Poppins, sans-serif;
    }

    .bravo_topbar,
    .bravo_header,
    .bravo_footer {
        display: none;
    }

    .invoice-amount {
        margin-top: 15px;
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 10px 20px;
        display: inline-block;
        text-align: center;
    }

    .email_new_booking .b-table {
        width: 100%;
    }

    .email_new_booking .val {
        text-align: right;
    }

    .email_new_booking td,
    .email_new_booking th {
        padding: 5px;
    }

    .email_new_booking .val table {
        text-align: left;
    }

    .email_new_booking .b-panel-title,
    .email_new_booking .booking-number,
    .email_new_booking .booking-status,
    .email_new_booking .manage-booking-btn {
        display: none;
    }

    .email_new_booking .fsz21 {
        font-size: 18px;
    }

    .table-service-head {
        border: 1px solid #ddd;
        background-color: #f9f9f9;
    }

    .table-service-head th {
        padding: 5px 15px;
    }

    #invoice-print-zone {
        background: white;
        padding: 15px;
        margin: 90px auto 40px auto;
        max-width: 1025px;
    }

    .invoice-company-info {
        margin-top: 15px;
    }

    .invoice-company-info p {
        margin-bottom: 2px;
        font-weight: normal;
    }

    /* 28-12-2024 */
    .hed {
        background: #33b28c;
        padding: 6px;
        color: #fff;
    }

    .bg_bx td {
        background: #2fb38d24;
    }

    .tour_name a {
        color: #2fb38d;
    }

    .in_disc{
        padding: 0;
        text-align: left;
        list-style: none;
        margin-top: 0;
        margin-bottom: 10px;
    }

    .in_disc li{
        margin-bottom: 3px;
    }

    .invice_disc h4{
        margin-bottom: 0.6rem;
    }

    .invice_disc h4{
        font-size: 20px;
        font-weight: 500;
        margin-bottom: 0;
    }

    .invice_disc p, .invice_disc span strong{
        font-size: 14px;
        font-weight: 400;
        text-align: left;
    }

    .image_bx{
        column-count: 4; /* Define the number of columns by default */
        column-gap: 16px; /* Space between columns */
    }

    img{
        width: 100%;
    }

    /* .bord_top{
            border-top: 1px solid #33b28c;
        } */
</style>
<link href="<?php echo e(asset('module/user/css/user.css')); ?>" rel="stylesheet">
<script>
    window.print();
</script>
<div id="invoice-print-zone">
    <table width="100%" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <th width="50%" align="left" class="text-left">
                     <?php if( !empty($logo = setting_item('logo_invoice_id') ?? setting_item('logo_id') )): ?>
                        <img style="max-width: 200px;" src="<?php echo e(public_path( 'uploads/0000/7/2024/10/10/riyadh-trips-logo-dark.png')); ?>" alt="<?php echo e(setting_item("site_title")); ?>">
                    <?php endif; ?>
                    
                    <div class="invoice-company-info">
                        <?php echo setting_item_with_lang("invoice_company_info"); ?>

                    </div>
                </th>
                <th width="50%" align="right" class="text-right">
                   
                </th>
            </tr>
            <tr>
                <th width="50%">
                    <?php echo nl2br(setting_item('invoice_company')); ?>

                </th>
                <th width="50%" align="right" class="text-right">
                    
                </th>
            </tr>
        </thead>
    </table>
    <br>
   
        
       
        
        
        
    
    
    
    <?php if(!empty($service->email_new_booking_file_downloads)): ?>
    <div class="email_new_booking">
        <?php echo $__env->make($service->email_new_booking_file_downloads ?? '', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopPush(); ?>
<?php $__env->startPush('js'); ?>
<script type="text/javascript" src="<?php echo e(asset(" module/user/js/user.js")); ?>"></script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('Layout::empty', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/riyaoeiu/public_html/themes/Base/User/Views/frontend/bookingDownloads.blade.php ENDPATH**/ ?>