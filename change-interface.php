<?php
	require_once("myfunctions.php");
	require_once("config.php");
	$network=$_GET["network"];

	for($i=0;$i<sizeof($hosts);$i++) {
		if($network==$hosts[$i]) {
			$hostFile = $hosts_files[$i];
			system("sudo cp /etc/dhcp/dhcpd.$hostFile /etc/dhcp/dhcpd.conf");
		}
	}

	system("sudo systemctl restart isc-dhcp-server");
?>
<html>
  <head>
    <?php echo get_html_includes(); ?>
	<?php echo get_redirect(); ?>
  </head>
  <body>
  <div class="jumbotron text-center">
      <h1>En attente de mise à jour des informations</h1>
      <p>Tout s'est bien passé</p>
	  <h2>Pensez à redémarrer les interfaces réseaux clients</h2>
	  <p>Redirection dans 5 secondes</p>
    </div>
  </body>
</html>
