<?php
    // Start the output buffer.
    ob_start();

    include_once "../../php/libs/database.php";
    include_once "../../plugins/private_signup_plugin.php";

    // Start the session.
    if (!isset($_SESSION)) {
        session_start();
    }

    if(empty($_GET["hash"]) || empty($_GET["id"])) {
        header("Location: ../../index.php");
        exit;
    } else {
        // Database object.
        $db = new Db();

        // Quote and escape values.
        $hash =  $db -> quote($_GET["hash"]);
        $id =  $db -> quote($_GET["id"]);

        // Get the corresponding values.
        $torrent = $db -> select("SELECT * FROM `torrents` WHERE `hash`=".$hash." AND `userid`=".$id."");
        // Change this to the torrent 404 page.
        if(count($torrent) == 0) { 
            header("Location: ../../en/status/404.php"); exit;
        }
        $uploader = $db -> select("SELECT `username` FROM `users` WHERE `user_id`=".$id."");
    }
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

    <title>Torrent | OpenTorrent</title>

    <!-- Bootstrap Core CSS -->
    <link href="../../css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Bootstrap CSS -->
    <link href="../../css/1-col-portfolio.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../../css/custom.css" rel="stylesheet">

    <!-- Editor TinyMCE -->
    <script src="../../js/tinymce/tinymce.min.js"></script>

    <script>
        tinymce.init({
          selector: 'textarea',
          theme: 'modern',
          readonly : 1,
          toolbar: false,
          menubar: false,
          statusbar: false,
          plugins: "autoresize",
          content_css: [
            '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i'
          ]
         });
    </script>

    <style>
        .mce-panel {
            border: none !important;
        }
    </style>

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/png" href="../../css/favicon.png"/>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="../../index.php">OpenTorrent</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
				    <li>
                        <a href="#">News</a>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <?php
                        if(!isset($_SESSION["username"])) {
                            echo '<li><a href="../account/register.php"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>';
                            echo '<li><a href="../account/login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>';
                        } else {
                            echo '<li><a href="../upload/upload.php"><span class="glyphicon glyphicon-upload"></span> Upload</a></li>';
                            echo '
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">'.$_SESSION["username"].' <b class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="my-torrents.php"><span class="glyphicon glyphicon-book"></span> Torrents</a>
                                        </li>
                                        <li>
                                            <a href="#"><span class="glyphicon glyphicon-cog"></span> Preferences</a>
                                        </li>
                                        <li>
                                            <a href="../account/logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a>
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
                    <form action="../search/search.php">
                        <div class="input-group">
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
                    <hr>
                </div>
            </div>
            <!-- /.row -->

            <div class="row">
            <!-- Blog Post Content Column -->
            <div class="col-lg-8">
                <!-- Torrent name -->
                <h1><?php echo $torrent[0]["name"]; ?></h1>

                <!-- Uploader -->
                <p class="lead">
                    by <a href=""><?php echo $uploader[0]["username"]; ?></a>
                </p>

                <hr>

                <!-- Date/Time -->
                <p><span class="glyphicon glyphicon-time"></span> Posted on <?php echo $torrent[0]["uploaddate"]; ?></p>

                <hr>

                <?php
                    if($torrent[0]["imdb"] != null) {
                        echo '<!-- Preview Image -->';
                        echo '<img class="img-responsive" src="http://placehold.it/900x300" alt=""><hr>';
                    }
                ?>                

                <!-- Torrent description + files tree -->
                <textarea>
                    <?php echo $torrent[0]["description"]; ?>
                </textarea>

                <hr>      
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>Filename</th>
                        <th>Size</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        $i = 0;
                        foreach (unserialize($torrent[0]["files"]) as $f[] => $key) {
                            echo '<tr>';
                            echo '<td>'. $f[$i] . '</td><td>'. formatBytes($key) .'</td>';
                            echo '</tr>';
                            $i++;
                        }
                      ?>
                    </tbody>
                  </table>

            </div>

            <!-- Blog Sidebar Widgets Column -->
            <div class="col-md-4">
                <!-- Blog Search Well -->
                <div class="well">
                    <h4>Stats &amp; Download</h4>
                        Size: <?php echo $torrent[0]["size"]; ?> <br/>
                        Seeders: <?php echo $torrent[0]["seeders"]; ?> <br/>
                        Leechers: <?php echo $torrent[0]["leechers"]; ?> <br/>
                        <br />
                        <a href="http://localhost/k/index.php?download=<?php echo substr($torrent[0]["name"],0,strlen($torrent[0]["name"])); ?><button type="button" class="btn btn-primary"><span class="glyphicon glyphicon-download-alt"></span></button></a>
                        <a href="<?php echo $torrent[0]["magnet"]; ?>"><button type="button" class="btn btn-primary"><span class="glyphicon glyphicon-magnet"></span></button></a>
                        <a href="" id = "Upvote" onclick="myAjax()"><button type="button" class="btn btn-primary"><span class="glyphicon glyphicon-thumbs-up"></span></button></a>                        
                        <p style = "color : red ; margin-top : 10px; font-size : 16px;"> Upvotes : <?php echo $torrent[0]["votes"]; ?> </p>
                </div>
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
    <style>
        h1{
            color:red;
        }
        #Body{
            background-color:rgba(245, 245, 245, 1);
        }
    </style>
    <script>
        function myAjax() {

      $.ajax({
           type: "POST",
           url: 'ajax.php?hash=' + <?php echo $hash ?> + '&id=' + <?php echo $id ?>,
           data:{action:'call_this' , hash: <?php echo $hash ?> , id:<?php echo $id ?>},

      });
 }
    </script>
    <!-- jQuery -->
    <script src="../../js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../../js/bootstrap.min.js"></script>

    <?php
        function formatBytes($bytes, $precision = 2) { 
            $units = array('B', 'KB', 'MB', 'GB', 'TB'); 

            $bytes = max($bytes, 0); 
            $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
            $pow = min($pow, count($units) - 1); 
            
            $bytes /= (1 << (10 * $pow)); 

            return round($bytes, $precision) . ' ' . $units[$pow]; 
        }
    ?>

</body>
</html>