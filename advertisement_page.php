<!DOCTYPE html>
<head>
    <meta charset="utf-8" />
</head>
<?php
if(!isset($_COOKIE['id']))
    echo("<html><script>window.location = 'index.php'</script></html>");
if(isset($_GET['id'])) {
    $advertisement_db = new mysqli("barter", "root", "", "barter_main");
    if ($advertisement_db->connect_errno) {
        exit();
    }
    $advertisement_req = $advertisement_db->query("SELECT * FROM `advertisements` WHERE `id`='$_GET[id]' LIMIT  0,1");
    $advertisement_info = $advertisement_req->fetch_all(MYSQLI_ASSOC);

    echo $advertisement_info[0][title];

    $suggest_id = $advertisement_info[0][suggest_from];
    $suggest_from_req = $advertisement_db->query("SELECT `name` FROM `topics` WHERE `id`='$suggest_id' LIMIT  0,1");
    $suggest_from = $suggest_from_req->fetch_all(MYSQLI_ASSOC);
    echo "<br>From: ".$suggest_from[0][name];

    $suggest_id = $advertisement_info[0][suggest_to];
    $region_id = $advertisement_info[0][region];
    $city_id = $advertisement_info[0][city];
    $bd_req = $advertisement_db->query("SELECT topics.name AS topic, cities.name AS city, regions.name AS region
      FROM topics, cities, regions WHERE topics.id ='$suggest_id' AND cities.id ='$city_id' AND regions.id = '$region_id' LIMIT 0,1");
    $bd = $bd_req->fetch_all(MYSQLI_ASSOC);
    echo "<br>To: ".$bd[0][topic];

    setlocale(LC_ALL, "Russian");
    echo "<br>Publish date: ".strftime("%d.%m.%Y %H:%M",$advertisement_info[0][publish_date]);

    echo "<br>Region: ".$bd[0][region];

    echo "<br>City: ".$bd[0][city];

    echo "<br>Name: ".$advertisement_info[0][name];

    echo "<br>Contacts: ".$advertisement_info[0][contacts];

    echo "<br>Description: ".$advertisement_info[0][description];
    $media_str = $advertisement_info[0][media];
    $media = preg_split("/[,]+/",$media_str);
    foreach($media as $value)
        echo "<img src='" . $value . "''/><br>";
}
?>