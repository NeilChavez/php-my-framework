<?php
echo $renderer->render('header');
?>
<h1>Hello, from module index!</h1>
<h2>params: <?php echo $name ?></h2>
<h3>Test view</h3>
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