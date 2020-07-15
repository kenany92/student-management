# student-management
* Installer nodejs v12.18.1
* Installer composer version 1.10.8
* Installer symfony CLI version v4.16.5
### Exécuter les commandes suivantes dans le terminal à la base du projet : 
* npm install
* npm run build
* composer install
### Configuer les informations de la base de données en modifiant le fichier .env à la ligne 32 : 
DATABASE_URL=mysql://user_name:password@127.0.0.1:3306/db_gie
### Lancer le serveur de base de données (XAMP pour mon cas)
### Créer la base de données (Dans notre cas : DATABASE_URL=mysql://root:@127.0.0.1:3306/db_gie) en exécutant la commande :
* php bin/console doctrine:database:create
### Migrer les tables vers la bd :
* php bin/console doctrine:migrations:migrate
### Lancer le serveur :
* symfony serve
### Allez à l'adresse http://127.0.0.1:8000/register : 
enregistrer un nouvel utilisateur
### Connectez vous...
 
