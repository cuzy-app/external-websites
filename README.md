Intégrer une URL et quand on change de page dans l'URL ça génère à chaque fois un content Humhub qui permet de commenter sur l'URL concernée.

Aussi, adapter la hauteur de l'iframe (ou utiliser embed ?) pour ne pas afficher de scroll dans l'iframe, et réadapter au fur et à mesure de la navigation.

- liste URLs à ne pas commenter paramétrable avec une case à cocher pour les admins
- paramétrer le nom du module, icone et l'URL à intégrer ou bien comme le module custom pages, de pouvoir faire une instance par URL

Cloner le module custom page ?


## Description

Iframe module for Humhub.
Enables to create pages integrating iframe content.
Uses [iFrame Resizer](https://github.com/davidjbradshaw/iframe-resizer).
Creates a content each time the URL in the iframe changes, and shows related comments.

## Usage

You must copy `iframeResizer.contentWindow.min.js` file (present in the `for-iframed-website` of this humhub plugin) on the server hosting the website contained within your iFrame and load it adding this code just before `</body>` :
```
<script type="text/javascript" src="path-to-js-files/iframeResizer.contentWindow.min.js"></script>
```