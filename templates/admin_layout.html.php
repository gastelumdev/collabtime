<!DOCTYPE html>
<html lang="en">
<head>
    <title>Collabtime - Admin/<?=$title?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A description">
    <meta name="keywords" content="">
    <link rel="shortcut icon" href="/images/collabtime2.png">
    <link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
    <link rel="stylesheet" href="css/styles.css" media="screen">
    <link rel="stylesheet" href="css/admin.css" media="screen">
    <link rel="stylesheet" href="css/stylesprint.css" media="print">
</head>
<body>
    <!-- The side nav should not show if it is a school coming from the invitation -->
    <div id="side-nav" class="hide">
        <div class="header">
            <a href="index.php"><img src="images/collabtime5.png" alt="Site logo." width="75px" height="80px"></a>
        </div>
        <div class="user">
            <img src="images/users/<?=$user['image']?>" alt="">
            <h2><?=$user['firstname'] . ' ' . $user['lastname']?></h2>
            <p class="dim"><?=$user['email']?></p>
        </div>
        <div class="dashboard">
            <h2>Manage</h2>
            <ul>
                <?php if ($user['role'] > 0): ?>
                    <li><a href="index.php?events" class="<?php if ($title == "Events") echo "active" ?>"><i class="fa fa-calendar-o" aria-hidden="true"></i>
                        Events</a></li>
                <?php endif; ?>

                <?php if ($user['role'] > 4): ?>
                    <li><a href="index.php?events/users" class="<?php if ($title == "Users") echo "active" ?>"><i class="fa fa-user-plus" aria-hidden="true"></i>
                        Users</a></li>
                <?php endif; ?>
                <li><a href="index.php?logout"><i class="fa fa-sign-out" aria-hidden="true"></i>
                    Logout</a></li>
            </ul>
        </div>
        <?php if(isset($_SESSION['event']) && $user['role'] > 2): ?>
        <div class="dashboard">
            <h2><?=$_SESSION['event']['name']?></h2>
            <ul>
                <?php if ($user['role'] > 3): ?>
                    <li><a href="index.php?events/schools" class="<?php if ($title == "Schools") echo "active" ?>"><i class="fa fa-calendar-o" aria-hidden="true"></i>
                        Schools</a></li>
                <?php endif; ?>

                <?php if ($user['role'] > 2): ?>
                    <li><a href="index.php?admin/staff" class="<?php if ($title == "Leads") echo "active" ?>"><i class="fa fa-calendar-o" aria-hidden="true"></i>
                        Leads</a></li>
                <?php endif; ?>
            </ul>
        </div>
        <?php endif; ?>
    </div>
    <div id="top-nav" class="center-links">
        <div class="container">
            <a href="javascript:void(0);" class="icon" onclick="navButton()">
                <i class="fa fa-bars"></i>
            </a>
            <h1 id="header-title" class="font-white"><?=$title?></h1>
        </div>
    </div>
    <section id="body">
        <div class="container">
            <?php if (count($breadcrumbs) > 1): ?>
                <div class="breadcrumb flat">
                    <?php foreach($breadcrumbs as $breadcrumb): ?>
                        <?php if (count($breadcrumbs) == $breadcrumb['depth']): ?>
                            <a href="index.php?<?=$breadcrumb['path']?>" class="active"><?=$breadcrumb['title']?></a>
                        <?php else: ?>
                            <a href="index.php?<?=$breadcrumb['path']?>"><?=$breadcrumb['title']?></a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?=$output?>

        </div>
    </section>

    <!-- =======================================================================
    | JAVASCRIPT
    ======================================================================= -->
    <script src="https://kit.fontawesome.com/f4a104139c.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/script.js"></script>
    <script src="js/admin.js"></script>
    <script src="js/form_submission.js"></script>
    <script src="js/fileInput.js"></script>
    <!-- <script src="js/map.js"></script> -->
</body>
</html>