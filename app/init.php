<?
$servername = "172.30.140.23";
$port = "5432";
$username = "thibault.chassagne";
$password = "Sp@gue11i";
$dbname = "project2425_chassagne_thibault";

$connstring = "host=$host port=$port dbname=$dbname user=$user password=$password";

$conn = pg_connect($connstring);

if (!$conn) {
    die("Echec de la connexion: ".pg_last_error());
}else {
    echo "HOURA !";
}


$sql = "CREATE TABLE test (CREATE TABLE IF NOT EXISTS test (id INT);";

$rslt = pg_query($conn,$sql);

if (!$rslt) {
    echo "X Erreur !";
}else {
    echo "V Ca marche";
}

pg_close($conn);

?>