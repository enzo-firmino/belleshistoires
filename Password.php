<?php
class Password {

    const SALT="thisisnotreallyapepperbutasaltyouKnow";


// salt le mdp
    public function salt($mdp) {
        $mdpsalt = $mdp.self::SALT;
        for ($i = 1; $i <= 1024; $i++) {
            $mdpsalt=hash('sha256', self::SALT.$mdpsalt.$i);
        }
        return $mdpsalt;
    }

// verifie que le mot de passe repond aux règles de sécurité
    public function verif_mdp($mdp){
        return(strlen($mdp)>=8 && preg_match("#[0-9]#", $mdp) && preg_match("#[A-Z]#", $mdp) && preg_match("#[a-z]#", $mdp));
    }

// genere un chaine de caractere aleatoire
    private function random_string($car) {
        $string = "";
        $chaine = "abcdefghijklmnpqrstuvwxyz";
        srand((double)microtime()*1000000);
        $upper1=rand(1, $car);
        $upper2=rand(1, $car);
        $upper3=rand(1, $car);

        for($i=0; $i<$car; $i++) {
            if($upper1==$i || $upper2==$i || $upper3==$i) {
                $string .= strtoupper($chaine[rand()%strlen($chaine)]);
            }
            else {
                $string .= $chaine[rand()%strlen($chaine)];
            }
        }
        return $string;
    }

//génére un mot de passe aléatoire
    function genere_mdp() {
        $chiffre[0]=rand(1, 9);
        $chiffre[1]=rand(11, 99);
        $chiffre[2]=rand(111, 999);
        $positionChiffre=rand(0,2); //permet d'égaliser les chances d'avoir un nombre à 1, 2 ou 3 chiffres.
        $longeurChiffre = strlen((string)$chiffre[$positionChiffre]);
        $text=$this->random_string(rand(8-$longeurChiffre, 8-$longeurChiffre));
        $position=rand(0,7);
        return str_replace("0",$chiffre[0],substr($text,0,$position).$chiffre[$positionChiffre].substr($text,$position));
    }


}