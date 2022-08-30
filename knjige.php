<?php
include "broker.php";

$broker=Broker::getBroker();

if(isset($_GET["metoda"])){
    if($_GET["metoda"]=="vrati sve"){
        $broker->vratiSveKnjige();
        posalji($broker);
    }
    if($_GET["metoda"]=="vrati iz biblioteke"){
        $broker->vratiSveKnjigeIzBiblioteke($_GET["biblioteka"]);
        posalji($broker);
    }
    if($_GET["metoda"]=="vrati kategorije"){
        $broker->vratiSveKategorije();
        posalji($broker);
    }
}
if(isset($_POST["metoda"])){
    if($_POST["metoda"]=="dodaj"){
        $naziv=$_POST["naziv"];
        $kategorija=$_POST["kategorija"];
        $brojStrana=$_POST["broj_strana"];
        if(!validirajKnjigu($naziv,$kategorija,$brojStrana)){
            echo "Knjiga nije validna";
        }else{
            $broker->dodajKnjigu($naziv,$kategorija,$brojStrana);
            if(!$broker->getRezultat()){
                echo "greska u bazi";
            }else{
                echo "uspesno dodata knjiga";
            }
        }
    }
    if($_POST["metoda"]=="izmeni"){
        $id=$_POST["id"];
        $naziv=$_POST["naziv"];
        $kategorija=$_POST["kategorija"];
        $brojStrana=$_POST["broj_strana"];
        if(!validirajKnjigu($naziv,$kategorija,$brojStrana)){
            echo "Knjiga nije validna";
        }else{
            $broker->izmeniKnjigu($id,$naziv,$kategorija,$brojStrana);
            if(!$broker->getRezultat()){
                echo "greska u bazi";
            }else{
                echo "uspesno izmenjena knjiga";
            }
        }
    }
    if($_POST["metoda"]=="obrisi"){
        $broker->obrisiKnjigu($_POST["id"]);
        if(!$broker->getRezultat()){
            echo "greska u bazi";
        }else{
            echo "uspesno obrisana knjiga";
        }
    }
}





function posalji($broker){
    $rezultat=$broker->getRezultat();
    $response=array();
    if(!$rezultat){
        $response["status"]="greska u bazi";
        
    }else{
        $response["status"]="ok";
        $response["data"]=array();
        while($red=$rezultat->fetch_object()){
            $response["data"][]=$red;
        }
    }
    echo json_encode($response);
}

function validirajKnjigu($naziv,$kategorija,$brojStrana){
    $naziv=trim($naziv);
    $kategorija=trim($kategorija);
    $brojStrana=trim($brojStrana);
    return strlen($naziv)>3 && strlen($naziv)<40 && intval($kategorija) && intval($brojStrana);
}

?>