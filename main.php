<?php
    $suggest = array("suggest_from","suggest_to","title", "description", "contacts", "price","region","media");

    function barter_topics()
    {
        $topics = new mysqli(HOST, DB_USER, DB_PASS, "barter_main");

        if ($topics->connect_errno) {
            //TODO: Handling the connection error
            exit();
        }

        $query = "SELECT * FROM topics";
        $result = $topics->query($query);

        $row = $result->fetch_all(MYSQLI_ASSOC);
        foreach ($row as &$value)
            echo("<option value=".$value[id].">".$value[name]."</option>");
        unset($value);
        $topics->close();
    }

  function region_selection()
  {
      $region = new mysqli(HOST, DB_USER, DB_PASS,"barter_main");

      if ($region->connect_errno)
      {
          //TODO: Handling the connection error
          exit();
      }

      $query = "SELECT * FROM `regions` ORDER BY `name` ASC";
      $result = $region->query($query);
      $row = $result->fetch_all(MYSQL_ASSOC);
      foreach ($row as &$val)
          echo ("<option value=".$val[id].">".$val[name]."</option>");
      unset($val);
      $region->close();
  }