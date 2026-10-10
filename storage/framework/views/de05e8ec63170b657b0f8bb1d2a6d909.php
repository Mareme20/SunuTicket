<?php $__env->startSection('title', 'Créer un compte organisateur'); ?>

<?php $__env->startSection('content'); ?>
<section class="panel narrow">
    <div class="panel-header">
        <div class="panel-logo">
            <img src="<?php echo e(asset('images/logo-sunuticket.png')); ?>" alt="Logo SunuTicket" style="width:100%;height:100%;object-fit:contain;">
        </div>
        <h1>Créer un compte organisateur</h1>
        <p>Commencez à vendre vos billets en ligne et encaissez via Wave & Orange Money.</p>
    </div>

    <form method="post" action="<?php echo e(route('register')); ?>">
        <?php echo csrf_field(); ?>

        <div class="form-group">
            <label for="name">Nom complet ou nom de l'organisation</label>
            <input id="name" class="form-control" name="name" value="<?php echo e(old('name')); ?>" placeholder="Ex : Dakar Music Prod" autocomplete="name" required autofocus>
            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="err"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="form-group">
            <label for="phone">Numéro de téléphone (Wave / OM)</label>
            <input id="phone" class="form-control" name="phone" type="tel" inputmode="tel" autocomplete="tel" value="<?php echo e(old('phone')); ?>" placeholder="Ex : 77 123 45 67" required>
            <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="err"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="form-group">
            <label for="email">Adresse email (facultatif)</label>
            <input id="email" class="form-control" name="email" type="email" autocomplete="email" value="<?php echo e(old('email')); ?>" placeholder="contact@organisation.sn">
            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="err"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="two-cols">
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input id="password" class="form-control" name="password" type="password" autocomplete="new-password" placeholder="8 car. minimum" required>
                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="err"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirmer</label>
                <input id="password_confirmation" class="form-control" name="password_confirmation" type="password" autocomplete="new-password" placeholder="Répéter mot de passe" required>
            </div>
        </div>

        <button class="btn btn-primary btn-block btn-lg" style="margin-top:14px;">
            <span>Créer mon compte organisateur</span>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                <line x1="5" y1="12" x2="19" y2="12"></line>
                <polyline points="12 5 19 12 12 19"></polyline>
            </svg>
        </button>
    </form>

    <p class="hint">
        Vous avez déjà un compte ? <a href="<?php echo e(route('login')); ?>" style="font-weight:700;color:var(--teal-600);">Se connecter</a>
    </p>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/user/Téléchargements/sunuticket/resources/views/auth/register.blade.php ENDPATH**/ ?>