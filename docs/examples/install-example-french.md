# Installation d'un YesWiki qui intègre HumHub - exemple pour Hameaux Légers

> Cette doc a été faite en prestation par Adrien pour l’association Hameaux-Légers. Les adresses utilisées sont ainsi spécifiques à leur serveur et il y a une partie de la configuration système qui repose sur l’interface de gestion système Plesk.

# Organisation globale

* depuis Plesk, dans website on voit le domaine principal [hameaux-legers.org](http://hameaux-legers.org/) puis les deux sous-domaines [communaute.hameaux-legers.org](http://communaute.hameaux-legers.org/) et [mooc.hameaux-legers.org](http://mooc.hameaux-legers.org/)
* on accède à chacune des bases de données en cliquant sur le bouton Databases, et on peut s'y connecter avec PhpMyAdmin avec le bouton du même nom
* pour se connecter en ssh, le même utilisateur peut accéder au domaine et aux sous-domaines. Lancer dans un terminal : `ssh [web@hameaux-legers.org](mailto:web@hameaux-legers.org)` et taper le mot de passe
* au niveau de l'aborescence de fichier, on retrouve :
    * le site vitrine (domaine principale) dans `~/httpdocs/`
    * le site yeswiki (sous domaine mooc) dans `~/mooc.hameaux-legers.org/`
    * le site humhub (sous domaine communaute) dans `~/communaute.hameaux-legers.org/`

# Configuration du SSO

## Pour YesWiki

* aller à l'adresse [https://login.lescommuns.org/auth/admin](https://login.lescommuns.org/auth/admin) avec un navigateur
    * cliquer sur le menu Clients puis sur le bouton Create
    * mettre pour Client ID : [mooc.hameaux-legers.org](http://mooc.hameaux-legers.org/)
    * selectionner pour Client Protocol : openid-connect
    * mettre pour Root URL : [https://mooc.hameaux-legers.org](https://mooc.hameaux-legers.org) et valider
    * dans l'onglet Settings :
        * sélectionner pour Access Type : confidential et cliquer sur Save
    * cliquer sur l'onglet Credentials :
        * retenir comment revenir à cet onglet pour récupérer la clé
    * cliquer sur l'onglet Mappers :
        * cliquer sur le bouton Add Builtin
        * sélectionner les champs 'family name', 'email', 'given name', 'full name', 'username', et cliquer sur le bouton Add selected

## Pour HumHub

* aller à l'adresse [https://login.lescommuns.org/auth/admin](https://login.lescommuns.org/auth/admin) avec un navigateur
* cliquer sur le menu Clients puis sur le bouton Create
* mettre pour Client ID : [communaute.hameaux-legers.org](http://communaute.hameaux-legers.org/)
* selectionner pour Client Protocol : openid-connect
* mettre pour Root URL : [https://communaute.hameaux-legers.org](https://communaute.hameaux-legers.org) et valider
* dans l'onglet Settings :
    * sélectionner pour Access Type : confidential et cliquer sur Save
* cliquer sur l'onglet Credentials :
    * retenir comment revenir à cet onglet pour récupérer la clé
* cliquer sur l'onglet Mappers :
    * cliquer sur le bouton Add Builtin
    * sélectionner les champs 'family name', 'email', 'given name', 'username', et cliquer sur le bouton Add selected
    * cliquer sur le bouton Edit à la ligne 'username', remplacer prefered_username par id et clique sur le bouton Save

# Installation de YesWiki

* dans Plesk, cliquer sur le menu Databases puis sur le sous-domaine [mooc.hameaux-legers.org](http://mooc.hameaux-legers.org/)
    * ajouter une base de donnée et un utilisateur en cliquant sur Add a Dababase
        * mettre pour Database name : yeswiki
        * selectionner pour Related site : [mooc.hameaux-legers.org](http://mooc.hameaux-legers.org/)
        * mettre pour Database user name : yeswiki
        * cliquer sur Generate pour créer un password sécurisé
        * sélectionner pour Access control : Allow remote connections from any host
    * aller dans Websites & Domains, cliquer sur le bouton File Manager et supprimer le fichier index.html
* se connecter en ssh : `ssh [web@hameaux-legers.org](mailto:web@hameaux-legers.org)` (mettre le mot de passe)
* préparer une version de yeswiki avec l'ensemble des extensions installées et les personnalisations qui permettent que yeswiki intéragissent avec humhub. Les modules **lms**, **login-sso** doivent être installées. Le module LMS ne prend pas encore en compte l'intégration des commentaires humhub, ainsi il faut avoir une version personnalisée. De même, la subscrition à des évènements est une partie personnalisée (cf le répertoire custom).
* copier cette version de yeswiki dans le répertoire `~/mooc.hameaux-legers.org/` de l'utilisateur web `rsync -av --itemize-changes * [web@hameaux-legers.org](mailto:web@hameaux-legers.org):~/mooc.hameaux-legers.org/`
* lancer la page [https://mooc.hameaux-legers.org](https://mooc.hameaux-legers.org) dans un navigateur
    * renseigner les champs Nom de votre site et Description
    * mettre dans Machine MySQL : localhost
    * mettre dans Base de données MySQL : yeswiki
    * mettre dans Nom de l'utilisateur MySQL : yeswiki
    * mettre dans Mot de passe MySQL : le mot de passe rentré lors de la création de l'utilisateur (cf plus haut)
    * mettre dans Préfixe des tables : yeswiki_mooc__
    * mettre dans Mot de passe et Confirmation du mot de passe en dessous de Administrateur : un mot de passe sécurisé
    * mettre dans Adresse e-mail : [support@hameaux-legers.org](mailto:support@hameaux-legers.org) et valider
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

* Accéder à la base de donner en cliquant sur Databases, [mooc.hameaux-legers.org](http://mooc.hameaux-legers.org/) puis sur le bouton phpMyAdmin
    * définir les administrateurs en allant dans la table yeswiki_mooc__triples, et en modifiant la ligne d'id 1 à la colonne value : laisser WikiAdmin mais rajouter les NomWiki des utilisateurs qu'on veut passer administrateur séparer par des espaces ou des retours à la ligne.
    * ajouter un formulaire à yeswiki pour les profils des utilisateurs et avec les champs minimum bf_titre, bf_nom, bf_prenom et bf_email. Puis modifier directement dans la base de données à la tabl…