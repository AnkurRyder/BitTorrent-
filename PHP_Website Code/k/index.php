<?php
    // Start the output buffer.
    ob_start();

    // Include database accessor.
    include_once "php/libs/database.php";

    // Setting the default timezone. (change this setting)
    date_default_timezone_set('Europe/Brussels');

    // Start the session.
    if (!isset($_SESSION)) {
        session_start();
    }

    // Include plugin.
    include_once "plugins/private_signup_plugin.php";
    include_once "php/popular.php";

    // Fetch data.
    $db = new Db();

    $categories = $db -> select("SELECT * from categories");

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Standard Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="OpenTorrent: an easy to setup torrent website!">
    <meta name="author" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Browse | OpenTorrent</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Bootstrap CSS -->
    <link href="css/1-col-portfolio.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/custom.css" rel="stylesheet">

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/png" href="css/favicon.png"/>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<?php
    if (isset($_GET['download'])) {
        force_download($_GET['download']);
    }
    function force_download($filename) {
        $append='localhost/torrents/';
        $newname=substr(str_replace(' ','.',$filename),1);
        /*if (substr($filename,1,1)=='B')
            $newname='http://'.$append.$newname.'-[rarbg.to].torrent';
        else
            $newname='http://'.$append.$newname.'.torrent';*/
        $newname='http://'.$append.$newname.'.torrent';
        echo "Newname is ".$newname."</br>";
        $find=strpos($newname,"button");
        if ($find)
        {
            $newname=substr($newname,0,$find-2).'.torrent';
        }
        $filedata = @file_get_contents($newname);
        header("location:$newname");
        // SUCCESS
        if ($filedata)
        {
            // GET A NAME FOR THE FILE
            $basename = basename($newname);

            // THESE HEADERS ARE USED ON ALL BROWSERS
            header("Content-Type: application-x/force-download");
            header("Content-Disposition: attachment; filename=$basename");
            header("Content-length: " . (string)(strlen($filedata)));
            header("Expires: ".gmdate("D, d M Y H:i:s", mktime(date("H")+2, date("i"), date("s"), date("m"), date("d"), date("Y")))." GMT");
            header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");

            // THIS HEADER MUST BE OMITTED FOR IE 6+
            if (FALSE === strpos($_SERVER["HTTP_USER_AGENT"], 'MSIE '))
            {
                header("Cache-Control: no-cache, must-revalidate");
            }

            // THIS IS THE LAST HEADER
            header("Pragma: no-cache");

            // FLUSH THE HEADERS TO THE BROWSER
            flush();

            // CAPTURE THE FILE IN THE OUTPUT BUFFERS - WILL BE FLUSHED AT SCRIPT END
            ob_start();
            echo $filedata;
        }

        // FAILURE
        else
        {
            die("ERROR: UNABLE TO OPEN $newname");
        }
    }
?>

<body id = "Body">
    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php" id = "Brand">OpenTorrent</a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
				    <li>
                        <a href="https://torrentfreak.com/">News</a>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                <?php
                    if(!isset($_SESSION["username"])) {
                        echo '<li><a href="en/account/register.php"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>';
                        echo '<li><a href="en/account/login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>';
                    } else {
                        echo '<li><a href="en/upload/upload.php"><span class="glyphicon glyphicon-upload"></span> Upload</a></li>';
                        echo '
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">'.$_SESSION["username"].' <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="en/view/my-torrents.php"><span class="glyphicon glyphicon-book"></span> Torrents</a>
                                    </li>
                                    <li>
                                        <a href="#"><span class="glyphicon glyphicon-cog"></span> Preferences</a>
                                    </li>
                                    <li>
                                       <a href="http://localhost/k/en/account/logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a>
                                    </li>
                                </ul>
                            </li>';
                    }
                ?>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <!-- Page Content -->
    <div class="container">

        <!-- Search -->
        <div class="row">
            <div class="col-lg-12">
                <form action="../k/en/search/search.php">
                    <div class="input-group">
					<script>
					function hey(v)
					{
						alert(v);
					}
					</script>
                        <div class="input-group-btn search-panel">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                <span id="search_concept">Category</span> <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                              <li><a href="1">Movies</a></li>
                              <li><a href="2">Television</a></li>
                              <li><a href="3">Music</a></li>
                              <li><a href="4">Games</a></li>
                              <li><a href="5">Software</a></li>
                              <li><a href="6">Anime</a></li>
                              <li><a href="7">Books</a></li>
                              <li><a href="8">XXX</a></li>
                              <li><a href="9">Other</a></li>
                              <li class="divider"></li>
                              <li><a href="all">Anything</a></li>
                            </ul>
                        </div>
                        <input type="hidden" name="category" value="all" id="category">         
                        <input type="text" class="form-control" name="query" placeholder="Search term...">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search"></span></button>
                        </span>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.row -->

        <!-- Torrents -->
        <div class="row">
            <div class="col-lg-12">
                <!-- Categories starts-->
                <?php
                    foreach ($categories as $cat) {
                        $torrents = get_popular_per_cat($cat['id'],$db);
                        echo '<h1 id = "catogeries">'.$cat["categoryname"].'</h1>';
                        echo '<table class="table table-striped" id="'.$cat["id"].'">
                                <thead>
                                    <tr>
                                        <th width="50%"><small><span class="glyphicon glyphicon-sort"></span></small>Name</th>
                                        <th width="10%"><small><span class="glyphicon glyphicon-sort"></span></small>Size</th>
                                        <th width="10%"><small><span class="glyphicon glyphicon-sort"></span></small>Age</th>
                                        <th width="10%"><small><span class="glyphicon glyphicon-sort"></span></small>Seeds</th>
                                        <th width="10%"><small><span class="glyphicon glyphicon-sort"></span></small>Leech</th>
                                        <th width="10%">Download</th>
                                    </tr>
                                  </thead>
                                  <tbody>';
                        foreach ($torrents as $key[] => $row) {
                            $ymd = new DateTime($row["uploaddate"]); $today = new DateTime(); $diff=date_diff($ymd,$today);
                            if($row["categoryname"] == $cat["categoryname"]) {
                                echo '<tr>
                                    <td class="Name" data-label="Name"><a href="en/view/torrent.php?hash='.$row["hash"].'&id='.$row["userid"].'">'.$row["name"].'</a>
                                        <small>by <a href="/k/en/view/user-torrents.php?userid='.$row["userid"].'">'.$row["username"].'</a></small>
                                    </td>
                                    <td data-label="Size">'.$row["size"].'</td>
                                    <td data-label="Age">'. $diff->format("%ad").'</td>
                                    <td data-label="Seeds">'.$row["seeders"].'</td>
                                    <td data-label="Leech">'.$row["leechers"].'</td>
                                    <td data-label="Download">
                                        <a href="http://localhost/k/index.php?download='.$row["name"].'"><span class="glyphicon glyphicon-download-alt link" ></span>
                                        <a href="'.$row["magnet"].'"><span class="glyphicon glyphicon-magnet link"></span></a>
                                    </td>
                                </tr>';
                            }
                        }
						//<a href="http://itorrents.org/torrent/'.$row["hash"].'.torrent"><span class="glyphicon glyphicon-download-alt link"></span></a>
                        echo '</tbody></table>';
                    }
                ?>
            </div>
        </div>
            <!-- /.row -->
        <hr>

        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p style = "font-size:16px">Made by Manthan and Ankur</p>
                    <p>CS12 and CS24</p>
                </div>
            </div>
            <!-- /.row -->
        </footer>

    </div>
    <!-- /.container -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Torrent Card -->
    <script src="js/torrentcard.js"></script>
    <style>
        h1{
            color:red;
        }
        #Body{
            background-color:rgba(245, 245, 245, 1);
        }
    </style>
    <script>
    // var x = document.getElementById("catogeries");
    // x.style.color = "red";
    $(document).ready(function(e){
        $('.search-panel .dropdown-menu').find('a').click(function(e) {
            e.preventDefault();
            var param = $(this).attr("href").replace("#","");
            var concept = $(this).text();
            $('.search-panel span#search_concept').text(concept);
            $('.input-group #category').val(param);
        });
    });
    </script>

    <!-- Tablesort -->
    <script src="js/jquery.tablesorter.js"></script>
    
    <script>
    $(document).ready(function() { 
        <?php
        foreach ($categories as $cat) {
        echo '$("#'.$cat["id"].'").tablesorter();
        ';}?>});
    </script>
</body>
</html>