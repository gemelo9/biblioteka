<?php


class Broker{

    private $rezultat;
    private $mysqli;
    private static $broker;
    public function getRezultat(){
        return $this->rezultat;
    }
    public function getMysqli(){
        return $this->mysqli;
    }
    private function __construct(){
        $this->mysqli = new mysqli("localhost", "root", "", "baza_biblioteka");
        $this->mysqli->set_charset("utf8");
    }

    public static function getBroker(){
        if(!isset($broker)){
            $broker=new Broker();
        }
        return $broker;
    }
    private function izvrsiUpit($upit){
        $this->rezultat=$this->mysqli->query($upit);
    }
    public function vratiSveKnjige(){
        $this->izvrsiUpit("select k.*, kat.naziv as 'kategorija', kat.id as 'kat_id' from knjiga k inner join kategorija_knjige kat on (kat.id=k.kategorija)");
    }

    public function vratiSveKnjigeIzBiblioteke($biblioteka){
        $this->izvrsiUpit("select k.*, kat.naziv as 'kategorija', kat.id as 'kat_id', bk.broj_primeraka as 'brojPrimeraka' from knjiga k inner join kategorija_knjige kat on (kat.id=k.kategorija) inner join biblioteka_knjiga bk on (bk.knjiga=k.id) where bk.biblioteka=".$biblioteka);
    }
    public function vratiSveKategorije(){
        $this->izvrsiUpit("select * from kategorija_knjige");
    }
    public function dodajKnjigu($naziv,$kat,$brojS){
        $this->izvrsiUpit("insert into knjiga (naziv,kategorija,broj_strana) values ('".$naziv."',".$kat.", ".$brojS.")");
    }
    public function obrisiKnjigu($id){
        $this->izvrsiUpit("delete from knjiga where id=".$id);
    }
    public function izmeniKnjigu($id,$naziv,$kat,$brojS){
        $this->izvrsiUpit("update knjiga set naziv='".$naziv."', kategorija=".$kat.", broj_strana=".$brojS." where id=".$id);
    }
    public function vratiBiblioteke(){
        $this->izvrsiUpit("select * from biblioteka");
    }
    public function dodajBiblioteku($naziv,$adresa){
        $this->izvrsiUpit("insert into biblioteka(naziv,adresa) values ('".$naziv."','".$adresa."')");
    }
    public function obrisiBiblioteku($id){
        $this->izvrsiUpit("delete from biblioteka where id=".$id);
    }
    public function dodajVezu($b,$k,$bp){
        $this->izvrsiUpit("insert into biblioteka_knjiga values (".$b.",".$k.",".$bp.")");
    }
    public function obrisiVezu($b,$k){
        $this->izvrsiUpit("delete from biblioteka_knjiga where biblioteka=".$b." and knjiga=".$k);
    }
    public function izmeniBiblioteku($id,$n,$a){
        $this->izvrsiUpit("update biblioteka set naziv='".$n."', adresa='".$a."' where id=".$id);
    }
}

?>