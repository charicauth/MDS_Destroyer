
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top ">
  <a class="navbar-brand" href="#"><?= $Auth->getPseudo() ?></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarText">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="<?= $Router->buildUrl('joueurs', 'dashboard') ?>">Dashboard</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?= $Router->buildUrl('joueurs', 'inventaire') ?>">Inventaire</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?= $Router->buildUrl('equipements', 'shop') ?>">Equipements</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?= $Router->buildUrl('objets', 'shop') ?>">Objets</a>
      </li>
    </ul>
    <span class="navbar-text pr-2">
        <?= $Auth->getNiveau() ?>
    </span>
    <img src="assets/img/round-star.png" width="30" height="24" class="d-inline-block align-top mr-4" alt="">
    <span class="navbar-text pr-2">
        <?= $Auth->getSolde() ?>
    </span>
    <img src="assets/img/crown-coin.png" width="30" height="30" class="d-inline-block align-top " alt="">
   
    <?= 
      openForm([
        CONTROLLER => 'joueurs', 
        ACTION => 'deconnexion',
        'class' => 'form-inline my-2 my-lg-0 pl-4'
      ]) .
      submit('Déconnexion', ['color' => DANGER]) .
      closeForm();
    ?>
  </div>
</nav>