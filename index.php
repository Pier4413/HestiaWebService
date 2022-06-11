<?php
  require_once("myfunctions.php");
?>
<html>
  <head>
    <?php echo get_html_includes(); ?>
  </head>
  <body>
    <div class="jumbotron text-center">
      <h1>Bienvenue sur la page de configuration rapide d'Hestia</h1>
      <p>Vous pouvez ici ajouter un nouvel hôte avec une ip fixe ou encore changer le réseau Internet de sortie (Free ou Orange)</p>
    </div>

    <!-- Tabs navs-->
    <ul class="nav nav-tabs" id="myTab" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="change-interface-tab" data-bs-toggle="tab" data-bs-target="#change-interface" type="button" role="tab" aria-controls="change-interface" aria-selected="true">Changer réseau</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="add-host-tab" data-bs-toggle="tab" data-bs-target="#add-host" type="button" role="tab" aria-controls="add-host" aria-selected="false">Ajouter hôte</button>
      </li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
      <div class="tab-pane active" id="change-interface" role="tabpanel" aria-labelledby="change-interface-tab">
        <form action="change-interface.php">
          <div class="form-check">
            <?php
            require_once("config.php");
            for($i=0;$i<sizeof($hosts);$i++) {
              $networkName="network$i";
              ?>
              <input class="form-check-input" type="radio" name="network" id=<?php echo $networkName; ?> value=<?php echo $hosts[$i]; ?> <?php echo get_used_iap($hosts_files[$i]) ? "checked" : "";?>>
              <label class="form-check-label" for=<?php echo $networkName; ?>>
                <?php echo ucfirst(strtolower($hosts[$i])); ?>
              </label>
            <?php
		echo "</br>";
            }
            ?>
          </div>
          <button type="submit" class="btn btn-primary">Valider</button>
        </form>
      </div>
      <div class="tab-pane" id="add-host" role="tabpanel" aria-labelledby="add-host-tab">
        <form action="add-host.php">
          <div class="form-group">
            <label for="name">Nom d'hôte</label>
            <input type="text" class="form-control" id="name" name="name" aria-describedby="nameHelp" placeholder="Entrer Nom d'hôte">
            <small id="nameHelp" class="form-text text-muted">Un nom d'hôte est le nom simplifié et facile à retenir sur le réseau d'une machine</small>
          </div>
          <div class="form-group">
            <label for="mac">Adresse MAC</label>
            <input type="text" class="form-control" id="mac" name="mac" aria-describedby="macHelp" placeholder="Entrer Adresse MAC">
            <small id="macHelp" class="form-text text-muted">Une adresse MAC est l'adresse de la carte réseau visée, elle est composé de six groupes de deux caractères séparés par des tirets ou des deux-points</small>
          </div>
          <div class="form-group">
            <label for="ip">Adresse Fixe</label>
            <input class="form-control" type="text" id="ip" name="ip" aria-describedby="ipHelp" placeholder="Adresse IP">
            <!--<input type="text" class="form-control" id="ip1" aria-describedby="ipHelp" placeholder="Adresse IP">-->
            <small id="ipHelp" class="form-text text-muted">L'adresse IP fixe à fournir attention de ne pas prendre une adresse déjà existante. L'adresse que vous attribuez doit être dans la plage 192.168.1.201-192.168.1.254</small>
          </div>
	  <button type="submit" class="btn btn-primary">Valider</button>
        </form>
        <table class="table table-striped">
          <thead>
            <tr>
              <th scope="col">Noms déjà en cours d'utilisation</th>
              <th scope="col">Adresses déjà en cours d'utilisation</th>
            </tr>
          </thead>
          <tbody>
            <?php
              $ips=get_ips();
              $names=get_names();
              if(sizeof($names)==sizeof($ips)) {
                for($i=0;$i<sizeof($ips);$i++) {
                  $name=$names[$i];
                  $ip=$ips[$i];
                  echo "<tr><th scope='row'>$name</th><td>$ip</td></tr>";
                }
              }
              ?>
          </tbody>
        </table>
      </div>
    </div>
    <!-- End of tabs -->
  </body>
</html>
