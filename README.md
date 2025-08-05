<<<<<<<< Update Guide >>>>>>>>>>>

Immediate Older Version: 1.1.0
Current Version: 1.2.0

Feature Update:
1. Introduced a Trade feature.
2. Implemented My Chat for user-to-user communication.
3. Enhanced the Email Configuration System.
4. Improved Sudo Africa and Stripe Virtual Card systems.
5. Updated the User Side Navigation.
6. Added PIN Verification for all transactions.
7. Enabled Refunds from Merchant to User.

Please Use This Commands On Your Terminal To Update Full System
1. To Run project Please Run This Command On Your Terminal
    composer update && composer dumpautoload && php artisan migrate:fresh --seed && php artisan passport:install --force
