<?php
require_once('conexion.php');

if (!empty($_POST) )
{
    define("HOST", $hostname_conexion);
    define("USER", $username_conexion);
    define("PASSWORD", $password_conexion);
    define("DB", "my");
    define("MyTable", "vista_controladores");

    $connection = mysqli_connect(HOST, USER, PASSWORD, DB) OR DIE("Impossible to access to DB : " . mysqli_connect_error());

    function getData($sql)
    {
        global $connection ;//we use connection already opened
        $query = mysqli_query($connection, $sql) OR DIE ("Can't get Data from DB , check your SQL Query " );
        $data = array();
        foreach ($query as $row )
        {
            $data[] = $row ;
        }
        return $data;
    }

    $draw = $_POST["draw"];
    $orderByColumnIndex  = $_POST['order'][0]['column'];
    $orderBy = $_POST['columns'][$orderByColumnIndex]['data'];
    $orderType = $_POST['order'][0]['dir'];
    $start  = $_POST["start"];
    $length = $_POST['length'];
    
/*
    $draw = "1";
    $orderByColumnIndex  = "1";
    $orderBy = "nombre";
    $orderType = "ASC";
    $start  = "0";
    $length = "10";
*/
    $recordsTotal = count(getData("SELECT * FROM ".MyTable));

    if(!empty($_POST['search']['value']))
    {
        for($i=0 ; $i<count($_POST['columns']);$i++)
        {
            $column = $_POST['columns'][$i]['data'];
            $where[]="$column like '%".$_POST['search']['value']."%'";
        }
        $where = "WHERE ".implode(" OR " , $where);

        $sql = sprintf("SELECT * FROM %s %s", MyTable , $where);

        $recordsFiltered = count(getData($sql));

        $sql = sprintf("SELECT * FROM %s %s ORDER BY %s %s limit %d , %d ", MyTable , $where ,$orderBy, $orderType ,$start,$length  );
        $data = getData($sql);
    }
    else
    {
        $sql = sprintf("SELECT * FROM %s ORDER BY %s %s limit %d , %d ", MyTable ,$orderBy,$orderType ,$start , $length);
        $data = getData($sql);

        $recordsFiltered = $recordsTotal;
    }

    $response = array(
        "draw" => intval($draw),
        "recordsTotal" => $recordsTotal,
        "recordsFiltered" => $recordsFiltered,
        "data" => $data
    );

    echo json_encode($response);
} 
else 
{
    echo "NO POST Query from DataTable";
}

?>
