<?php

// *************************************************************************
// *                                                                       *
// * DEPRIXA PRO -  Integrated Web Shipping System                         *
// * Copyright (c) JAOMWEB. All Rights Reserved                            *
// *                                                                       *
// *************************************************************************
// *                                                                       *
// * Email: support@jaom.info                                              *
// * Website: http://www.jaom.info                                         *
// *                                                                       *
// *************************************************************************
// *                                                                       *
// * This software is furnished under a license and may be used and copied *
// * only  in  accordance  with  the  terms  of such  license and with the *
// * inclusion of the above copyright notice.                              *
// * If you Purchased from Codecanyon, Please read the full License from   *
// * here- http://codecanyon.net/licenses/standard                         *
// *                                                                       *
// *************************************************************************


class Conexion
{

    private $db_host = CDP_DB_HOST;
    private $db_name = CDP_DB_NAME;
    private $db_user = CDP_DB_USER;
    private $db_pass = CDP_DB_PASS;
    public $dbh;
    private $stmt;
    private $error;

    public function __construct()
    {
        $dsn = 'mysql:host=' . $this->db_host . ';dbname=' . $this->db_name;
        $options = array(PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT);

        //Instanciar PDO
        try {
            $this->dbh = new PDO($dsn, $this->db_user, $this->db_pass, $options);
            $this->dbh->exec('set names UTF8');
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            echo $this->error;
        }
    }
    //Se prepara la consulta
    public function cdp_query($sql)
    {
        $this->stmt = $this->dbh->prepare($sql);
    }
    public function bind($param, $value, $type = null)
    {


        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
                    break;
            }
        }
        //Vincula un valor a un parámetro
        $this->stmt->bindValue($param, $value, $type);
    }
    //Ejecuta la consulta
    public function cdp_execute()
    {
        return $this->stmt->execute();
    }

    // public function cdp_execute() {
    //     try {
    //         // Attempt to execute the statement
    //         if ($this->stmt->execute()) {
    //             return true; // If successful, return true
    //         } else {
    //             // If there's an error, fetch error information
    //             $errorInfo = $this->stmt->errorInfo();
    //             // Display the error information
    //             echo "SQLSTATE error code: " . $errorInfo[0] . "<br>";
    //             echo "Driver-specific error code: " . $errorInfo[1] . "<br>";
    //             echo "Driver-specific error message: " . $errorInfo[2] . "<br>";
    //             return false; // Indicate failure
    //         }
    //     } catch (PDOException $e) {
    //         // Handle exceptions if any
    //         echo "Execution failed: " . $e->getMessage() . "<br>";
    //         return false; // Indicate failure
    //     }
    // }


    //Obtener los datos de la consulta
    public function cdp_registros()
    {
        try {
            $this->cdp_execute();
            return $this->stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            echo 'PDOException: ' . $e->getMessage();
        } catch (Exception $e) {
            echo 'Exception: ' . $e->getMessage();
        }
    }

    //Obtener dato de la consulta
    public function cdp_registro()
    {
        $this->cdp_execute();
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    //Obtener dato de la consulta
    public function cdp_fetch_assoc()
    {
        $this->cdp_execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    //Numero de filas obtenidas
    public function cdp_rowCount()
    {
        return $this->stmt->rowCount();
    }



    public function cdp_fetch_all()
    {
        $this->cdp_execute();
        return $this->stmt->fetchAll();
    }

    public function getAllTags()
    {
        $allTags = [
            "Fridge Item (2-4 C)",
            "Hand Deliver",
            "Narcotics",
            "Pickup Rx Paper",
            "Pick up Old Medication"
        ];
        return $allTags;
    }
}
