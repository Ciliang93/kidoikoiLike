<?php
/**
 * Created by IntelliJ IDEA.
 * User: Junior
 * Date: 01/08/2018
 * Time: 17:37
 */

abstract class PO
{
    function fillObject(array $data){
        foreach ($data as $key => $value){
            $method = 'set'.ucfirst($key);
            if(method_exists($this,$method)){
                $this->$method($value);
            }
        }
    }

    /**
     * @return string
     */
    function toString(){
        $toString = '
               <table class="table table-dark">
                <tr>
        ';

        $class_name = get_class($this);
        $class_methods = get_class_methods(new $class_name(null));
        foreach ($class_methods as $method){
            if(strpos($method, "get") !== false){ //Si c'est un getter
                $titreColonne = $method;
                str_replace('get', '', $titreColonne);
                $toString = $toString .'<th scope="col">'. $titreColonne .'</th>';
            }
        }

        $toString = $toString . '</tr><tr>';

        foreach ($class_methods as $method){
            if(strpos($method, "get") !== false) { //Si c'est un getter
                if(method_exists($this, $method)){

                    $toString = $toString . '<td scope="row">'. $this->$method() .'</td>';
                }
            }
        }

        $toString = $toString . '</tr></table>';

        return $toString;

    }
}