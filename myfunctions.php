<?php

/**
 * Recupère les adresses IP utilisés en fixe depuis le fichier d'hosts du serveur DHCP
 * @return ips La liste des ips utilisés
 */
function get_ips() {
  $ips=array();
  exec("cat /etc/dhcp/v4/hosts.conf|grep fixed-address|awk -F \" \" '{print $2}'|awk -F \";\" '{print $1}'", $ips, $retval);
  return $ips;
}

/**
 * Recupère les noms d'hôtes utilisés en fixe depuis le fichier d'hosts du serveur DHCP
 * @return names La liste des hosts
 */
function get_names() {
  $names=array();
  exec("cat /etc/dhcp/v4/hosts.conf|grep host|awk -F \" \" '{print $2}'", $names, $retval);
  return $names;
}

/**
 * Dis si c'est le name qui est utilisé comme FAI actuel depuis le fichier dhcpd.conf
 * @param name Le nom a verifier
 * @return Un booleen
 */
function get_used_iap($name) {
  $used=array();
  exec("cat /etc/dhcp/dhcpd.conf|grep include|awk -F \" \" '{print $2}'|awk -F \";\" '{print $1}'|awk -F \"/\" '{print $5}'|awk -F \".\" '{print $1}'", $used, $retval);
  if(sizeof($used)>2) {
           $to_check="$used[1].conf";
    return $to_check==$name;
  } else {
    return "";
  }
}

/**
 * Les éléments à inclure dans la page
 */
function get_html_includes() {
  return "<link href=\"https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css\" rel=\"stylesheet\" integrity=\"sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC\" crossorigin=\"anonymous\">
    <script src=\"https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js\" integrity=\"sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM\" crossorigin=\"anonymous\"></script>";
}

/**
 * La balise de redirection
 */
function get_redirect() {
  return "<meta http-equiv=\"refresh\" content=\"5;url=index.php\"/>";
}
