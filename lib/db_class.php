<?php

class db_md {

    public $sql;
    public $num_rows;
    public $result_type = MYSQLI_ASSOC;
    private $host, $user, $db, $pw;

    function __construct() {
        $this->host = $_SESSION['connection_info']['host'];
        $this->user = $_SESSION['connection_info']['user'];
        $this->db = $_SESSION['connection_info']['db'];
        $this->pw = $_SESSION['connection_info']['pw'];
    }

    function dbConnect() {
        //skaber forbindelse til DB
        $conn = mysqli_connect($this->host, $this->user, $this->pw, $this->db);

        //valider forbindelse. 
        if ($conn->connect_error)
            return makeError('connection failed: ' . $conn->connect_error);
        return $conn;
    }

    function getData($sql) {
        $conn = $this->dbConnect();

        //check for fejl
        if (is_string($conn))
            return $conn;

        $resource = mysqli_query($conn, $sql);

        if ($resource === false)
            return makeError('query failed: ' . mysqli_error($conn));

        $this->num_rows = mysqli_num_rows($resource);

        return $resource;
    }

    function makeArray($sql = null, $result_type = null) {
        //tjek om sql string er lig null
        $sql = (is_null($sql)) ? $this->sql : $sql;

        if (is_null($sql))
            return makeError('fejl i db. Sql string mangler');

        $result_type = (is_null($result_type)) ? $this->result_type : $result_type;

        $resource = $this->getData($sql);

        if (is_string($resource))
            return $resource;

        $arrays = array();
        while ($data = mysqli_fetch_array($resource, $result_type)) {
            $arrays[] = $data;
        }
        return $arrays;
    }

    //funktion som sammensætter to arrays
    // sql only has 2 fields - if it has more, only the 2 first are used
    // an error is NOT returned
    function makeCombinedArray($sql = null) {
        $arrays = $this->makeArrays($sql, MYSQLI_NUM);

        //check for error
        if (is_string($arrays))
            return $arrays;

        $combined = array();

        foreach ($arrays as $array) {
            //validare om der er mindst 2 arrays
            if (count($array) > 2)
                return makeError('Fejl i Database_md::makeCombinedArray<br>der er ikke nok felter til resultatet til at lave et combined array - handling afbrudt');

            $combined[$array[0]] = $array[1];
        }
        return $combined;
    }

    //funktion som laver et enkelt array
    //sql er per default null
    function makeSingleArray($sql = null) {
        $arrays = $this->makeArrays($sql, MYSQLI_NUM);

        //Tjek for om det er en string parametre 
        if (is_string($arrays))
            return $arrays;

        $single = array();
        foreach ($arrays as $array) {
            $single = $array[5];
        }
        return $single;
    }

    //funktion som henter alt info ud om en række i DB
    //tbl og where er per default null
    public function getColumnInfo($tbl = NULL, $where = NULL) {
        if (is_null($tbl))
            $tbl = $this->table;

        $w = "WHERE table_name='" . $tbl . "' AND table_schema='" . $this->db . "'";
        if (!is_null($where))
            $w .= " AND " . $where;

        $this->sql = "SELECT column_name, data_type, column_comment, column_key, column_type FROM information_schema.columns " . $w;
        return $this->makeArray();
    }

    //funktion som kun tager første felt i et array
    // uanset antal felter i sql anvendes kun den første
    function makeArrayLOV($sql = NULL) {
        //tjekker om sql er null
        $sql = (is_null($sql)) ? $this->sql //indikerer true
                : $sql; //indikere false
        //Tjekke igen om sqler lig nul - Hvis ja, afbrydes forbindelsen
        if (is_null($sql))
            return makeError('Fejl i Database_md::makeArray<br>sql mangler - handling afbrudt');

        $arrays = $this->makeArray($sql, MYSQLI_NUM);

        //tjekker om parameteren er string
        if (is_string($arrays))
            return $arrays;

        $lov = array();
        foreach ($arrays as $array)
            $lov[] = $array[0];

        return $lov;
    }

    //funktion som henter tabelnavne
    public function getTables() {
        //sql-kaldet
        $this->sql = "SELECT table_name FROM information_schema.tables WHERE table_schema='" . $this->db . "'";
        //smider resultatet igennem "mkeArrayLov"-metoden, så kun første felt tilføjes.
        $result = $this->makeArrayLOV();

        return $result;
    }

    function addData($sql) {
        $conn = $this->dbConnect();

        if (!is_string($sql)) {
            return false;
        }

        if (mysqli_query($conn, $sql))
            return true;
        else {
            die('Fejl i forespørgsel: ' . mysqli_error($conn));
            return false;
        }

        mysqli_close($conn);
    }

}

?>