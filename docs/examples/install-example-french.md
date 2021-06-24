# Organisation globale

* depuis Plesk, dans website on voit le domaine principal [hameaux-legers.org](http://hameaux-legers.org/) puis les deux sous-domaines [communaute.hameaux-legers.org](http://communaute.hameaux-legers.org/) et [mooc.hameaux-legers.org](http://mooc.hameaux-legers.org/)
* on accède à chacune des bases de données en cliquant sur le bouton Databases, et on peut s&#039;y connecter avec PhpMyAdmin avec le bouton du même nom
* pour se connecter en ssh, le même utilisateur peut accéder au domaine et aux sous-domaines. Lancer dans un terminal : `ssh [web@hameaux-legers.org](mailto:web@hameaux-legers.org)` et taper le mot de passe
* au niveau de l&#039;aborescence de fichier, on retrouve :
    * le site vitrine (domaine principale) dans `~/httpdocs/`
    * le site yeswiki (sous domaine mooc) dans `~/mooc.hameaux-legers.org/`
    * le site humhub (sous domaine communaute) dans `~/communaute.hameaux-legers.org/`

# Configuration du SSO

## Pour YesWiki

* aller à l&#039;adresse [https://login.lescommuns.org/auth/admin](https://login.lescommuns.org/auth/admin) avec un navigateur
    * cliquer sur le menu Clients puis sur le bouton Create
    * mettre pour Client ID : [mooc.hameaux-legers.org](http://mooc.hameaux-legers.org/)
    * selectionner pour Client Protocol : openid-connect
    * mettre pour Root URL : [https://mooc.hameaux-legers.org](https://mooc.hameaux-legers.org) et valider
    * dans l&#039;onglet Settings :
        * sélectionner pour Access Type : confidential et cliquer sur Save
    * cliquer sur l&#039;onglet Credentials :
        * retenir comment revenir à cet onglet pour récupérer la clé
    * cliquer sur l&#039;onglet Mappers :
        * cliquer sur le bouton Add Builtin
        * sélectionner les champs &#039;family name&#039;, &#039;email&#039;, &#039;given name&#039;, &#039;full name&#039;, &#039;username&#039;, et cliquer sur le bouton Add selected

## Pour HumHub

* aller à l&#039;adresse [https://login.lescommuns.org/auth/admin](https://login.lescommuns.org/auth/admin) avec un navigateur
* cliquer sur le menu Clients puis sur le bouton Create
* mettre pour Client ID : [communaute.hameaux-legers.org](http://communaute.hameaux-legers.org/)
* selectionner pour Client Protocol : openid-connect
* mettre pour Root URL : [https://communaute.hameaux-legers.org](https://communaute.hameaux-legers.org) et valider
* dans l&#039;onglet Settings :
    * sélectionner pour Access Type : confidential et cliquer sur Save
* cliquer sur l&#039;onglet Credentials :
    * retenir comment revenir à cet onglet pour récupérer la clé
* cliquer sur l&#039;onglet Mappers :
    * cliquer sur le bouton Add Builtin
    * sélectionner les champs &#039;family name&#039;, &#039;email&#039;, &#039;given name&#039;, &#039;username&#039;, et cliquer sur le bouton Add selected
    * cliquer sur le bouton Edit à la ligne &#039;username&#039;, remplacer prefered_username par id et clique sur le bouton Save

# Installation de YesWiki

* dans Plesk, cliquer sur le menu Databases puis sur le sous-domaine [mooc.hameaux-legers.org](http://mooc.hameaux-legers.org/)
    * ajouter une base de donnée et un utilisateur en cliquant sur Add a Dababase
        * mettre pour Database name : yeswiki
        * selectionner pour Related site : [mooc.hameaux-legers.org](http://mooc.hameaux-legers.org/)
        * mettre pour Database user name : yeswiki
        * cliquer sur Generate pour créer un password sécurisé
        * sélectionner pour Access control : Allow remote connections from any host
    * aller dans Websites &amp; Domains, cliquer sur le bouton File Manager et supprimer le fichier index.html
* se connecter en ssh : `ssh [web@hameaux-legers.org](mailto:web@hameaux-legers.org)` (mettre le mot de passe)
* préparer une version de yeswiki avec l&#039;ensemble des extensions installées et les personnalisations qui permettent que yeswiki intéragissent avec humhub. Les modules **lms**, **login-sso** doivent être installées. Le module LMS ne prend pas encore en compte l&#039;intégration des commentaires humhub, ainsi il faut avoir une version personnalisée. De même, la subscrition à des évènements est une partie personnalisée (cf le répertoire custom).
* copier cette version de yeswiki dans le répertoire `~/mooc.hameaux-legers.org/` de l&#039;utilisateur web `rsync -av --itemize-changes * [web@hameaux-legers.org](mailto:web@hameaux-legers.org):~/mooc.hameaux-legers.org/`
* lancer la page [https://mooc.hameaux-legers.org](https://mooc.hameaux-legers.org) dans un navigateur
    * renseigner les champs Nom de votre site et Description
    * mettre dans Machine MySQL : localhost
    * mettre dans Base de données MySQL : yeswiki
    * mettre dans Nom de l&#039;utilisateur MySQL : yeswiki
    * mettre dans Mot de passe MySQL : le mot de passe rentré lors de la création de l&#039;utilisateur (cf plus haut)
    * mettre dans Préfixe des tables : yeswiki_mooc__
    * mettre dans Mot de passe et Confirmation du mot de passe en dessous de Administrateur : un mot de passe sécurisé
    * mettre dans Adresse e-mail : [support@hameaux-legers.org](mailto:support@hameaux-legers.org) et valider
* modifier le fichier de configuration
    * remplacer : `&#039;default_write_acl&#039; =&gt; &#039;*&#039;,* &#039;default_read_acl&#039; =&gt; &#039;&#039;,` par : `&#039;default_write_acl&#039; =&gt; &#039;%&#039;, &#039;default_read_acl&#039; =&gt; &#039;+&#039;,`
    * puis rajouter à la fin du tableau :

```
      &#039;contact_mail_func&#039; =&gt; &#039;smtp&#039;,
      &#039;contact_mail_func&#039; =&gt; &#039;smtp&#039;,
      &#039;contact_smtp_host&#039; =&gt; &#039;ssl0.ovh.net&#039;,
      &#039;contact_smtp_port&#039; =&gt; &#039;465&#039;,
      &#039;contact_smtp_user&#039; =&gt; &#039;support@hameaux-legers.org&#039;,
      &#039;contact_smtp_pass&#039; =&gt; &#039;SMTP_PASSWORD&#039;,
      &#039;contact_from&#039; =&gt; &#039;support@hameaux-legers.org&#039;,
      &#039;BAZ_ADRESSE_MAIL_ADMIN&#039; =&gt; &#039;support@hameaux-legers.org&#039;,
      &#039;disable_wiki_links&#039; =&gt; true,
      &#039;sso_config&#039; =&gt; [
          // the form id for the bazar entry corresponding to the connected user
          // if defined, link will propose to show him his user information (profile)
          // don&#039;t declare it, if you don&#039;t need to have bazar entries related to users
          &#039;bazar_user_entry_id&#039; =&gt; &#039;1000&#039;,
          &#039;providers&#039; =&gt; [
              // each entry here is an array corresponding to a SSO provider
              [
                  // the authentification auth type, two protocols are supported : &#039;oauth2&#039; and &#039;cas&#039;
                  &#039;auth_type&#039; =&gt; &#039;oauth2&#039;,
                  &#039;auth_options&#039; =&gt; [
                      &#039;clientId&#039; =&gt; &#039;mooc.hameaux-legers.org&#039;,
                      // The client ID assigned to you by the provider
                      &#039;clientSecret&#039; =&gt; &#039;SSO_SECRET&#039;,
                      // The client secret assigned to you by the provider
                      &#039;urlAuthorize&#039; =&gt; &#039;https://login.hameaux-legers.org/auth/realms/master/protocol/openid-connect/auth&#039;,
                      &#039;urlAccessToken&#039; =&gt; &#039;https://login.hameaux-legers.org/auth/realms/master/protocol/openid-connect/token&#039;,
                      &#039;urlResourceOwnerDetails&#039; =&gt; &#039;https://login.hameaux-legers.org/auth/realms/master/protocol/openid-connect/userinfo&#039;,
                  ],
                  // sso server fieldname used for the user email, this email links an SSO user to a yeswiki user
                  &#039;email_sso_field&#039; =&gt; &#039;email&#039;,
                  &#039;create_user_from&#039; =&gt; &#039;#[given_name] #[family_name]&#039;,
                  // if create_user_from is defined, an yeswiki user with a name and an email is created.
                  // the username is an unique word (ID) generated from the format create_user_form by specifying #[field_name] to referring to a sso field
                  // if not defined, the authentification module accepts only sso users which have an yeswiki user corresponding to this email
                  &#039;button_style&#039; =&gt; [
                      // name used for the login button
                      &#039;button_label&#039; =&gt; &#039;Les communs&#039;,
                      // class of this button
                      &#039;button_class&#039; =&gt; &#039;btn btn-default btn-lescommuns&#039;,
                      // icon used for this button (class of the &lt;i&gt;)
                      &#039;button_icon&#039; =&gt; &#039;glyphicon glyphicon-log-in&#039;
                      // you can also write a wiki page named &#039;ConnectionDetails&#039; to inform the user before the buttons are displayed
                  ],
                  &#039;bazar_mapping&#039; =&gt; [
                      &#039;fields&#039; =&gt; [
                          &#039;bf_nom&#039; =&gt; &#039;family_name&#039;,
                          &#039;bf_prenom&#039; =&gt; &#039;given_name&#039;,
                          &#039;bf_mail&#039; =&gt; &#039;email&#039;
                      ],
                      &#039;read_access_entry&#039; =&gt; &#039;+&#039;,
                      &#039;write_access_entry&#039;=&gt; &#039;%&#039;,
                      &#039;entry_creation_information&#039; =&gt; &quot;&lt;p&gt;C&#039;est votre première connexion avec ce compte. Une fiche avec vos informations personnelles va être créée dans le but de faciliter la mise en lien entre les utilisateurs. Les données suivantes - Prénom, Nom, E-mail - vont êtres récupérées directement depuis le serveur d&#039;authentification et pourront être modifiées ou supprimées plus tard à votre convenance dans &#039;Mes fiches&#039;.&lt;/p&gt;&quot;,
                  ]
              ]
          ]
      ],
```

Attention à bien remplacer SMTP_PASSWORD et SSO_SECRET avec les bonnes valeurs. Vous pouvez également adapter &#039;entry_creation_information&#039; qui est le message qui sera affichée à la première connexion d&#039;un utilisateur lorsque son compte est créé.

* Accéder à la base de donner en cliquant sur Databases, [mooc.hameaux-legers.org](http://mooc.hameaux-legers.org/) puis sur le bouton phpMyAdmin
    * définir les administrateurs en allant dans la table yeswiki_mooc__triples, et en modifiant la ligne d&#039;id 1 à la colonne value : laisser WikiAdmin mais rajouter les NomWiki des utilisateurs qu&#039;on veut passer administrateur séparer par des espaces ou des retours à la ligne.
    * ajouter un formulaire à yeswiki pour les profils des utilisateurs et avec les champs minimum bf_titre, bf_nom, bf_prenom et bf_email. Puis modifier directement dans la base de données à la table yeswiki_mooc__nature la valeur bn_id_nature de ce formulaire pour qu&#039;elle soit à 1000.
* lancer [https://mooc.hameaux-legers.org/?PagePrincipale/update](https://mooc.hameaux-legers.org/?PagePrincipale/update) dans un navigateur pour rajouter les pages et formulaires utiles au LMS

# Installation de HumHub

## Installation basique

* dans Plesk, cliquer sur le menu Databases puis sur le sous-domaine [communaute.hameaux-legers.org](http://communaute.hameaux-legers.org/)
    * ajouter une base de donnée et un utilisateur en cliquant sur Add a Dababase
        * mettre pour Database name : humhub
        * sélectionner pour Related site : [communaute.hameaux-legers.org](http://communaute.hameaux-legers.org/)
        * mettre pour Database user name : humhub
        * cliquer sur Generate pour créer un mot de passe sécurisé
        * sélectionner pour Access control : Allow remote connections from any host
* dans Plesk, aller dans Websites &amp; Domains / PHP Settings et changer :
    * max_execution_time à 120
    * post_max_size à 16M
    * upload_max_filesize à 16M
* se connecter en ssh : `ssh [web@hameaux-legers.org](mailto:web@hameaux-legers.org)` (mettre le mot de passe) et exécuter les lignes suivantes :
    * `rm ~/communaute.hameaux-legers.org/index.html`
    * `cd /tmp`
    * `wget &lt;[https://www.humhub.com/download/package/humhub-1.8.2.tar.gz](https://www.humhub.com/download/package/humhub-1.8.2.tar.gz)`&gt; (vous pouvez installer une version plus récente en regardant la dernière version stable sur [https://www.humhub.com/en/download](https://www.humhub.com/en/download))
    * `tar xvzf humhub-1.8.2.tar.gz`
    * `cd humhub-1.8.2/`
    * `mv * ~/communaute.hameaux-legers.org/`
    * `mv .htaccess.dist ~/communaute.hameaux-legers.org/`
    * `rm ../humhub-1.8.2* -rf`
* dans Plesk :
    * aller dans Websites &amp; Domains et cliquer sur le bouton Scheduled Tasks (panneau de droite) :
        * cliquer sur Add Task
        * sélectionner pour Webspace : [hameaux-legers.org](http://hameaux-legers.org/)
        * mettre pour Command : `/opt/plesk/php/7.4/bin/php /var/www/vhosts/hameaux-legers.org/communaute.hameaux-legers.org/protected/yii queue/run &gt;/dev/null 2&gt;&amp;1`
        * sélectionner Cron style pour Run puis mettre : \* \* \* \* \*
        * mettre pour Description : pour les traitements longs d&#039;Humhub (script lancé toutes les minutes)
        * sélectionner pour Notify : Do not notify et Valider
    * puis créer une 2ème Task avec les mêmes champs excepté :
        * mettre pour Command : `/opt/plesk/php/7.4/bin/php /var/www/vhosts/hameaux-legers.org/communaute.hameaux-legers.org/protected/yii cron/run &gt;/dev/null 2&gt;&amp;1`
    * aller sur le site [https://communaute.hameaux-legers.org/](https://communaute.hameaux-legers.org/) pour terminer l&#039;install
        * cliquer sur Suivant
        * voir que les prérequis systèmes sont OK et cliquer sur Suivant
        * pour la configuration de la base de données :
            * mettre pour Nom d&#039;hôte : localhost
            * mettre pour Nom d&#039;utilisateur : humhub
            * mettre pour Mot de passe : le mot de passe rentré lors de la création de l&#039;utilisateur (cf plus haut)
            * mettre pour Nom de la base de données : humhub et cliquer sur Suivant
        * mettre pour Nom de votre réseau : Hameaux Légers et cliquer sur Suivant
        * sélectionner pour Je veux utiliser Humhub pour : Ma communauté
        * pour les paramètres de sécurité :
            * cocher : Les utilisateurs externes peuvent s&#039;inscrire
            * décocher : Les nouveaux utilisateurs inscrits doivent être préalablement activés par un administrateur
            * décocher : Autoriser l&#039;accès aux contenus publics pour les utilisateurs non inscrits
            * décocher : Les membres inscrits peuvent inviter des personnes par e-mail
            * décocher : Autoriser le système d&#039;amitié entre les utilisateurs
        * pour les Modules recommandés, cocher tous les modules
        * pour la configuration du compte administrateur :
            * mettre pour identifiant : admin
            * mettre pour E-mail : [support@hameaux-legers.org](mailto:support@hameaux-legers.org)
            * mettre pour Nouveau mot de passe et Confirmer le nouveau mot de passe : définir un mot de passe au hazard (l&#039;utilisateur sera ensuite supprimé)
            * mettre pour Prénom : admin
            * mettre pour Nom : admin et cliquer sur Créer un compte administrateur
        * pour Exemples de contenu, décocher Installer des exemples de contenu
        * vous pouvez ensuite vous logger pour tester le compte admin temporaire

## Configurer les adresses courtes

Rend les urls plus « user friendly », et nécessaire pour les modules ci-dessous

* aller dans Plesk :
    * cliquer sur Websites &amp; Domains et cliquer sur l&#039;onglet Hosting &amp; DNS du site [communaute.hameaux-legers.org](http://communaute.hameaux-legers.org/)
    * cliquer sur Apache &amp; nginx Settings
    * décocher Restrict the ability to follow symbolic links
    * se connecter en ssh : `ssh [web@hameaux-legers.org](mailto:web@hameaux-legers.org)` (mettre le mot de passe)
    * modifier le fichier de conf de humhub en tapant `vi ~/communaute.hameaux-legers.org/protected/config/common.php` et modifier le tableau pour retourner :

      ```
      return [
          &#039;components&#039; =&gt; [
              &#039;urlManager&#039; =&gt; [
                  &#039;showScriptName&#039; =&gt; false,
                  &#039;enablePrettyUrl&#039; =&gt; true,
              ],
          ]
      ];
      ```
* activer les redirections apache en tapant : `mv ~/communaute.hameaux-legers.org/.htaccess.dist ~/communaute.hameaux-legers.org/.htaccess`
* naviguer sur humhub et vérifier que vous avez bien l&#039;url de type [https://communaute.hameaux-legers.org/s/espace-de-bienvenue/](https://communaute.hameaux-legers.org/s/espace-de-bienvenue/) quand vous allez sur l&#039;espace de bienvenue

## Derniers paramétrages post-install

* activer le mode « production » :
    * se connecter en ssh : `ssh [web@hameaux-legers.org](mailto:web@hameaux-legers.org)` (mettre le mot de passe)
    * éditer le fichier index.php via la commande `vi ~/communaute.hameaux-legers.org/index.php`
    * commenter ces deux définition de variables YII_DEBUG et YII_ENV de la manière suivante :

      ```
      // comment out the following two lines when deployed to production
      //defined(&#039;YII_DEBUG&#039;) or define(&#039;YII_DEBUG&#039;, true);
      //defined(&#039;YII_ENV&#039;) or define(&#039;YII_ENV&#039;, &#039;dev&#039;);
      ```
* configurer l&#039;envoi de mail en allant à image de profil en haut à droite / Administration / Paramètre, onglet Avancé puis sous-onglet E-mail :
* mettre pour Adresse expéditeur des e-mails : [support@hameaux-legers.org](mailto:support@hameaux-legers.org)
* mettre pour Nom de l&#039;expéditeur des e-mails : Hameaux Légers
* sélectionner pour Type de transport des e-mails : SMTP
* mettre pour Hostname : [ssl0.ovh.net](http://ssl0.ovh.net/)
* mettre pour Identifiant : [support@hameaux-legers.org](mailto:support@hameaux-legers.org)
* mettre pour Mot de passe : le mot de passe de la messagerie de [support@hameaux-legers.org](mailto:support@hameaux-legers.org)
* mettre pour Port : 465
* mettre pour Chiffrement : SSL
* suite à cette configuration, un mail de test est envoyé à l&#039;administrateur qui a fait le paramétrage

## Install du module auth-keycloak

permet de se connecter au serveur SSO des Communs

* se connecter en ssh : `ssh [web@hameaux-legers.org](mailto:web@hameaux-legers.org)` (mettre le mot de passe)
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
  &#039;components&#039; =&gt; [
        &#039;urlManager&#039; =&gt; [
            &#039;showScriptName&#039; =&gt; false,
            &#039;enablePrettyUrl&#039; =&gt; true,
        ],
    &#039;authClientCollection&#039; =&gt; [ 
      &#039;clients&#039; =&gt; [ 
        &#039;Keycloak&#039; =&gt; [ 
          &#039;class&#039; =&gt; &#039;humhub\modules\authKeycloak\authclient\Keycloak&#039;, 
          &#039;authUrl&#039; =&gt; &#039;https://login.hameaux-legers.org/auth/realms/master/protocol/openid-connect/auth&#039;, 
          &#039;tokenUrl&#039; =&gt; &#039;https://login.hameaux-legers.org/auth/realms/master/protocol/openid-connect/token&#039;, 
          &#039;apiBaseUrl&#039; =&gt; &#039;https://login.hameaux-legers.org/auth/realms/master/protocol/openid-connect&#039;,
          &#039;clientId&#039; =&gt; &#039;communaute.hameaux-legers.org&#039;,
          // Client secret is in the &quot;Credentials&quot; tab (if in the settings &quot;Access Type&quot; is set to &quot;confidential&quot;)
          &#039;clientSecret&#039; =&gt; &#039;SSO_SECRET&#039;,
          // String attribute to match user tables with email or id
          &#039;idAttribute&#039; =&gt; &#039;id&#039;,
          // Keycloak mapper for username: &#039;preferred_username&#039;, &#039;sub&#039; (to use Keycloak ID) or other custom Token Claim Name
          &#039;usernameMapper&#039; =&gt; &#039;sub&#039;,
          // Title of the button (if autoLogin is disabled)
          &#039;title&#039; =&gt; &#039;Connexion via les Communs&#039;,
          // Automatic login
          &#039;autoLogin&#039; =&gt; true,
          // Hide username field in registration form
          &#039;hideRegistrationUsernameField&#039; =&gt; true,
        ],
      ],
    ],
  ],
];
```

Penser à modifier SSO_SECRET avec les bonnes valeurs.

* retourner sur Humhub avec le compte admin temporaire puis cliquer sur l&#039;image de profil en haut à droite / Administration / Modules
* cliquer sur le lien Activer au niveau du module Keycload Sign-In

*Tips* : en cas de mauvaise manip, désactiver le module avec la commande `/opt/plesk/php/7.4/bin/php /var/www/vhosts/hameaux-legers.org/communaute.hameaux-legers.org/protected/yii module/disable auth-keycloak` (remplacer auth-keycload par le module voulu)

## Install du module external-websites

permet d&#039;intégrer les commmentaires humhub dans yeswiki et inversement (possibilité aussi d&#039;intégrer yeswiki dans humhub)

* se connecter en ssh : ssh [web@hameaux-legers.org](mailto:web@hameaux-legers.org) (mettre le mot de passe)
    * installer le module en tapant en ligne de commande :

  ```
  cd ~/communaute.hameaux-legers.org/protected/modules
  git clone https://gitlab.com/cuzy/humhub-modules-external-websites.git external-websites
  ```
    * pour pouvoir inclure les commentaires humhub dans yeswiki, éditer le ficher web.php avec la commande : `vi ~/communaute.hameaux-legers.org/protected/config/web.php` et remplacer le tableau vide par :

  ```
  return [
    &#039;web&#039; =&gt; [
      &#039;security&#039; =&gt;  [
        &quot;headers&quot; =&gt; [
          &quot;Content-Security-Policy&quot; =&gt; &quot;default-src *; connect-src  *; font-src &#039;self&#039;; frame-src https://* http://* *; img-src https://* http://* * data:; object-src &#039;self&#039;; script-src &#039;self&#039; https://* http://* * &#039;unsafe-inline&#039; &#039;report-sample&#039;; style-src * https://* http://* * &#039;unsafe-inline&#039;; frame-ancestors &#039;self&#039; https://mooc.hameaux-legers.org;&quot;
        ]
          ],
      ],
  ];
  ```
* dans HumHub
    * aller dans image de profil en haut à droite / Administration / Modules et activer &quot;External Websites&quot;
    * puis dans image de profil en haut à droite / Administration / Espaces et cliquer sur le bouton Ajouter un espace :
        * mettre pour Nom : &quot;S&#039;installer en habitat réversible&quot;
        * mettre pour Description : &quot;Espace d&#039;échange pour les participants au MOOC « S&#039;installer en habitat réversible »&quot;
        * cliquer sur Paramètres d&#039;accès avancées et sélectionner :
            * Public (utilisateurs enregistrés uniquement)
            * Sur invitation uniquement
        * sur la page suivante, cliquer sur Activer pour le module External Websites
        * aller dans roue crantée / Réglages puis l&#039;onglet avancée, mettre pour Url : mooc-habitat-reversible et cliquer sur Enregistrer
    * dans l&#039;espace du MOOC, cliquer sur roue crantée / Sécurité puis l&#039;onglet Permissions. Au niveau de la ligne Inviter des utilisateurs, sélectionner Interdire
    * aller dans roue crantée / Gérer les sites web externes et les paramètres et cliquer sur Ajouter un site web
        * mettre dans Titre : MOOC
        * sélectionner pour Humhub est intégré : Oui
        * mettre dans URL de la première page du site Web : [https://mooc.hameaux-legers.org](https://mooc.hameaux-legers.org)
        * mettre dans Afficher dans le menu de l&#039;espace : Oui
        * et cliquer sur Ajouter ce site web
* créer un groupe pour les utilisateurs du MOOC :
    * aller dans image de profil en haut à droite / Administration / Utilisateurs, onglet Groupes et cliquer sur Ajouter un groupe
        * mettre pour Nom : Participants MOOC
        * mettre pour Description : Groupe des participants au MOOC « S&#039;installer en habitat réversible »
        * ajouter pour Default Space(s) : S&#039;installer en habitat réversible
        * cliquer sur Enregistrer
* se connecter en ssh : `ssh [web@hameaux-legers.org](mailto:web@hameaux-legers.org)` (mettre le mot de passe)
    * `vi ~/communaute.hameaux-legers.org/protected/config/common.php`
    * intégrer les lignes suivantes en dernier élément du tableau principal (modifier API_SECRET_KEY) :

      ```
      &#039;modules&#039; =&gt; [
        &#039;external-websites&#039; =&gt; [
          &#039;registerAssetsIfHumhubIsEmbedded&#039; =&gt; true,
          &#039;jwtKey&#039; =&gt; &#039;API_SECRET_KEY&#039;,
        ],
      ],
      ```

      Au final, voici le tableau qu&#039;on obtient (penser à modifier SSO_SECRET et API_SECRET_KEY avec les bonnes valeurs)

```
return [
  &#039;components&#039; =&gt; [
    &#039;urlManager&#039; =&gt; [
        &#039;showScriptName&#039; =&gt; false,
        &#039;enablePrettyUrl&#039; =&gt; true,
    ],
    &#039;authClientCollection&#039; =&gt; [ 
      &#039;clients&#039; =&gt; [ 
        &#039;Keycloak&#039; =&gt; [ 
          &#039;class&#039; =&gt; &#039;humhub\modules\authKeycloak\authclient\Keycloak&#039;, 
          &#039;authUrl&#039; =&gt; &#039;https://login.hameaux-legers.org/auth/realms/master/protocol/openid-connect/auth&#039;, 
          &#039;tokenUrl&#039; =&gt; &#039;https://login.hameaux-legers.org/auth/realms/master/protocol/openid-connect/token&#039;, 
          &#039;apiBaseUrl&#039; =&gt; &#039;https://login.hameaux-legers.org/auth/realms/master/protocol/openid-connect&#039;,
          &#039;clientId&#039; =&gt; &#039;communaute.hameaux-legers.org&#039;,
          // Client secret is in the &quot;Credentials&quot; tab (if in the settings &quot;Access Type&quot; is set to &quot;confidential&quot;)
          &#039;clientSecret&#039; =&gt; &#039;SSO_SECRET&#039;,
          // String attribute to match user tables with email or id
          &#039;idAttribute&#039; =&gt; &#039;id&#039;,
          // Keycloak mapper for username: &#039;preferred_username&#039;, &#039;sub&#039; (to use Keycloak ID) or other custom Token Claim Name
          &#039;usernameMapper&#039; =&gt; &#039;sub&#039;,
          // Title of the button (if autoLogin is disabled)
          &#039;title&#039; =&gt; &#039;Connexion via les Communs&#039;,
          // Automatic login
          &#039;autoLogin&#039; =&gt; true,
          // Hide username field in registration form
          &#039;hideRegistrationUsernameField&#039; =&gt; true,
        ],
      ],
    ],
  ],
  &#039;modules&#039; =&gt; [
    &#039;registerAssetsIfHumhubIsEmbedded&#039; =&gt; true,
      &#039;external-websites&#039; =&gt; [
          &#039;jwtKey&#039; =&gt; &#039;API_SECRET_KEY&#039;,
      ],
  ],
];
```

Pour info, voici le payload associé à cette jwtKey afin que qu&#039;un nouvel utilisateur soit automatiquement ajouté aux groupes d&#039;ID 1 et 2 (cf [doc de l&#039;extension external-website](https://gitlab.com/cuzy/humhub-modules-external-websites/-/tree/master/docs)) :

```
{
 &quot;groupsId&quot;: [1, 2]
}
```

## Paramétrage du module « external websites » dans YesWiki

* rajouter la librairie contenante de iframe-resizer (si elle n&#039;existe pas déjà) en exécutant :

```
cd ~/mooc.hameaux-legers.org/custom/javascripts/
wget https://gitlab.com/funkycram/humhub-modules-external-websites/-/raw/master/resources/js/iframeResizer/iframeResizer.js
```

* modifier le fichier fiche-1201.tpl.html en exécutant `vi ~/mooc.hameaux-legers.org/tools/lms/templates/bazar/fiche-1201.tpl.html` :
    * modifier la valeur de la variable $humhubUrl (ligne 82) et mettre : &#039;[https://communaute.hameaux-legers.org](https://communaute.hameaux-legers.org)&#039;
    * modifier la valeur de la variable $spaceUrl (ligne 84) et mettre : &#039;mooc-habitat-reversible&#039; (l&#039;id est normalement à 1, sinon sinon récupérer son Id dans le menu roue crantée / Gérer les sites web externes)
    * modifier la valeur de la variable $humhubWebsiteId (ligne 86) et mettre : 1
    * modifier la valeur de la variable $autoLogin (ligne 90) et mettre : 1
    * modifier la valeur de la variable $token (ligne 92) et mettre :
    * modifier la valeur de la variable $humhubUrl et mettre : le TOKEN calculé à partir du payload et de API_SECRET_KEY en suivant la procédure décrite dans la [doc de l&#039;extension external-website](https://gitlab.com/cuzy/humhub-modules-external-websites/-/tree/master/docs)

# Resssouces

* [installation classique de YesWiki](https://yeswiki.net/?DocumentationInstallation)
* [préparation du serveur pour HumHub](https://docs.humhub.org/docs/admin/server-setup/)
* [installation de HumHub](https://docs.humhub.org/docs/admin/installation/)
* [doc de l&#039;extension auth-keycloak (HumHub)](https://github.com/cuzy-app/humhub-modules-auth-keycloak)
* [doc de l&#039;extension external-website (HumHub)](https://gitlab.com/cuzy/humhub-modules-external-websites/-/tree/master/docs)