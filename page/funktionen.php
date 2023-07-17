<?php
function makeTable($query, $value = NULL, $makeLastInsertRed = true) 
{
    global $con;

    try 
    {
        $stmt = makeStatement($query, $value);
        
        /* Tabelle mit "dynamischer" Spaltenbezeichnung mittels meta-Daten */

        echo '<table class="table">
              <tr>';
        $colCount = $stmt->columnCount();

        $meta = array();

        // Iteriert über alle Spalten-IDs
        for ($i = 0; $i < $colCount; $i++) 
        {
            // Befüllt den array "meta" mit den Metadaten der jeweiligen Spalte
            $meta[] = $stmt->getColumnMeta($i);

            // Gibt den jeweiligen Spaltennamen als Pberschrift in der Tabelle aus
            echo '<th>'.$meta[$i]['name'].'</th>';
        }

        echo '</tr>';
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) 
        {
            $row = array_values($row);
            if ($row[1] == $value && $makeLastInsertRed) 
            {
                echo '<tr style="background-color:lime">';
            }
            else 
            {
                echo '<tr>';
            }
            foreach ($row as $r) {
                echo '<td>'.$r.'</td>';
            }
            echo '</tr>';
        }
        echo '</table>';
    }
    catch (Exception $e) 
    {
        echo 'Error - Tabelle Adressen: '.$e->getCode().': '.$e->getMessage().'<br>';
    }
}

function makeStatement($query, $executeArray = NULL) 
{
    global $con;
    // $con = include_once 'conf.php';
    $stmt = $con->prepare($query);
    $stmt->execute($executeArray);
    return $stmt;
}