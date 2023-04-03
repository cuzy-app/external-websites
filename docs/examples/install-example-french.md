> Cette doc a été faite pour l’association Hameaux Légers. Les adresses utilisées sont ainsi spécifiques à leur serveur et il y a une partie de la configuration système qui repose sur l’interface de gestion système Plesk.

# Organisation globale

* depuis Plesk, dans website on voit le domaine principal `hameaux-legers.org` puis les deux sous-domaines communaute.hameaux-legers.org et mooc.hameaux-legers.org
* on accède à chacune des bases de données en cliquant sur le bouton Databases, et on peut s'y connecter avec PhpMyAdmin avec le bouton du même nom
* pour se connecter en ssh, le même utilisateur peut accéder au domaine et aux sous-domaines. Lancer dans un terminal : `ssh web@hameaux-legers.org` et taper le mot de passe
* au niveau de l'aborescence de fichier, on retrouve :
    * le site vitrine (domaine principale) dans `~/httpdocs/`
    * le site yeswiki (sous domaine mooc) dans `~/mooc.hameaux-legers.org/`
    * le site humhub (sous domaine communaute) dans `~/communaute.hameaux-legers.org/`

# Configuration du SSO

## Pour YesWiki

* aller à l'adresse https://login.lescommuns.org/auth/admin avec un navigateur
    * cliquer sur le menu Clients puis sur le bouton Create
    * mettre pour Client ID : mooc.hameaux-legers.org
    * selectionner pour Client Protocol : openid-connect
    * mettre pour Root URL : https://mooc.hameaux-legers.org et valider
    * dans l'onglet Settings :
        * sélectionner pour Access Type : confidential et cliquer sur Save
    * cliquer sur l'onglet Credentials :
        * retenir comment revenir à cet onglet pour récupérer la clé
    * cliquer sur l'onglet Mappers :
        * cliquer sur le bouton Add Builtin
        * sélectionner les champs 'family name', 'email', 'given name', 'full name', 'username', et cliquer sur le bouton Add selected

## Pour HumHub

* aller à l'adresse https://login.lescommuns.org/auth/admin avec un navigateur
* cliquer sur le menu Clients puis sur le bouton Create
* mettre pour Client ID : communaute.hameaux-legers.org
* selectionner pour Client Protocol : openid-connect
* mettre pour Root URL : https://communaute.hameaux-legers.org et valider
* dans l'onglet Settings :
    * sélectionner pour Access Type : confidential et cliquer sur Save
* cliquer sur l'onglet Credentials :
    * retenir comment revenir à cet onglet pour récupérer la clé
* cliquer sur l'onglet Mappers :
    * cliquer sur le bouton Add Builtin
    * sélectionner les champs 'family name', 'email', 'given name', 'username', et cliquer sur le bouton Add selected
    * cliquer sur le bouton Edit à la ligne 'username', remplacer prefered_username par id et clique sur le bouton Save

# Installation de YesWiki

* dans Plesk, cliquer sur le menu Databases puis sur le sous-domaine `mooc.hameaux-legers.org`
    * ajouter une base de donnée et un utilisateur en cliquant sur Add a Dababase
        * mettre pour Database name : yeswiki
        * selectionner pour Related site : mooc.hameaux-legers.org
        * mettre pour Database user name : yeswiki
        * cliquer sur Generate pour créer un password sécurisé
        * sélectionner pour Access control : Allow remote connections from any host
    * aller dans Websites & Domains, cliquer sur le bouton File Manager et supprimer le fichier index.html
* se connecter en ssh : `ssh web@hameaux-legers.org` (mettre le mot de passe)
* préparer une version de yeswiki avec l'ensemble des extensions installées et les personnalisations qui permettent que yeswiki intéragissent avec humhub. Les modules **lms**, **login-sso** doivent être installées. Le module LMS ne prend pas encore en compte l'intégration des commentaires humhub, ainsi il faut avoir une version personnalisée. De même, la subscrition à des évènements est une partie personnalisée (cf le répertoire custom).
* copier cette version de yeswiki dans le répertoire `~/mooc.hameaux-legers.org/` de l'utilisateur web `rsync -av --itemize-changes * web@hameaux-legers.org:~/mooc.hameaux-legers.org/`
* lancer la page https://mooc.hameaux-legers.org dans un navigateur
    * renseigner les champs Nom de votre site et Description
    * mettre dans Machine MySQL : localhost
    * mettre dans Base de données MySQL : yeswiki
    * mettre dans Nom de l'utilisateur MySQL : yeswiki
    * mettre dans Mot de passe MySQL : le mot de passe rentré lors de la création de l'utilisateur (cf plus haut)
    * mettre dans Préfixe des tables : yeswiki_mooc__
    * mettre dans Mot de passe et Confirmation du mot de passe en dessous de Administrateur : un mot de passe sécurisé
    * mettre dans Adresse e-mail : support@hameaux-legers.org et valider
* modifier le fichier de configuration
    * remplacer : `'default_write_acl' => '*',* 'default_read_acl' => '',` par : `'default_write_acl' => '%', 'default_read_acl' => '+',`
    * puis rajouter à la fin du tableau :

```
      'contact_mail_func' => 'smtp',
      'contact_mail_func' => 'smtp',
      'contact_smtp_host' => 'ssl0.ovh.net',
      'contact_smtp_port' => '465',
      'contact_smtp_user' => 'support@hameaux-legers.org',
      'contact_smtp_pass' => 'SMTP_PASSWORD',
      'contact_from' => 'support@hameaux-legers.org',
      'BAZ_ADRESSE_MAIL_ADMIN' => 'support@hameaux-legers.org',
      'disable_wiki_links' => true,
      'sso_config' => [
          // the form id for the bazar entry corresponding to the connected user
          // if defined, link will propose to show him his user information (profile)
          // don't declare it, if you don't need to have bazar entries related to users
          'bazar_user_entry_id' => '1000',
          'providers' => [
              // each entry here is an array corresponding to a SSO provider
              [
                  // the authentification auth type, two protocols are supported : 'oauth2' and 'cas'
                  'auth_type' => 'oauth2',
                  'auth_options' => [
                      'clientId' => 'mooc.hameaux-legers.org',
                      // The client ID assigned to you by the provider
                      'clientSecret' => 'SSO_SECRET',
                      // The client secret assigned to you by the provider
                      'urlAuthorize' => 'https://login.hameaux-legers.org/auth/realms/master/protocol/openid-connect/auth',
                      'urlAccessToken' => 'https://login.hameaux-legers.org/auth/realms/master/protocol/openid-connect/token',
                      'urlResourceOwnerDetails' => 'https://login.hameaux-legers.org/auth/realms/master/protocol/openid-connect/userinfo',
                  ],
                  // sso server fieldname used for the user email, this email links an SSO user to a yeswiki user
                  'email_sso_field' => 'email',
                  'create_user_from' => '#[given_name] #[family_name]',
                  // if create_user_from is defined, an yeswiki user with a name and an email is created.
                  // the username is an unique word (ID) generated from the format create_user_form by specifying #[field_name] to referring to a sso field
                  // if not defined, the authentification module accepts only sso users which have an yeswiki user corresponding to this email
                  'button_style' => [
                      // name used for the login button
                      'button_label' => 'Les communs',
                      // class of this button
                      'button_class' => 'btn btn-default btn-lescommuns',
                      // icon used for this button (class of the <i>)
                      'button_icon' => 'glyphicon glyphicon-log-in'
                      // you can also write a wiki page named 'ConnectionDetails' to inform the user before the buttons are displayed
                  ],
                  'bazar_mapping' => [
                      'fields' => [
                          'bf_nom' => 'family_name',
                          'bf_prenom' => 'given_name',
                          'bf_mail' => 'email'
                      ],
                      'read_access_entry' => '+',
                      'write_access_entry'=> '%',
                      'entry_creation_information' => "<p>C'est votre première connexion avec ce compte. Une fiche avec vos informations personnelles va être créée dans le but de faciliter la mise en lien entre les utilisateurs. Les données suivantes - Prénom, Nom, E-mail - vont êtres récupérées directement depuis le serveur d'authentification et pourront être modifiées ou supprimées plus tard à votre convenance dans 'Mes fiches'.</p>",
                  ]
              ]
          ]
      ],
```

Attention à bien remplacer SMTP_PASSWORD et SSO_SECRET avec les bonnes valeurs. Vous pouvez également adapter 'entry_creation_information' qui est le message qui sera affichée à la première connexion d'un utilisateur lorsque son compte est créé.

* Accéder à la base de donner en cliquant sur Databases, `mooc.hameaux-legers.org` puis sur le bouton phpMyAdmin
    * définir les administrateurs en allant dans la table yeswiki_mooc__triples, et en modifiant la ligne d'id 1 à la colonne value : laisser WikiAdmin mais rajouter les NomWiki des utilisateurs qu'on veut passer administrateur séparer par des espaces ou des retours à la ligne.
    * ajouter un formulaire à yeswiki pour les profils des utilisateurs et avec les champs minimum bf_titre, bf_nom, bf_prenom et bf_email. Puis modifier directement dans la base de données à la table yeswiki_mooc__nature la valeur bn_id_nature de ce formulaire pour qu'elle soit à 1000.
* lancer https://mooc.hameaux-legers.org/?PagePrincipale/update dans un navigateur pour rajouter les pages et formulaires utiles au LMS

# Installation de HumHub

## Installation basique

* dans Plesk, cliquer sur le menu Databases puis sur le sous-domaine communaute.hameaux-legers.org
    * ajouter une base de donnée et un utilisateur en cliquant sur Add a Dababase
        * mettre pour Database name : humhub
        * sélectionner pour Related site : communaute.hameaux-legers.org
        * mettre pour Database user name : humhub
        * cliquer sur Generate pour créer un mot de passe sécurisé
        * sélectionner pour Access control : Allow remote connections from any host
* dans Plesk, aller dans Websites & Domains / PHP Settings et changer :
    * max_execution_time à 120
    * post_max_size à 16M
    * upload_max_filesize à 16M
* se connecter en ssh : `ssh web@hameaux-legers.org` (mettre le mot de passe) et exécuter les lignes suivantes :
    * `rm ~/communaute.hameaux-legers.org/index.html`
    * `cd /tmp`
    * `wget https://www.humhub.com/download/package/humhub-1.8.2.tar.gz` (vous pouvez installer une version plus récente en regardant la dernière version stable sur [https://www.humhub.com/en/download](https://www.humhub.com/en/download))
    * `tar xvzf humhub-1.8.2.tar.gz`
    * `cd humhub-1.8.2/`
    * `mv * ~/communaute.hameaux-legers.org/`
    * `mv .htaccess.dist ~/communaute.hameaux-legers.org/`
    * `rm ../humhub-1.8.2* -rf`
* dans Plesk :
    * aller dans Websites & Domains et cliquer sur le bouton Scheduled Tasks (panneau de droite) :
        * cliquer sur Add Task
        * sélectionner pour Webspace : hameaux-legers.org
        * mettre pour Command : `/opt/plesk/php/7.4/bin/php /var/www/vhosts/hameaux-legers.org/communaute.hameaux-legers.org/protected/yii queue/run >/dev/null 2>&1`
        * sélectionner Cron style pour Run puis mettre : \* \* \* \* \*
        * mettre pour Description : pour les traitements longs d'Humhub (script lancé toutes les minutes)
        * sélectionner pour Notify : Do not notify et Valider
    * puis créer une 2ème Task avec les mêmes champs excepté :
        * mettre pour Command : `/opt/plesk/php/7.4/bin/php /var/www/vhosts/hameaux-legers.org/communaute.hameaux-legers.org/protected/yii cron/run >/dev/null 2>&1`
    * aller sur le site https://communaute.hameaux-legers.org/ pour terminer l'install
        * cliquer sur Suivant
        * voir que les prérequis systèmes sont OK et cliquer sur Suivant
        * pour la configuration de la base de données :
            * mettre pour Nom d'hôte : localhost
            * mettre pour Nom d'utilisateur : humhub
            * mettre pour Mot de passe : le mot de passe rentré lors de la création de l'utilisateur (cf plus haut)
            * mettre pour Nom de la base de données : humhub et cliquer sur Suivant
        * mettre pour Nom de votre réseau : Hameaux Légers et cliquer sur Suivant
        * sélectionner pour Je veux utiliser Humhub pour : Ma communauté
        * pour les paramètres de sécurité :
            * cocher : Les utilisateurs externes peuvent s'inscrire
            * décocher : Les nouveaux utilisateurs inscrits doivent être préalablement activés par un administrateur
            * décocher : Autoriser l'accès aux contenus publics pour les utilisateurs non inscrits
            * décocher : Les membres inscrits peuvent inviter des personnes par e-mail
            * décocher : Autoriser le système d'amitié entre les utilisateurs
        * pour les Modules recommandés, cocher tous les modules
        * pour la configuration du compte administrateur :
            * mettre pour identifiant : admin
            * mettre pour E-mail : [support@hameaux-legers.org](mailto:support@hameaux-legers.org)
            * mettre pour Nouveau mot de passe et Confirmer le nouveau mot de passe : définir un mot de passe au hazard (l'utilisateur sera ensuite supprimé)
            * mettre pour Prénom : admin
            * mettre pour Nom : admin et cliquer sur Créer un compte administrateur
        * pour Exemples de contenu, décocher Installer des exemples de contenu
        * vous pouvez ensuite vous logger pour tester le compte admin temporaire

## Configurer les adresses courtes

Rend les urls plus « user friendly », et nécessaire pour les modules ci-dessous

* aller dans Plesk :
    * cliquer sur Websites & Domains et cliquer sur l'onglet Hosting & DNS du site http://communaute.hameaux-legers.org/
    * cliquer sur Apache & nginx Settings
    * décocher Restrict the ability to follow symbolic links
    * se connecter en ssh : `ssh web@hameaux-legers.org` (mettre le mot de passe)
    * modifier le fichier de conf de humhub en tapant `vi ~/communaute.hameaux-legers.org/protected/config/common.php` et modifier le tableau pour retourner :

      ```
      return [
          'components' => [
              'urlManager' => [
                  'showScriptName' => false,
                  'enablePrettyUrl' => true,
              ],
          ]
      ];
      ```
* activer les redirections apache en tapant : `mv ~/communaute.hameaux-legers.org/.htaccess.dist ~/communaute.hameaux-legers.org/.htaccess`
* naviguer sur humhub et vérifier que vous avez bien l'url de type https://communaute.hameaux-legers.org/s/espace-de-bienvenue/ quand vous allez sur l'espace de bienvenue

## Derniers paramétrages post-install

* activer le mode « production » :
    * se connecter en ssh : `ssh web@hameaux-legers.org` (mettre le mot de passe)
    * éditer le fichier index.php via la commande `vi ~/communaute.hameaux-legers.org/index.php`
    * commenter ces deux définition de variables YII_DEBUG et YII_ENV de la manière suivante :

      ```
      // comment out the following two lines when deployed to production
      //defined('YII_DEBUG') or define('YII_DEBUG', true);
      //defined('YII_ENV') or define('YII_ENV', 'dev');
      ```
* configurer l'envoi de mail en allant à image de profil en haut à droite / Administration / Paramètre, onglet Avancé puis sous-onglet E-mail :
* mettre pour Adresse expéditeur des e-mails : support@hameaux-legers.org
* mettre pour Nom de l'expéditeur des e-mails : Hameaux Légers
* sélectionner pour Type de transport des e-mails : SMTP
* mettre pour Hostname : ssl0.ovh.net
* mettre pour Identifiant : support@hameaux-legers.org
* mettre pour Mot de passe : le mot de passe de la messagerie de support@hameaux-legers.org
* mettre pour Port : 465
* mettre pour Chiffrement : SSL
* suite à cette configuration, un mail de test est envoyé à l'administrateur qui a fait le paramétrage

## Install du module auth-keycloak

permet de se connecter au serveur SSO des Communs

* se connecter en ssh : `ssh web@hameaux-legers.org` (mettre le mot de passe)
    * installer le module en tapant en ligne de commande :

```
cd ~/communaute.hameaux-legers.org/protected/modules
git clone https://github.com/cuzy-app/humhub-modules-auth-keycloak.git auth-keycloak
cd auth-keycloak
/opt/plesk/php/7.4/bin/php /usr/lib/plesk-9.0/composer.phar install
```

* modifier le fichier common.php en tapant `vi ~/communaute.hameaux-legers.org/protected/config/common.php` et modifier le tableau pour retourner :

```
return [
  'components' => [
        'urlManager' => [
            'showScriptName' => false,
            'enablePrettyUrl' => true,
        ],
    'authClientCollection' => [ 
      'clients' => [ 
        'Keycloak' => [ 
          'class' => 'humhub\modules\authKeycloak\authclient\Keycloak', 
          'authUrl' => 'https://login.hameaux-legers.org/auth/realms/master/protocol/openid-connect/auth', 
          'tokenUrl' => 'https://login.hameaux-legers.org/auth/realms/master/protocol/openid-connect/token', 
          'apiBaseUrl' => 'https://login.hameaux-legers.org/auth/realms/master/protocol/openid-connect',
          'clientId' => 'communaute.hameaux-legers.org',
          // Client secret is in the "Credentials" tab (if in the settings "Access Type" is set to "confidential")
          'clientSecret' => 'SSO_SECRET',
          // String attribute to match user tables with email or id
          'idAttribute' => 'id',
          // Keycloak mapper for username: 'preferred_username', 'sub' (to use Keycloak ID) or other custom Token Claim Name
          'usernameMapper' => 'sub',
          // Title of the button (if autoLogin is disabled)
          'title' => 'Connexion via les Communs',
          // Automatic login
          'autoLogin' => true,
          // Hide username field in registration form
          'hideRegistrationUsernameField' => true,
        ],
      ],
    ],
  ],
];
```

Penser à modifier SSO_SECRET avec les bonnes valeurs.

* retourner sur Humhub avec le compte admin temporaire puis cliquer sur l'image de profil en haut à droite / Administration / Modules
* cliquer sur le lien Activer au niveau du module Keycload Sign-In

*Tips* : en cas de mauvaise manip, désactiver le module avec la commande `/opt/plesk/php/7.4/bin/php /var/www/vhosts/hameaux-legers.org/communaute.hameaux-legers.org/protected/yii module/disable auth-keycloak` (remplacer auth-keycload par le module voulu)

## Install du module external-websites

permet d'intégrer les commmentaires humhub dans yeswiki et inversement (possibilité aussi d'intégrer yeswiki dans humhub)

* se connecter en ssh : `ssh web@hameaux-legers.org` (mettre le mot de passe)
    * installer le module en tapant en ligne de commande :

  ```
  cd ~/communaute.hameaux-legers.org/protected/modules
  git clone https://github.com/cuzy-app/humhub-modules-external-websites.git external-websites
  ```
    * pour pouvoir inclure les commentaires humhub dans yeswiki, éditer le ficher web.php avec la commande : `vi ~/communaute.hameaux-legers.org/protected/config/web.php` et remplacer le tableau vide par :

  ```
  return [
    'web' => [
      'security' =>  [
        "headers" => [
          "Content-Security-Policy" => "default-src *; connect-src  *; font-src 'self'; frame-src https://* http://* *; img-src https://* http://* * data:; object-src 'self'; script-src 'self' https://* http://* * 'unsafe-inline' 'report-sample'; style-src * https://* http://* * 'unsafe-inline'; frame-ancestors 'self' https://mooc.hameaux-legers.org;"
        ]
          ],
      ],
  ];
  ```
* dans HumHub
    * aller dans image de profil en haut à droite / Administration / Modules et activer "External Websites"
    * puis dans image de profil en haut à droite / Administration / Espaces et cliquer sur le bouton Ajouter un espace :
        * mettre pour Nom : "S'installer en habitat réversible"
        * mettre pour Description : "Espace d'échange pour les participants au MOOC « S'installer en habitat réversible »"
        * cliquer sur Paramètres d'accès avancées et sélectionner :
            * Public (utilisateurs enregistrés uniquement)
            * Sur invitation uniquement
        * sur la page suivante, cliquer sur Activer pour le module External Websites
        * aller dans roue crantée / Réglages puis l'onglet avancée, mettre pour Url : mooc-habitat-reversible et cliquer sur Enregistrer
    * dans l'espace du MOOC, cliquer sur roue crantée / Sécurité puis l'onglet Permissions. Au niveau de la ligne Inviter des utilisateurs, sélectionner Interdire
    * aller dans roue crantée / Gérer les sites web externes et les paramètres et cliquer sur Ajouter un site web
        * mettre dans Titre : MOOC
        * sélectionner pour Humhub est intégré : Oui
        * mettre dans URL de la première page du site Web : https://mooc.hameaux-legers.org
        * mettre dans Afficher dans le menu de l'espace : Oui
        * et cliquer sur Ajouter ce site web
* créer un groupe pour les utilisateurs du MOOC :
    * aller dans image de profil en haut à droite / Administration / Utilisateurs, onglet Groupes et cliquer sur Ajouter un groupe
        * mettre pour Nom : Participants MOOC
        * mettre pour Description : Groupe des participants au MOOC « S'installer en habitat réversible »
        * ajouter pour Default Space(s) : S'installer en habitat réversible
        * cliquer sur Enregistrer
* se connecter en ssh : `ssh web@hameaux-legers.org` (mettre le mot de passe)
    * `vi ~/communaute.hameaux-legers.org/protected/config/common.php`
    * intégrer les lignes suivantes en dernier élément du tableau principal (modifier API_SECRET_KEY) :

      ```
      'modules' => [
        'external-websites' => [
          'registerAssetsIfHumhubIsEmbedded' => true,
          'jwtKey' => 'API_SECRET_KEY',
        ],
      ],
      ```

      Au final, voici le tableau qu'on obtient (penser à modifier SSO_SECRET et API_SECRET_KEY avec les bonnes valeurs)

```
return [
  'components' => [
    'urlManager' => [
        'showScriptName' => false,
        'enablePrettyUrl' => true,
    ],
    'authClientCollection' => [ 
      'clients' => [ 
        'Keycloak' => [ 
          'class' => 'humhub\modules\authKeycloak\authclient\Keycloak', 
          'authUrl' => 'https://login.hameaux-legers.org/auth/realms/master/protocol/openid-connect/auth', 
          'tokenUrl' => 'https://login.hameaux-legers.org/auth/realms/master/protocol/openid-connect/token', 
          'apiBaseUrl' => 'https://login.hameaux-legers.org/auth/realms/master/protocol/openid-connect',
          'clientId' => 'communaute.hameaux-legers.org',
          // Client secret is in the "Credentials" tab (if in the settings "Access Type" is set to "confidential")
          'clientSecret' => 'SSO_SECRET',
          // String attribute to match user tables with email or id
          'idAttribute' => 'id',
          // Keycloak mapper for username: 'preferred_username', 'sub' (to use Keycloak ID) or other custom Token Claim Name
          'usernameMapper' => 'sub',
          // Title of the button (if autoLogin is disabled)
          'title' => 'Connexion via les Communs',
          // Automatic login
          'autoLogin' => true,
          // Hide username field in registration form
          'hideRegistrationUsernameField' => true,
        ],
      ],
    ],
  ],
  'modules' => [
    'registerAssetsIfHumhubIsEmbedded' => true,
      'external-websites' => [
          'jwtKey' => 'API_SECRET_KEY',
      ],
  ],
];
```

Pour info, voici le payload associé à cette jwtKey afin que qu'un nouvel utilisateur soit automatiquement ajouté aux groupes d'ID 1 et 2 (cf [doc de l'extension external-website](https://github.com/cuzy-app/humhub-modules-external-websites/tree/master/docs)) :

```
{
 "groupsId": [1, 2]
}
```

## Paramétrage du module « external websites » dans YesWiki

* rajouter la librairie contenante de iframe-resizer (si elle n'existe pas déjà) en exécutant :

```
cd ~/mooc.hameaux-legers.org/custom/javascripts/
wget https://github.com/cuzy-app/humhub-modules-external-websites/tree/master/resources/js/iframeResizer/iframeResizer.js
```

* modifier le fichier fiche-1201.tpl.html en exécutant `vi ~/mooc.hameaux-legers.org/tools/lms/templates/bazar/fiche-1201.tpl.html` :
    * modifier la valeur de la variable $humhubUrl (ligne 82) et mettre : 'https://communaute.hameaux-legers.org'
    * modifier la valeur de la variable $spaceUrl (ligne 84) et mettre : 'mooc-habitat-reversible' (l'id est normalement à 1, sinon sinon récupérer son Id dans le menu roue crantée / Gérer les sites web externes)
    * modifier la valeur de la variable $humhubWebsiteId (ligne 86) et mettre : 1
    * modifier la valeur de la variable $autoLogin (ligne 90) et mettre : 1
    * modifier la valeur de la variable $token (ligne 92) et mettre :
    * modifier la valeur de la variable $humhubUrl et mettre : le TOKEN calculé à partir du payload et de API_SECRET_KEY en suivant la procédure décrite dans la [doc de l'extension external-website](https://github.com/cuzy-app/humhub-modules-external-websites/tree/master/docs)

# Resssouces

* [installation classique de YesWiki](https://yeswiki.net/?DocumentationInstallation)
* [préparation du serveur pour HumHub](https://docs.humhub.org/docs/admin/server-setup/)
* [installation de HumHub](https://docs.humhub.org/docs/admin/installation/)
* [doc de l'extension auth-keycloak (HumHub)](https://github.com/cuzy-app/humhub-modules-auth-keycloak)
* [doc de l'extension external-website (HumHub)](https://github.com/cuzy-app/humhub-modules-external-websites/tree/master/docs)
