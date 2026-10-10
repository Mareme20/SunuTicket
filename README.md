# SunuTicket

Billetterie en ligne pour les événements du Sénégal : l'organisateur crée son événement, les acheteurs paient avec **Wave / Orange Money via PayDunya**, reçoivent un **billet avec code QR** par email, et l'organisateur **scanne les billets à l'entrée** avec son téléphone.

Application Laravel 11 (PHP 8.2+). Aucun outil JavaScript à compiler : le site fonctionne dès l'installation PHP terminée.

## Installation

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite        # (SQLite par défaut ; pour MySQL, voir .env)
php artisan migrate --seed            # crée les tables + le compte administrateur
php artisan storage:link              # pour l'affichage des affiches
php artisan serve                     # http://localhost:8000
```

Données de démonstration (facultatif) : `php artisan db:seed --class=DemoSeeder`
(organisateur de test : `77 111 22 33` / mot de passe `password`).

## Comptes

- **Administrateur** : créé par `php artisan migrate --seed` à partir de `ADMIN_PHONE` et `ADMIN_PASSWORD` dans `.env`. **Changez le mot de passe par défaut avant la mise en ligne.** Connexion sur `/connexion`, puis page `/administration`.
- **Organisateur** : s'inscrit lui-même sur `/inscription` avec son numéro de téléphone.
- **Acheteur** : pas de compte. Nom, téléphone, email, puis paiement.

## Configurer PayDunya

1. Créez votre application sur PayDunya et copiez les 4 clés (master, private, public, token) dans `.env` (`PAYDUNYA_*`).
2. `PAYDUNYA_MODE=test` utilise le sandbox ; passez à `live` avec vos clés de production.
3. Dans le tableau de bord PayDunya, l'URL de notification (IPN) est envoyée automatiquement à chaque paiement : `https://votre-domaine/paydunya/ipn`. En local, PayDunya ne peut pas appeler `localhost` (utilisez ngrok si vous voulez tester l'IPN) ; ce n'est pas bloquant car la page de retour vérifie aussi le paiement auprès de PayDunya.
4. Les billets ne sont émis qu'après vérification serveur auprès de PayDunya (statut `completed` **et** montant identique à la commande).

## Emails

Par défaut `MAIL_MAILER=log` : les emails sont écrits dans `storage/logs/laravel.log`. En production, renseignez un serveur SMTP dans `.env` (`MAIL_MAILER=smtp`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`; avec le port 465 ajoutez `MAIL_SCHEME=smtps`).

## Calcul des frais (app/Services/Fees.php)

Billet à 5 000 FCFA, 1 billet :

| | |
|---|---|
| L'acheteur paie (5 000 + 7 %) | 5 350 FCFA |
| PayDunya prélève 3 % de 5 350 | 160 FCFA |
| Bénéfice administrateur (350 − 160) | 190 FCFA |
| L'organisateur reçoit | 5 000 FCFA |

Les taux se changent dans `.env` (`COMMISSION_PERCENT`, `GATEWAY_PERCENT`).

## Sécurité des billets

- Le QR contient un code aléatoire signé (HMAC avec `APP_KEY`) : impossible d'inventer un billet.
- À l'entrée, le billet passe de « valide » à « utilisé » en une opération atomique : une photocopie ou une capture d'écran est refusée (écran rouge « Déjà utilisé »).
- Le scanner utilise la caméra : le site doit être servi en **HTTPS** en production (la caméra est bloquée sur du HTTP simple, sauf `localhost`).
- Si l'écran du client est abîmé, l'organisateur peut saisir les 8 caractères affichés sous le QR.

## Mise en production

- Pointez le serveur web sur le dossier `public/`.
- `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://votre-domaine`.
- `php artisan config:cache && php artisan route:cache && php artisan view:cache`
- Dossiers `storage/` et `bootstrap/cache/` accessibles en écriture.

## Structure

- `app/Services` : frais, PayDunya, QR, PDF, émission des billets
- `app/Http/Controllers` : public, paiement, billets, organisateur (événements, scanner), admin
- `resources/views` : pages (Blade) · `public/css/app.css` : styles (mobile d'abord)
- `database/migrations` : users, events, orders, tickets
