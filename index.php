<?php
// Récupération de la liste des GULL Francophones
// On génère une adresse email à partir du nom de domaine
function getEmail($url) {
    $pattern = array(
        "/^((http|https):\/\/([\w]{3})\.|(http|https):\/\/)/"
    );
    $replacement = array(
        "contact@"
    );
    $url = preg_replace($pattern, $replacement, $url);
    return $url;
}

// On lance une instance de SimpleHTMLDOM pour parser la liste de l'Agenda du Libre
require_once "simplehtmldom/simple_html_dom.php";
$html = new simple_html_dom();

// On lance la boucle de parsing
$list = array();
for($i=1; $i<=12; $i++) {
    $html->load_file("https://www.agendadulibre.org/orgas?page=" . $i . "&q[kind_id_eq]=3");
    foreach($html->find(".active") as $lug) {
        $list[] = array(
            //type
            "GULL",
            //nom
            trim($lug->find(".name",0)->plaintext),
            //portable
            "",
            //fixe
            "",
            // mail
            getEmail($lug->find(".url",0)->children(0)->href),
            //date envoi
            "",
            //date réponse
            "",
            //twitter
            "",
            //ville
            trim($lug->find(".city",0)->plaintext),
            //contact
            "",
        );
    }
}

echo "<pre>";
print_r($list);
echo "</pre>";

// On crée le fichier CSV et le délimiteur
$file = "liste_contact_gull.csv";
$separator = ",";
$fileOpen = fopen($file, "w+");
foreach($list as $listItem){
    fputcsv($fileOpen, $listItem, $separator);
}
fclose($file);
?>
