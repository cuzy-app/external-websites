TODOS 
=====

- If the external website is embedded, it is possible to hide some elements by adding data attributes in the `<head>` tag:
  - `data-external-comments="0"` will hide comments (in this case, no sidebar is shown, the addons are shown above the external page)
  - `data-external-likes="0"` will hide likes
  - `data-external-permalink="0"` will hide permalink

Détection si intégré en iframe : le site externe doit envoyer une info (via iframeResizer pour ne pas modifier l'URL) qui sera enregistrée sous forme de `$_SESSION['humhub_is_embedded'] = true;` pour que l'on sache que l'on est encore intégré quand on surfe dans Humhub.
Si `$_SESSION['humhub_is_embedded']`, ca charge un JS via les assets qui:
- Ajoute la classe `humhub-is-embedded` dans le tag `<html>` pour que le thème puisse masquer les menus
- Charge iframeResizer en tant que embedded pour que le site externe qui intègre Humhub puisse régler la hauteur de l'iframe

Paramétrage dans l'espace pour indiquer que intégré dans un site externe (URL).
Si oui et si pas déjà dans l'iframe (`!isset($_SESSION['humhub_is_embedded']) || $_SESSION['humhub_is_embedded'] === false`), rediriger les liens concernant cet espace vers le site externe qui devra appeler la bonne sous page de l'espace en iframe 
