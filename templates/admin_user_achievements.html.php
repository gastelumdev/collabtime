<section id="body">
    <div class="container">
        <input type="hidden" name="type" id="type" value="user_achievement">
        <h1>User Achievements</h1>
        <!-- 5/23/21 OG NEW - Display the number of groups calculated in the controller -->
        <p>Total achievements created: <?=$totalUserAchievements?>
        <!-- 5/23/21 OG NEW - if the user has author rights then display the create group button -->
        <?php if ($loggedIn && $permissions >= 2): ?>
            <table class="table table-sm table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col">Action</th>
                    <th scope="col">Achievement</th>
                    <th scope="col">Group</th>
                    <th scope="col">Member</th>
                </tr>
            </thead>
            <tbody id="createTable">
                <!-- <form id="upload_form" enctype="multipart/form-data"> -->
                    <tr>
                        <td scope="row"><button id="create" class="createBtn btn btn-primary btn-sm">Save</button></td>
                        <td>
                            <select id="achievementid" class="form-control" id="exampleFormControlSelect1" name="achievementid">
                                <option value="">Choose an achievement:</option>
                                <?php foreach ($achievements as $achievement): ?>
                                    <option value="<?=$achievement['id']?>"><?=$achievement['name']?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td>
                            <select id="groupid" class="form-control" id="exampleFormControlSelect1" name="groupid">
                                <option value="">Choose a group:</option>
                                <?php foreach ($groups as $group): ?>
                                    <option value="<?=$group['id']?>"><?=$group['name']?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td>
                            <select id="userid" class="form-control" id="exampleFormControlSelect1" name="userid">
                                <option value="">Choose a group:</option>
                                <?php foreach ($users as $user): ?>
                                    <option value="<?=$user['id']?>"><?=$user['lastname'] . ', ' . $user['firstname']?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                <!-- </form> -->
            </tbody>
            </table>
        <?php endif; ?>
        <!-- 5/23/21 OG NEW - If the total amount of groups is greater than 0, display the table -->
        <?php if ($totalUserAchievements >= 0): ?>
            <table class="table table-sm table-striped table-hover">
            <thead id="tableHead">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Action</th>
                    <th scope="col"><div id="user_name">Name</div></th>
                    <th scope="col"><div id="achievement_name">Achievement</div></th>
                    <th scope="col"><div id="group_name">Group</div></th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <!-- 5/23/21 OG NEW - For each group -->
                <?php foreach($userAchievements as $userAchievement): ?>
                    <!-- 5/23/21 OG NEW - If user has admin rights, display all groups else only show only those that they created -->
                    <?php if ($permissions >= 2): ?>
                        <tr>
                            <td scope="row"><?=$userAchievement['id']?></td>
                            <td scope="row"><button id="deleteBtn-<?=$userAchievement['id']?>" class="deleteBtn btn btn-danger btn-sm">Delete</button></td>
                            <td><div id="user_name"><?=$userAchievement['user_name']?></div></td>
                            <td><div id="achievement_name"><?=$userAchievement['achievement_name']?></div></td>
                            <td><div id="group_name"><?=$userAchievement['group_name']?></div></td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
            </table>
        <?php endif; ?>
    </div>
</section>