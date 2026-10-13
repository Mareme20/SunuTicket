# SunuTicket

SunuTicket est une billetterie en ligne pensée pour les événements au Sénégal. Elle permet à un organisateur de publier un événement, à un acheteur de réserver et payer en ligne, puis à l’organisateur de scanner les billets à l’entrée via son téléphone.

La plateforme est construite avec Laravel 11 et PHP 8.2+, sans dépendance JavaScript côté build : le site fonctionne dès que l’environnement PHP est prêt.

## Fonctionnalités

- Création et gestion d’événements par les organisateurs
- Paiements en ligne via PayDunya avec Wave / Orange Money
- Validation serveur du paiement avant l’émission des billets
- Génération de billets avec QR code et envoi par email
- Vérification de billets à l’entrée avec scanner mobile
- Filtrage des événements publics par catégorie, ville et date
- Gestion des commandes temporaires avec expiration automatique
- Support des événements gratuits et des catégories d’événements
- Tableau de bord administrateur et profil public d’organisateur
- Génération de PDF de billets et support de pièces jointes de l’affiche

## Prérequis

- PHP 8.2+
- Composer
- SQLite par défaut, ou MySQL/PostgreSQL si vous configurez la base manuellement
- Extension PHP nécessaire pour Laravel (voir la documentation officielle Laravel)

## Installation rapide

```bash
composer install
cp .env.example .env
php artisan key:generate

touch database/database.sqlite
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Puis ouvrez l’application sur : http://localhost:8000

### Données de démonstration

```bash
php artisan db:seed --class=DemoSeeder
```

Compte d’organisateur de test :
- téléphone : `77 111 22 33`
- mot de passe : `password`

## Comptes

- Administrateur : créé via la seed ou les variables `ADMIN_PHONE` / `ADMIN_PASSWORD` dans le fichier `.env`. Changez le mot de passe avant la mise en ligne.
- Organisateur : s’inscrit lui-même sur `/inscription` avec son numéro de téléphone.
- Acheteur : pas de compte utilisateur. Il fournit son nom, téléphone et email, puis paie.

## Scanner d’entrée

Le scanner de billets est une page réservée aux comptes organisateurs ou administrateurs connectés.

Vous y accédez depuis le tableau de bord organisateur, puis en cliquant sur le bouton "Scanner" d’un événement, ou directement via l’URL :

```text
/organisateur/evenements/{event}/scanner
```

Exemple :

```text
/organisateur/evenements/12/scanner
```

La page utilise la caméra du téléphone pour lire le QR code et envoie les vérifications vers :

```text
/organisateur/evenements/{event}/scanner
/organisateur/evenements/{event}/scanner/donnees
```

> Cette page n’est pas publique : elle n’apparaît pas pour un simple acheteur ou un visiteur non connecté.

## Configuration de l’environnement

Copiez le fichier d’exemple et adaptez les variables selon votre environnement :

```bash
cp .env.example .env
```

Les variables importantes sont :

- `APP_URL`
- `DB_CONNECTION` / `DB_*`
- `MAIL_MAILER`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`
- `COMMISSION_PERCENT`
- `GATEWAY_PERCENT`
- `ORDER_HOLD_MINUTES`
- `PAYDUNYA_MODE`
- `PAYDUNYA_MASTER_KEY`
- `PAYDUNYA_PRIVATE_KEY`
- `PAYDUNYA_PUBLIC_KEY`
- `PAYDUNYA_TOKEN`
- `ADMIN_PHONE`, `ADMIN_PASSWORD`

## Configuration PayDunya

1. Créez votre application sur PayDunya et récupérez les 4 clés : master, private, public et token.
2. Renseignez-les dans le fichier `.env` sous les variables `PAYDUNYA_*`.
3. `PAYDUNYA_MODE=test` active le sandbox ; pour la production, remplacez par `live` avec vos clés réelles.
4. L’URL de notification IPN doit correspondre à : `https://votre-domaine/paydunya/ipn`.
5. Les billets ne sont émis qu’après une vérification côté serveur : le paiement doit être en statut `completed` et le montant doit correspondre exactement à la commande.

> En local, PayDunya ne peut pas appeler `localhost` directement. Vous pouvez utiliser ngrok pour tester les notifications IPN, mais la vérification côté retour de paiement est aussi mise en place pour sécuriser le flux.

## Emails et files d’attente

Par défaut, `MAIL_MAILER=log` : les emails sont écrits dans `storage/logs/laravel.log`.

Pour la production, configurez un SMTP et lancez une file de traitement durable :

```bash
php artisan queue:work --tries=3 --backoff=10
```

En local, `QUEUE_CONNECTION=sync` est suffisant pour les tests rapides.

## Calcul des frais

Pour un billet à 5 000 FCFA, avec un seul billet :

| Élément | Montant |
|---|---:|
| Acheteur paie (5 000 + 7 %) | 5 350 FCFA |
| PayDunya prélève 3 % sur 5 350 | 160 FCFA |
| Bénéfice administrateur (350 − 160) | 190 FCFA |
| Organisateur reçoit | 5 000 FCFA |

Les taux sont configurables dans `.env` via :
- `COMMISSION_PERCENT`
- `GATEWAY_PERCENT`

## Sécurité des billets

- Le QR contient un code aléatoire signé avec la clé applicative `APP_KEY`, ce qui empêche de falsifier un billet.
- À l’entrée, le statut du billet passe de valide à utilisé en une opération atomique.
- Une capture d’écran ou une photocopie est refusée : l’écran affiche alors un message de billet déjà utilisé.
- Le scanner dépend de l’accès à la caméra du téléphone. En production, le site doit être servi en HTTPS.
- Si l’écran du client est abîmé, l’organisateur peut saisir les 8 caractères affichés sous le QR pour valider manuellement le billet.

## Déploiement en production

1. Pointer le serveur web sur le dossier `public/`.
2. Définir :
   - `APP_ENV=production`
   - `APP_DEBUG=false`
   - `APP_URL=https://votre-domaine`
3. Activer `PAYDUNYA_MODE=live` avec les clés de production.
4. Configurer le SMTP et la queue durable.
5. Lancer le scheduler Laravel toutes les minutes pour expirer les commandes en attente et libérer les places :

```bash
* * * * * cd /chemin/vers/sunuticket && php artisan schedule:run >> /dev/null 2>&1
```

6. Compiler les caches :

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

7. Vérifier que les dossiers `storage/` et `bootstrap/cache/` sont accessibles en écriture.
8. Surveiller `/up`, les logs Laravel, les erreurs PayDunya et les emails non envoyés.
9. Sauvegarder régulièrement la base de données et le dossier `storage/app/public` qui contient les affiches.

## Structure du projet

- `app/Services` : calcul des frais, intégration PayDunya, QR, PDF, traitement des billets
- `app/Http/Controllers` : public, checkout, billets, organisateur, admin
- `app/Models` : entités métier (`User`, `Event`, `Order`, `Ticket`...)
- `database/migrations` : schéma de base de données
- `database/seeders` : données de test et compte administrateur
- `resources/views` : vues Blade de l’interface
- `public/css` : styles de l’application
- `routes/web.php` : routes publiques et privées de l’application

## License

Ce projet est livré sans licence explicite dans le dépôt. Vérifiez les règles du projet avant une utilisation commerciale ou une mise en production.

## Contribution

Les contributions sont les bienvenues. Avant de proposer des changements, testez le flux principal : installation, paiement sandbox, génération d’email et validation du QR.
