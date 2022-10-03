<?php $__env->startComponent('mail::message'); ?>
# Reset Password
Reset or change your password.
<?php $__env->startComponent('mail::button', ['url' => env('FRONTPATH').'#/reset_password/'.$token]); ?>
Change Password
<?php echo $__env->renderComponent(); ?>
Thanks,<br>
<?php echo e(config('app.name')); ?>

<?php echo $__env->renderComponent(); ?>
<?php /**PATH /home/mbus0/mbus.if.ua/api-dev/resources/views/Email/resetPassword.blade.php ENDPATH**/ ?>