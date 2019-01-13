<?php


function confirmerMdp($nouveauMdpA, $nouveauMdpB){ //1 = Nouveau mdp vide, 2=Les deux mdp ne correspondent pas, 0=ok
    $returncode = 3;

    if($nouveauMdpA == "" || $nouveauMdpB == ""){
        $returncode = 1;
    } else if($nouveauMdpA == $nouveauMdpB){
        $returncode = 0;
    } else {
        $returncode = 2;
    }
    return $returncode;
}