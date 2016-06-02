<?php

$allowedExtensions = array('xml');

$explodedFilename = explode('.', $_FILES['file']['name']);

$extension = end($explodedFilename);

if(
        ($_FILES['file']['type'] == 'text/xml') &&
        (($_FILES['file']['size'] <= 500000) && (in_array($extension, $allowedExtensions)))
) {
    if($_FILES['file']['error'] > 0) {
        echo 'Greska: ' . $_FILES['file']['error'] . '<br />';
    } else {
        if(file_exists('files/' . $_FILES['file']['name'])){
//            echo '<p style="color:#FF0000;">' . $_FILES['file']['name'] . ' vec postoji!</p>';
            unlink('files/' . $_FILES['file']['name']);
            echo 'Fajl sa istim imenom je vec postojao i sada je izbrisan!<br />';
            echo '<a href="index.php">Pokusaj ponovo</a>';
        } else {
            $filePath = 'files/' . $_FILES['file']['name'];
            move_uploaded_file($_FILES['file']['tmp_name'], $filePath);

            $xml = simplexml_load_file($filePath);

            foreach($xml->xpath('InvoiceHeader') as $header){
//                echo 'ID: ' . $header->ID . '<br />';
//                echo 'Number: ' . $header->Number . '<br />';
//                echo 'Type: ' . $header->Type . '<br />';
//                echo 'OrderID: ' . $header->OrderID . '<br />';
                echo 'Porudzbenica broj: ' . $header->OrderNumber . '<br />';
//                echo 'ComitentKey: ' . $header->ComitentKey . '<br />';
                echo 'Komitent: ' . $header->Comitent . '<br />';
                echo 'Broj porucenih artikala: ' . $header->ItemsCount . '<br />';
//                echo 'Created: ' . $header->Created . '<br />';
                echo 'Porudzbina poslata: ' . substr($header->Booked, 0, 10) . ' u ' . substr($header->Booked, 11, 8) . ' sati.' . '<br />';
                echo 'Valuta porudzbenice: ' . substr($header->Valuta, 0, 10) . '<br />';
                echo 'Cena: ' . $header->Price . '<br />';
                echo 'Neto iznos: ' . $header->NettoValue . '<br />';
                echo 'Rabat: ' . $header->Rebate . '<br />';
                echo 'PDV: ' . $header->Tax . '<br />';
            }

            echo '<!DOCTYPE html>
            <html>
                <head>
                    <title>Faktura</title>
                    <style>
                        table{
                            font-family: "Lucida Grande", "Lucida Sans Unicode", Verdana, Arial, Helvetica, sans-serif;
                            border-collapse: collapse;
                            width: 80%;
                        }
                        table, th, td {
                            border: 2px solid grey;
                        }
                        tr:nth-child(even){
                            background-color: lightgrey;
                        }
                    </style>
                </head>';


            echo '<table>
                <tr>
                    <th>RB</th>
                    <!--<th>Manufacturer</th>-->
                    <!--<th>ArticleCode</th>-->
                    <th>Artikl</th>
                    <!--<th>SerialNumber</th>-->
                    <!--<th>SerialDuration</th>-->
                    <!--<th>Unit</th>-->
                    <th>Kolicina</th>
                    <th>Cena</th>
                    <!--<th>Rebate</th>-->
                    <th>Iznos</th>
                    <!--<th>NettoPrice</th>-->
                    <!--<th>Tax</th>-->
                    <!--<th>CassaGroupID</th>-->
                    <!--<th>BarCode</th>-->
                </tr>';
            $redniBroj = 1;
            foreach($xml->xpath('InvoiceItems') as $invoiceItem){
                echo '<tr>';
//                echo '<td>' . $invoiceItem->ID . '</td>';
                echo '<td>' . $redniBroj . '</td>';
//                echo '<td>' . $invoiceItem->Manufacturer . '</td>';
//                echo '<td>' . $invoiceItem->ArticleCode . '</td>';
                echo '<td>' . $invoiceItem->ArticleName . '</td>';
//                echo '<td>' . $invoiceItem->SerialNumber . '</td>';
//                echo '<td>' . substr($invoiceItem->SerialDuration, 0, 10) . '</td>';
//                echo '<td>' . $invoiceItem->Unit . '</td>';
                echo '<td>' . $invoiceItem->Amount . '</td>';
                echo '<td>' . round(floatval($invoiceItem->Price), 2) . '</td>';
//                echo '<td>' . $invoiceItem->Rebate . '</td>';
                echo '<td>' . round(floatval($invoiceItem->NettoValue), 2) . '</td>';
//                echo '<td>' . round(floatval($invoiceItem->NettoPrice), 2) . '</td>';
//                echo '<td>' . $invoiceItem->Tax . '</td>';
//                echo '<td>' . $invoiceItem->CassaGroupID . '</td>';
//                echo '<td>' . $invoiceItem->BarCode . '</td>';
                echo '</tr>';
                $redniBroj++;
//                var_dump($invoiceItem);
            }
//            var_dump($xml);
            echo '</table>';
            echo '</html>';
        }
    }
} else {
    echo 'Fajl nije ispravan!';
}
