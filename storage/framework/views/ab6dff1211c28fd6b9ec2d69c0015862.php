<?php $__env->startSection('title', 'Connexion Organisateur'); ?>

<?php $__env->startSection('content'); ?>
<section class="panel narrow">
    <div class="panel-header">
        <div class="panel-logo">
            <img src="<?php echo e(asset('images/logo-sunuticket.png')); ?>" alt="Logo SunuTicket" style="width:100%;height:100%;object-fit:contain;">
        </div>
        <h1>Connexion organisateur</h1>
        <p>Gérez vos événements, suivez vos recettes et validez les entrées.</p>
    </div>

    <form method="post" action="<?php echo e(route('login')); ?>">
        <?php echo csrf_field(); ?>

        <div class="form-group">
            <label for="phone">Numéro de téléphone</label>
            <input id="phone" class="form-control" name="phone" type="tel" inputmode="tel" autocomplete="tel" value="<?php echo e(old('phone')); ?>" placeholder="Ex : 77 123 45 67" required autofocus>
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
            <label for="password">Mot de passe</label>
            <input id="password" class="form-control" name="password" type="password" autocomplete="current-password" placeholder="••••••••" required>
            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="err"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <label class="checkbox-label">
            <input type="checkbox" name="remember" value="1">
            <span>Se souvenir de moi</span>
        </label>

        <button class="btn btn-primary btn-block btn-lg" style="margin-top:10px;">
            <span>Se connecter</span>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                <line x1="5" y1="12" x2="19" y2="12"></line>
                <polyline points="12 5 19 12 12 19"></polyline>
            </svg>
        </button>
    </form>

    <p class="hint">
        Pas encore inscrit ? <a href="<?php echo e(route('register')); ?>" style="font-weight:700;color:var(--teal-600);">Créer un compte en 1 minute</a>
    </p>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/user/Téléchargements/sunuticket/resources/views/auth/login.blade.php ENDPATH**/ ?>