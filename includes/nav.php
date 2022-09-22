<nav class="nav">
  <ul class="nav__list">
    <li class="nav__list">
      <a href="./index.php
              " class="nav__link">Home</a>
    </li>
    <?php if ($userConnect) : ?>
      <li class="nav__list">
        <a href="./form-article.php
              " class="nav__link">Ecrire un article</a>
      </li>
      <li class="nav__list">
        <a href="./logout.php
              " class="nav__link">Se déconnecter</a>
      </li>
    <?php else : ?>

      <li class="nav__list">
        <a href="./register.php
          " class="nav__link">Inscription</a>
      </li>

      <li class="nav__list">
        <a href="./login.php
          " class="nav__link">Mon compte</a>
      </li>
      <li class="nav__list"><a href="" class="nav__link">Gallerie</a></li>
      <li class="nav__list">
        <a href="" class="nav__link">Tarifs et prestations</a>
      </li>
      <li class="nav__list"><a href="" class="nav__link">Contact</a></li>
    <?php endif ?>
  </ul>
</nav>