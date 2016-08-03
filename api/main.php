<?php
require_once "db_connection.php";
    $suggest = array("suggest_from","suggest_to","title", "description", "contacts", "price","region","media");
    function barter_topics()
    {
        global $db;
        $query = "SELECT * FROM topics";
        $result = $db->query($query);

        $row = $result->fetch_all(MYSQLI_ASSOC);
        foreach ($row as &$value)
            echo("<option value=".$value[id].">".$value[name]."</option>");
        unset($value);
    }

  function region_selection()
  {
      global $db;
      $query = "SELECT * FROM `regions` ORDER BY `name` ASC";
      $result = $db->query($query);
      $row = $result->fetch_all(MYSQLI_ASSOC);
      foreach ($row as &$val)
          echo ("<option value=".$val[id].">".$val[name]."</option>");
      unset($val);
  }