<?php
echo $renderer->render('header');
?>
<h1>Hello form INDEX, params: <?php echo $name ?></h1>
<h1>Test view</h1>
<ul>
  <li>
    
    <a href=" 
    <?php echo $router->generateUri('blog.show', ['slug' => 'first-article', 'id' => '1']); ?>">
    Element 1

    </a>

  </li>
  <li>Element 2</li>
  <li>Element 3</li>
  <li>Element 4</li>
  <li>Element 5</li>
</ul>

<?php

echo $renderer->render('footer');

?>