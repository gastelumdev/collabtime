<!-- ===================================================================
| YOUR GROUPS SECTION
=================================================================== -->
<section id="profile">
    <div class="container">
        
        <div class="card card-large">
            <div class="container">
                <img src="images/users/<?=$user['image']?>" height="100px" width="100px" alt="">
                <h1><?=$user['firstname']?> <?=$user['lastname']?></h1>
                <div class="card-details">
                    <p>Member since: <?=strtoupper($user['month'])?> <?=strtoupper($user['day'])?>, <?=strtoupper($user['year'])?></p>
                </div>
                <div class="card-description">
                    <h4>INTRODUCTION</h4>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                </div>
            </div>
        </div>
        <div id="groups" class="card card-large">
            <div class="container">
                <h4>GROUPS</h4>
                <div class="row">
                    <?php foreach ($groups as $group): ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="card card-flat">
                                <div class="row">
                                    <img src="images/hero/<?=$group['category']?>.jpg" alt="">
                                    <a href="index.php?group?id=<?=$group['id']?>"><?=$group['name']?></a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div id="groups" class="card card-large">
            <div class="container">
                <h4>ACHIEVEMENTS</h4>
                <div class="row">
                    <?php foreach ($userAchievements as $achievement): ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="card card-flat">
                                <div class="row">
                                    <img src="images/achievement_icons/<?=$achievement['achievement_icon']?>" alt="icon">
                                    <div class="card-details">
                                        <p><?=$achievement['achievement_name']?></p>
                                        <p><?=$achievement['group_name']?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>