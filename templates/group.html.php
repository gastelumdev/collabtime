<!-- ===================================================================
| YOUR GROUPS SECTION
=================================================================== -->
<section id="group">
    <div class="container">
        <div class="card card-large">
            <div class="container">
                <h1><?=$group['name']?></h1>
                <div class="group-info">
                    <p>Last event: 8 days ago</p>
                    <p><?=$groupMemberCount?> Members</p>
                </div>
                <p class="description"><?=$group['description']?></p>
                <p>Group Administrator: <a href="index.php?user/profile?id=<?=$group['userId']?>"><?=$user['firstname']?> <?=$user['lastname']?></a></p>
                <?php if ($loggedIn): ?>
                    <?php if ($member): ?>
                        <button id="<?=$group['id']?>" value="Leave" class="btn btn-complementary btn-action">Leave</button>
                    <?php else: ?>
                        <button id="<?=$group['id']?>" value="Join" class="btn btn-complementary btn-action">Join</button>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
        <div id="events" class="card card-large">
            <div class="container">
                <h4>UPCOMING EVENTS</h4>
                <?php if ($member): ?>
                    <?php foreach($events as $event): ?>
                        <div class="card card-flat card-wide">
                            <div class="row">
                                <img src="images/hero/<?=$category?>.jpg" alt="">
                                <div class="card-details">
                                    <p class="card-heading"><?=strtoupper($event['dayOfWeek'])?>, <?=strtoupper($event['month'])?> <?=strtoupper($event['day'])?> @ <?=$event['hour']?>:<?=$event['minutes']?> <?=$event['meridiem']?></p>
                                    <p><a href="index.php?event?id=<?=$event['id']?>"><?=$event['name']?></a></p>
                                    <p><?=$group['name']?> - <?=$group['city']?> <?=$group['state']?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Join group to see upcoming events.</p>
                <?php endif; ?>
            </div>
        </div>
        <?php if ($groupMemberCount > 0): ?>
        <div id="members" class="card card-large">
            <div class="container">
                <h4>MEMBERS</h4>
                <div class="row">
                    <?php foreach ($groupMembers as $groupMember): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="card card-flat">
                            <div class="row">
                                <img src="images/users/<?=$groupMember['image']?>" height="50px" width="50px" alt="">
                                <a href="index.php?user/profile?id=<?=$groupMember['id']?>"><?=$groupMember['firstname']?> <?=$groupMember['lastname']?></a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <div id="members" class="card card-large">
            <div class="container">
                <h4>COMMENTS</h4>
                
                    <div class="form-row">
                        <div id="text-input" class="col">
                            <input id="text" type="text" class="form-control" name="text" placeholder="Comment...">
                            <input value="<?=$group['id']?>" type="hidden" name="groupid" id="groupid">
                        </div>
                        <button id="postComment" class="createBtn btn btn-primary">Save</button>
                    </div>
                <div id="comments">
                    <?php foreach ($groupComments as $groupComment): ?>
                        <div class="card card-flat card-wide">
                            <div class="row">
                                <img src="images/users/<?=$groupComment['user_image']?>" alt="">
                                <div class="card-details">
                                    <p class="card-heading"><?=$groupComment['user_name'] . ' | ' . $groupComment['month']?> <?=strtoupper($groupComment['day'])?> @ <?=$groupComment['hour']?>:<?=$groupComment['minutes']?> <?=$groupComment['meridiem']?></p>
                                    <p><?=$groupComment['text']?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="js/membership.js"></script>
<script src="js/groupComment.js"></script>