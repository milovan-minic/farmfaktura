<!DOCTYPE html>
<html>
    <head>
        <title>Faktura</title>
    </head>
    <body>
    <h3>Forma za unos Farmalogist .xml fakture</h3>
        <form name="file_upload" action="faktura.php" method="post" enctype="multipart/form-data">
            Izaberi fajl: <input type="file" name="file" size="500000"><br />
            <input type="submit" name="file_submit" value="Posalji">
        </form>
    </body>
</html>