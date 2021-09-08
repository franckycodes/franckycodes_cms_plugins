<?php
use franckycodes\database\LightDb;

require_once 'XLSXReader/XLSXReader.php';
function readExcelPlugins($file = 'somefile.xlsx')
{
    $datas = new XLSXReader($file);

    $sheets = $datas->getSheetNames();
    $list = $datas->getSheetData($sheets[1]);

    // echo '<table>';
    // echo '<tr>';

    // echo '<th>site</th>';
    // echo '<th>initial</th>';
    // echo '<th>matricule</th>';
    // echo '<th>nom</th>';
    // echo '<th>fonction</th>';
    // echo '<th>codir</th>';
    // echo '<th>encadreur</th>';
    // echo '<th>embauche</th>';
    // echo '<th>fin 3 mois</th>';
    // echo '<th>fin 6 mois</th>';
    // echo '</tr>';
    // echo '<tr>';
    $db = new LightDb();

    foreach ($list as $result) {
        $site = $result[0];
        $initial = $result[1];
        $matricule = $result[2];
        $nom = $result[3];
        $fonction = $result[4];
        $codir = $result[5];
        $encadreur = $result[6];

        if ($site != 'site') {

            //check encadreur
            $checkEncadreur = $db->query('SELECT * FROM encadreur_responsable_table WHERE LOWER(nom)=LOWER(:qTest)',
                true,
                ['qTest' => $encadreur], true, true);

            if (gettype($checkEncadreur) != 'boolean') {
                $encadreur = $checkEncadreur['id'];
                //check codir
                $codir = $db->query('SELECT * FROM departements_table WHERE encadreur_responsable_id=:qEncadreur',
                    true,
                    ['qEncadreur' => $checkEncadreur['id']], true, true);

                if (gettype($codir) != 'boolean') {
                    $codir = $codir['dep_codir_id'];
                }
            }

            $dateEmbauche = $datas->toUnixTimeStamp((int) $result[7]);
            $fin3mois = $datas->toUnixTimeStamp((int) $result[8]);
            $mailRappel1 = $result[9];
            $mailRappel2 = $result[10];
            $decision1 = $result[11];
            $fin6mois = $datas->toUnixTimeStamp((int) $result[12]);
            $mailRappel3 = $result[13];
            $mailRappel4 = $result[14];
            $decision2 = $result[15];

            $db->query('INSERT INTO users_periode_essai(user_nom, user_site, user_initial,
        user_matricule,
        user_fonction,
        date_embauche,
        date_fin3mois,
        date_fin6mois,
        encadreur_responsable_id,
        user_codir_id)
        VALUES(:qNom,
        :qSite,
        :qInitial,
        :qMatricule,
        :qFonction,
        :qEmbauche,
        :qFin3,
        :qFin6,
        :qEncadreur,
        :qCodir)
        ',
                true,
                ['qNom' => $nom,
                    'qSite' => $site,
                    'qInitial' => $initial,
                    'qMatricule' => $matricule,
                    'qFonction' => $fonction,
                    'qEmbauche' => date('Y-m-d', $dateEmbauche),
                    'qFin3' => date('Y-m-d', $fin3mois),
                    'qFin6' => date('Y-m-d', $fin6mois),
                    'qEncadreur' => $encadreur,
                    'qCodir' => $codir,
                ]);
        }
        // $query='INSERT INTO users_periode_essai'
        // echo '<td>' . $site . '</td>';
        // echo '<td>' . $initial . '</td>';
        // echo '<td>' . $matricule . '</td>';
        // echo '<td>' . $nom . '</td>';
        // echo '<td>' . $fonction . '</td>';
        // echo '<td>' . $codir . '</td>';
        // echo '<td>' . $encadreur . '</td>';
        // echo '<td>' . date('d/m/Y', $dateEmbauche) . '</td>';
        // echo '<td>' . date('d/m/Y', $fin3mois) . '</td>';
        // echo '<td>' . date('d/m/Y', $fin6mois) . '</td>';
        // echo '</tr>';
        // echo '<pre>';
        // var_dump($result);
        // echo '</pre>';
    }
    // echo '</table>';

    // exit();

    header('Location: ' . WEBROOT . 'profile/');
    die();

}
