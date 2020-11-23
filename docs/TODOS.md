TODOS 
=====

- Config to manage pages
- Possibility for admin to change comment state for an URL

Piste d'amélioration : remplacer le iframe par file_get_contents :
```
$content = file_get_contents('https://www.site.com');

$dom = new domDocument('1.0', 'utf-8'); 
// load the html into the object 
libxml_use_internal_errors(true);
$dom->loadHTML($content); 
libxml_use_internal_errors(false);
//discard white space 
$dom->preserveWhiteSpace = false; 

$xpath = new DOMXpath($dom);

$node = $xpath->query('//div[@class="class-name"]');
echo $dom->saveHTML($node->item(0));
```