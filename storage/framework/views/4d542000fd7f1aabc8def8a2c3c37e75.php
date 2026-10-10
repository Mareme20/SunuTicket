<?php $__env->startSection('title', $event->exists ? 'Modifier l’événement' : 'Nouvel événement'); ?>

<?php $__env->startSection('content'); ?>

<nav class="breadcrumbs" aria-label="Fil d'Ariane">
    <a href="<?php echo e(route('home')); ?>">Accueil</a>
    <span>/</span>
    <a href="<?php echo e(route('organizer.dashboard')); ?>">Tableau de bord</a>
    <span>/</span>
    <span style="color:var(--text-main);font-weight:600;"><?php echo e($event->exists ? 'Modifier' : 'Créer un événement'); ?></span>
</nav>

<section class="panel" style="max-width:820px;margin:0 auto 40px;">
    <div style="border-bottom:1px solid var(--border);padding-bottom:18px;margin-bottom:28px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
        <div>
            <h1 style="font-size:1.85rem;color:var(--navy-950);margin:0;">
                <?php echo e($event->exists ? 'Modifier l’événement' : 'Créer un nouvel événement'); ?>

            </h1>
            <p style="color:var(--text-muted);font-size:0.95rem;margin:4px 0 0;">
                Renseignez les détails de votre billetterie pour lancer les réservations en ligne.
            </p>
        </div>
        <a class="btn btn-ghost btn-sm" href="<?php echo e(route('organizer.dashboard')); ?>">← Retour</a>
    </div>

    <form method="post" enctype="multipart/form-data"
          action="<?php echo e($event->exists ? route('organizer.events.update', $event) : route('organizer.events.store')); ?>">
        <?php echo csrf_field(); ?>
        <?php if($event->exists): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

        <!-- Section 1 : Généralités -->
        <div style="margin-bottom:28px;">
            <h3 style="color:var(--navy-900);font-size:1.15rem;margin-bottom:16px;">1. Informations générales</h3>

            <div class="form-group">
                <label for="title">Titre de l’événement *</label>
                <input id="title" class="form-control" name="title" value="<?php echo e(old('title', $event->title)); ?>" placeholder="Ex : Grand Concert Afrobeat 2026" required>
                <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="err"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label for="description">Description détaillée de l'événement</label>
                <textarea id="description" class="form-control" name="description" rows="5" placeholder="Décrivez le programme, les artistes, les consignes d'accès..."><?php echo e(old('description', $event->description)); ?></textarea>
                <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="err"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        <!-- Section 2 : Date & Emplacement -->
        <div style="margin-bottom:28px;border-top:1px solid var(--border);padding-top:24px;">
            <h3 style="color:var(--navy-900);font-size:1.15rem;margin-bottom:16px;">2. Date et Lieu</h3>

            <div class="two-cols">
                <div class="form-group">
                    <label for="starts_at">Date et heure de début *</label>
                    <input id="starts_at" class="form-control" type="datetime-local" name="starts_at" value="<?php echo e(old('starts_at', $event->starts_at?->format('Y-m-d\TH:i'))); ?>" required>
                    <?php $__errorArgs = ['starts_at'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="err"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label for="city">Ville *</label>
                    <input id="city" class="form-control" name="city" list="cities" value="<?php echo e(old('city', $event->city)); ?>" placeholder="Ex : Dakar" required>
                    <datalist id="cities">
                        <?php $__currentLoopData = ['Dakar', 'Pikine', 'Guédiawaye', 'Rufisque', 'Thiès', 'Mbour', 'Saint-Louis', 'Kaolack', 'Ziguinchor', 'Touba']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($city); ?>">
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </datalist>
                    <?php $__errorArgs = ['city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="err"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="form-group">
                <label for="venue">Lieu précis (salle, stade, adresse) *</label>
                <input id="venue" class="form-control" name="venue" value="<?php echo e(old('venue', $event->venue)); ?>" placeholder="Ex : Théâtre National Daniel Sorano, Corniche Ouest" required>
                <?php $__errorArgs = ['venue'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="err"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        <!-- Section 3 : Tarifs & Places -->
        <div style="margin-bottom:28px;border-top:1px solid var(--border);padding-top:24px;">
            <h3 style="color:var(--navy-900);font-size:1.15rem;margin-bottom:16px;">3. Billetterie & Jauge</h3>

            <div class="two-cols">
                <div class="form-group">
                    <label for="price">Prix du billet par personne (FCFA) *</label>
                    <input id="price" class="form-control" type="number" name="price" min="0" step="50" inputmode="numeric" value="<?php echo e(old('price', $event->price)); ?>" placeholder="Ex : 5000" required>
                    <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="err"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label for="category">Catégorie</label>
                    <select id="category" class="form-control" name="category">
                        <?php $__currentLoopData = ['concert','festival','club','conference','sport','theatre','social']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($category); ?>" <?php if(old('category', $event->category ?? 'concert') === $category): echo 'selected'; endif; ?>><?php echo e(ucfirst($category)); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['category'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="err"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="form-group" style="margin-top:16px;">
                <label for="capacity">Capacité totale (nombre de billets) *</label>
                <input id="capacity" class="form-control" type="number" name="capacity" min="1" inputmode="numeric" value="<?php echo e(old('capacity', $event->capacity)); ?>" placeholder="Ex : 200" required>
                <?php $__errorArgs = ['capacity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="err"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group" style="margin-top:16px;">
                <label>Tarifs multiples (optionnel)</label>
                <div id="ticket-types-list" style="display:grid;gap:12px;margin-top:8px;"></div>
                <button type="button" id="add-ticket-type" class="btn btn-ghost btn-sm" style="margin-top:12px;">+ Ajouter un tarif</button>
                <input type="hidden" name="ticket_types" id="ticket_types_json" value='<?php echo e(old('ticket_types', json_encode($event->ticketTypes(), JSON_UNESCAPED_UNICODE))); ?>'>
                <small style="color:var(--text-muted);display:block;margin-top:8px;">Exemples : Standard 3 000 FCFA, VIP 10 000 FCFA, Étudiant 2 500 FCFA.</small>
                <?php $__errorArgs = ['ticket_types'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="err"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div style="background:var(--teal-50);border:1px solid var(--teal-100);border-radius:var(--radius-md);padding:14px 16px;color:var(--teal-800);font-size:0.88rem;display:flex;align-items:center;gap:12px;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="16" x2="12" y2="12"></line>
                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                </svg>
                <span>
                    <strong>Transparence totale :</strong> Vous recevez l’intégralité de votre prix de vente (<?php echo e($event->price ? fcfa($event->price) : 'prix fixé'); ?> par place vendue). Les <?php echo e(config('sunuticket.commission_percent')); ?>% de frais de plateforme sont ajoutés au panier de l’acheteur.
                </span>
            </div>
        </div>

        <!-- Section 4 : Affiche / Visuel -->
        <div style="margin-bottom:28px;border-top:1px solid var(--border);padding-top:24px;">
            <h3 style="color:var(--navy-900);font-size:1.15rem;margin-bottom:16px;">4. Affiche de l'événement</h3>

            <div class="form-group">
                <label for="poster">Image de l'affiche (JPG, PNG ou WebP, max 3 Mo)</label>
                <input id="poster" class="form-control" type="file" name="poster" accept="image/jpeg,image/png,image/webp">
                <?php $__errorArgs = ['poster'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="err"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <?php if($event->posterUrl()): ?>
                <div style="margin-top:12px;display:flex;align-items:center;gap:14px;">
                    <img src="<?php echo e($event->posterUrl()); ?>" alt="Affiche actuelle" style="width:120px;height:75px;object-fit:cover;border-radius:var(--radius-sm);border:1px solid var(--border);">
                    <span style="font-size:0.85rem;color:var(--text-muted);">Affiche actuelle sauvegardée</span>
                </div>
            <?php endif; ?>
        </div>

        <!-- Publication -->
        <div style="margin-bottom:28px;border-top:1px solid var(--border);padding-top:24px;">
            <input type="hidden" name="is_published" value="0">
            <label class="checkbox-label" style="font-weight:600;font-size:1rem;">
                <input type="checkbox" name="is_published" value="1" <?php if(old('is_published', $event->is_published)): echo 'checked'; endif; ?>>
                <span>Publier immédiatement l’événement (visible sur le site et ouvert à la vente)</span>
            </label>
        </div>

        <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
            <button class="btn btn-primary btn-lg">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                    <polyline points="17 21 17 13 7 13 7 21"></polyline>
                    <polyline points="7 3 7 8 15 8"></polyline>
                </svg>
                <span><?php echo e($event->exists ? 'Enregistrer les modifications' : 'Créer l’événement'); ?></span>
            </button>

            <a class="btn btn-ghost" href="<?php echo e(route('organizer.dashboard')); ?>">Annuler</a>
        </div>
    </form>
</section>

<script>
(function () {
    var list = document.getElementById('ticket-types-list');
    var hidden = document.getElementById('ticket_types_json');
    var addButton = document.getElementById('add-ticket-type');
    var basePriceInput = document.getElementById('price');

    function parseStoredValue() {
        var raw = <?php echo json_encode(old('ticket_types', $event->ticketTypes()), 512) ?>;

        if (typeof raw === 'string') {
            try {
                raw = JSON.parse(raw);
            } catch (e) {
                raw = [];
            }
        }

        if (!Array.isArray(raw) || !raw.length) {
            var defaultPrice = Number(basePriceInput.value || 0);
            return [{ name: 'Standard', price: defaultPrice > 0 ? defaultPrice : 3000 }];
        }

        return raw.map(function (item) {
            return {
                name: String(item.name || 'Standard'),
                price: Number(item.price || 0)
            };
        });
    }

    var types = parseStoredValue();

    function syncHidden() {
        var cleaned = types
            .map(function (item) {
                var name = String(item.name || '').trim();
                var price = Number(item.price || 0);
                if (!name || price <= 0) {
                    return null;
                }
                return { name: name, price: price };
            })
            .filter(Boolean);

        hidden.value = JSON.stringify(cleaned.length ? cleaned : []);
    }

    function renderRows() {
        list.innerHTML = '';

        types.forEach(function (item, index) {
            var row = document.createElement('div');
            row.style.display = 'grid';
            row.style.gridTemplateColumns = 'minmax(180px, 1.3fr) minmax(120px, 0.7fr) auto';
            row.style.gap = '10px';
            row.style.alignItems = 'center';
            row.style.padding = '10px';
            row.style.border = '1px solid var(--border)';
            row.style.borderRadius = 'var(--radius-md)';
            row.style.background = 'var(--bg-surface)';

            var nameInput = document.createElement('input');
            nameInput.type = 'text';
            nameInput.value = item.name || '';
            nameInput.placeholder = 'Nom du tarif';
            nameInput.className = 'form-control';
            nameInput.addEventListener('input', function () {
                types[index].name = this.value;
                syncHidden();
            });

            var priceInput = document.createElement('input');
            priceInput.type = 'number';
            priceInput.min = '0';
            priceInput.step = '50';
            priceInput.value = item.price || 0;
            priceInput.placeholder = '3000';
            priceInput.className = 'form-control';
            priceInput.addEventListener('input', function () {
                types[index].price = Number(this.value || 0);
                syncHidden();
            });

            var removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.className = 'btn btn-ghost btn-sm';
            removeButton.textContent = 'Supprimer';
            removeButton.addEventListener('click', function () {
                if (types.length > 1) {
                    types.splice(index, 1);
                    renderRows();
                }
            });

            row.appendChild(nameInput);
            row.appendChild(priceInput);
            row.appendChild(removeButton);
            list.appendChild(row);
        });

        syncHidden();
    }

    addButton.addEventListener('click', function () {
        var defaultPrice = Number(basePriceInput.value || 0);
        types.push({ name: '', price: defaultPrice > 0 ? defaultPrice : 3000 });
        renderRows();
    });

    basePriceInput.addEventListener('input', function () {
        if (!types.length) return;
        var first = types[0];
        if (!first.name || first.name.toLowerCase() === 'standard') {
            first.price = Number(this.value || 0) || 3000;
            renderRows();
        }
    });

    renderRows();
})();
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/user/Téléchargements/sunuticket/resources/views/organizer/events/form.blade.php ENDPATH**/ ?>