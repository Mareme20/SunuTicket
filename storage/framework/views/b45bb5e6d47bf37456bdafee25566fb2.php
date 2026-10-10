<?php $__env->startSection('title', 'Scanner les billets — '.$event->title); ?>

<?php $__env->startSection('content'); ?>

<div class="dashboard-header" style="max-width:540px;margin:0 auto 20px;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--navy-950);margin:0;">Contrôle d'accès</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:2px 0 0;"><?php echo e($event->title); ?></p>
    </div>
    <a class="btn btn-ghost btn-sm" href="<?php echo e(route('organizer.events.show', $event)); ?>">← Terminer</a>
</div>

<section class="panel scanner-container">
    <!-- Viseur Caméra -->
    <div id="reader" class="scanner-viewport"></div>

    <div id="cam-error" class="flash bad" style="margin-top:12px;" hidden>
        <svg class="flash-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="12" y1="8" x2="12" y2="12"></line>
            <line x1="12" y1="16" x2="12.01" y2="16"></line>
        </svg>
        <span style="font-size:0.85rem;">Caméra inaccessible. Autorisez-la dans votre navigateur ou utilisez la saisie manuelle ci-dessous.</span>
    </div>

    <!-- Saisie manuelle de secours -->
    <div style="margin-top:20px;border-top:1px solid var(--border);padding-top:18px;">
        <form id="manual" class="manual">
            <div class="form-group" style="margin-bottom:12px;">
                <label for="manual-code" style="font-size:0.88rem;color:var(--text-muted);">
                    Saisie manuelle : Code à 8 caractères sous le QR
                </label>
                <div style="display:flex;gap:8px;">
                    <input id="manual-code" class="form-control" maxlength="8" autocomplete="off" autocapitalize="characters" placeholder="Ex : 4A8B2C9F" style="text-transform:uppercase;font-family:monospace;font-size:1.1rem;letter-spacing:0.1em;font-weight:700;">
                    <button class="btn btn-navy" type="submit">Valider</button>
                </div>
            </div>
        </form>
    </div>
</section>

<!-- Modal plein écran du résultat du scan -->
<div id="verdict" class="verdict" role="alert" hidden>
    <div>
        <div style="width:72px;height:72px;border-radius:50%;background:rgba(255,255,255,0.2);display:grid;place-items:center;margin:0 auto 18px;">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
        </div>
        <h2 id="verdict-title"></h2>
        <p id="verdict-detail"></p>
        <span class="verdict-tap">Touchez l’écran pour scanner le suivant</span>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
(function () {
    var url = <?php echo json_encode(route('organizer.scan.check', $event), 512) ?>;
    var cacheUrl = <?php echo json_encode(route('organizer.scan.data', $event), 512) ?>;
    var csrf = <?php echo json_encode(csrf_token(), 15, 512) ?>;
    var box = document.getElementById('verdict');
    var busy = false;
    var cacheKey = 'sunuticket.scan-cache.' + <?php echo json_encode($event->id, 15, 512) ?>;
    var usedKey = 'sunuticket.scan-used.' + <?php echo json_encode($event->id, 15, 512) ?>;

    function show(ok, title, detail) {
        box.className = 'verdict ' + (ok ? 'ok' : 'bad');
        box.hidden = false;
        document.getElementById('verdict-title').textContent = title;
        document.getElementById('verdict-detail').textContent = detail;
        if (navigator.vibrate) navigator.vibrate(ok ? 120 : [200, 100, 200]);
    }

    function readJson(key, fallback) {
        try {
            var raw = localStorage.getItem(key);
            return raw ? JSON.parse(raw) : fallback;
        } catch (e) {
            return fallback;
        }
    }

    function persistJson(key, value) {
        try {
            localStorage.setItem(key, JSON.stringify(value));
        } catch (e) {}
    }

    function normalizePayload(payload) {
        var value = String(payload || '').trim();
        if (!value) return null;

        if (/^[A-Za-z0-9]{8}$/.test(value)) {
            return value.toUpperCase();
        }

        var parts = value.split('.');
        if (parts.length === 3 && parts[0] === 'ST1' && parts[1]) {
            return parts[1].toUpperCase();
        }

        return null;
    }

    function lookupOfflineTicket(payload) {
        var candidate = normalizePayload(payload);
        if (!candidate) return null;

        var cache = readJson(cacheKey, {tickets: []});
        var used = readJson(usedKey, []);
        var found = (cache.tickets || []).find(function (ticket) {
            return ticket.code === candidate || ticket.short_code === candidate || ticket.code.toUpperCase().startsWith(candidate);
        });

        if (!found || used.indexOf(found.code) !== -1) {
            return null;
        }

        return found;
    }

    function rememberUsed(code) {
        var used = readJson(usedKey, []);
        if (used.indexOf(code) === -1) {
            used.push(code);
            persistJson(usedKey, used);
        }
    }

    function cacheOfflineData(data) {
        persistJson(cacheKey, data || {tickets: []});
    }

    function refreshOfflineCache() {
        if (!navigator.onLine) {
            var cached = readJson(cacheKey, {tickets: []});
            if (cached.tickets && cached.tickets.length) {
                return;
            }
            show(false, 'Hors ligne', 'La liste des billets n’a pas encore été chargée sur cet appareil.');
            return;
        }

        fetch(cacheUrl, {headers: {'Accept': 'application/json'}})
            .then(function (r) { return r.json(); })
            .then(function (data) { cacheOfflineData(data); })
            .catch(function () {});
    }

    function send(payload) {
        if (busy) return;
        busy = true;

        var offline = lookupOfflineTicket(payload);
        if (!navigator.onLine) {
            if (offline) {
                rememberUsed(offline.code);
                show(true, 'Billet valide', offline.holder_name + ' — billet ' + offline.short_code);
                return;
            }

            show(false, 'Billet indisponible', 'Ce billet n’est pas dans la liste locale chargée sur cet appareil.');
            return;
        }

        fetch(url, {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf},
            body: JSON.stringify({payload: payload})
        }).then(function (r) { return r.json(); })
          .then(function (d) { show(d.ok, d.title, d.detail); refreshOfflineCache(); })
          .catch(function () {
              if (offline) {
                  rememberUsed(offline.code);
                  show(true, 'Billet valide', offline.holder_name + ' — billet ' + offline.short_code);
                  return;
              }
              show(false, 'Erreur réseau', 'Vérifiez la connexion internet ou réessayez plus tard.');
          });
    }

    box.addEventListener('click', function () {
        box.hidden = true;
        busy = false;
    });

    document.getElementById('manual').addEventListener('submit', function (e) {
        e.preventDefault();
        var code = document.getElementById('manual-code').value.trim();
        if (code) send(code);
    });

    refreshOfflineCache();

    if (window.Html5Qrcode) {
        var qr = new Html5Qrcode('reader');
        qr.start({facingMode: 'environment'}, {fps: 10, qrbox: {width: 250, height: 250}}, function (text) {
            send(text);
        }).catch(function () {
            document.getElementById('cam-error').hidden = false;
        });
    }
})();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/user/Téléchargements/sunuticket/resources/views/organizer/scan.blade.php ENDPATH**/ ?>