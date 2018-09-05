<?php
require_once('vendor/autoload.php');


class compare
{
    protected $json;
    protected $fromDB;

    public function loadInternalData($dbHostname, $dbName, $dbUsername, $dbPassword = null)
    {
        $pdo = new PDO(sprintf('mysql:host=%s;dbname=%s', $dbHostname, $dbName), $dbUsername, $dbPassword);
        $stm = $pdo->prepare('select id from `my_data`');
        $stm->execute();
        foreach ($stm->fetchAll() as $row) {
            $this->fromDB[] = $row['id'];
        }
    }

    public function loadExternalData($jsonDataFilePath)
    {
        foreach (json_decode(file_get_contents($jsonDataFilePath)) as $row) {
            $this->json[] = $row->id;
        }
    }

    public function check()
    {
        $compared = array_intersect($this->json, $this->fromDB);
        foreach ($compared as $id) {
            echo "$id\n";
        }
    }

}

$bench = new Ubench();
$bench->start();
$compare = new compare();
$compare->loadInternalData('localhost', 'ss', 'root');
$compare->loadExternalData('data.json');
$compare->check();
$bench->end();
echo $bench->getTime();