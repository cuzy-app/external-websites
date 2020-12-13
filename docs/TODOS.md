TODOS 
=====

- If the external website is embedded, it is possible to hide some elements by adding data attributes in the `<head>` tag:
  - `data-external-comments="0"` will hide comments (in this case, no sidebar is shown, the addons are shown above the external page)
  - `data-external-likes="0"` will hide likes
  - `data-external-permalink="0"` will hide permalink

- Tester avec différents navigateurs comme Safari

Paramétrage dans l'espace pour indiquer que intégré dans un site externe (URL).
Si oui et si pas déjà dans l'iframe (`!isset($_SESSION['humhub_is_embedded']) || $_SESSION['humhub_is_embedded'] === false`), rediriger les liens concernant cet espace vers le site externe qui devra appeler la bonne sous page de l'espace en iframe 

Update README.md (Overview and Features)