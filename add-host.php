<?php
	require_once("myfunctions.php");

	$name=$_GET['name'];
	$mac=$_GET['mac'];
	$ip=$_GET['ip'];

	$okCharMac=['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F'];
	$tester=true;

	$names=get_names();
	$ips=get_ips();

  $error="<ul>";
  $ok=null;

	// On check si le nom d'hote est déjà utilisé
	if(in_array($name, $names)) {
		$error.="<li>Probleme le nom d'hote fourni est déjà utilise</li>";
		$tester = false;
	}

	// On commence par remplacer, si nécessaire, les - par des : puis par vérifier que l'adresse MAC est correctement formée
	$mac=str_replace('-', ':', $mac);
	$mac=strtoupper($mac);
	$macArray=explode(":", $mac);
	if(sizeof($macArray)!=6)
	{
		$error.="<li>Probleme avec l'adresse MAC fournie retour à l'origine, trop longue. Redirection dans deux secondes</li>";
		//$error.="<meta http-equiv=\"refresh\" content=\"2;url=index.php\"/>";
		$tester=false;
	}

	for($i=0;$i<sizeof($macArray);$i++)
	{
		if(strlen($macArray[$i])!=2) {
			$error.="<li>Probleme avec l'adresse MAC. L'un des parametres ne fait pas deux caracteres comme attendu</li>";
			$tester=false;
			break;
		}

		if(in_array(substr($macArray[$i], 0, 1), $okCharMac) == false || in_array(substr($macArray[$i], 1, 1), $okCharMac) == false) {
			$error.="<li>Probleme avec l'adresse MAC fourni l'un des caracteres est faux (0-9/A-F). Redirection dans deux secondes</li>";
			$tester=false;
			break;
		}
	}

	// Partie IP
	$ipATester=explode(".", $ip);
 	if(sizeof($ipATester)!=4) {
        	$error.="<li>Le modele fourni ne correspond pas à une adresse IP</li>";
          $tester = false;
        }

       	if(intval($ipATester[0])!=192||intval($ipATester[1])!=168||intval($ipATester[2])!=1) {
          $error.="<li>L'adresse IP fourni n'est pas dans notre sous-réseau (192.168.1.0)</li>";
          $tester = false;
        }

	if(sizeof($ips)!=0) {
    for($i;$i<sizeof($ips);$i++) {
      if(intval(substr($ips[$i], 3, 1))==intval($ipATester[3]) && intval($ipATester[3]) > 200 && intval($ipATester[3]) < 255) {
        $error.="<li>L'adresse que vous souhaitez utiliser est déjà prise ou dans un intervalle non autorisée != (201-254)</li>";
        $tester=false;
        break;
      }
    }
	}

	if($tester==true) {
		$firstLine="host $name {";
		$secondLine="\"hardware ethernet $mac;\"";
		$thirdLine="\"fixed-address $ip;\"";
		$fourthLine="}";

		$save=exec("echo $firstLine|sudo tee -a /etc/dhcp/v4/hosts.conf");
		$save.=exec("echo $secondLine|sudo tee -a /etc/dhcp/v4/hosts.conf");
		$save.=exec("echo $thirdLine|sudo tee -a /etc/dhcp/v4/hosts.conf");
		$save.=exec("echo $fourthLine|sudo tee -a /etc/dhcp/v4/hosts.conf");
		$save.=exec("sudo systemctl restart isc-dhcp-server");

		$bindDirection="$name	IN	A	$ip";
		$save.=exec("echo $bindDirection|sudo tee -a /etc/bind/db.delmasweb.local");

		$bindReverse="$ipATester[3]	IN	PTR	$name.delmasweb.local.";
		$save.=exec("echo $bindReverse|sudo tee -a /etc/bind/1.168.192.db");

		$save.=exec("sudo systemctl restart bind9");

		$ok="Tout s'est bien passé. Vous allez être redirigé vers la page d'accueil dans 5 secondes";
	}
?>
<html>
  <head>
    <?php echo get_html_includes(); ?>
    <?php echo get_redirect(); ?>
  </head>
  <body>
  <div class="jumbotron text-center">
      <h1>En attente de mise à jour des informations</h1>
      <p>
        <?php
        if($ok!=null) {
          echo $ok;
        } else {
          echo "Vous avez des erreurs";
          echo $error;
          echo "</ul>";
        }
        ?>
      </p>
      <p>Redirection dans 5 secondes</p>
    </div>
  </body>
</html>
