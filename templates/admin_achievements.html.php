<section id="body">
    <div class="container">
        <input type="hidden" name="type" id="type" value="achievement">
        <h1>Achievements</h1>
        <!-- 5/23/21 OG NEW - Display the number of groups calculated in the controller -->
        <p>Total achievements created: <span id="count"><?=$totalAchievements?></span></p>
        <!-- 5/23/21 OG NEW - if the user has author rights then display the create group button -->
        <?php if ($loggedIn && $permissions >= 2): ?>
            <table class="table table-sm table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col">Action</th>
                    <th scope="col">Name</th>
                    <th scope="col">Description</th>
                    <th scope="col">Group</th>
                    <th scope="col">Icon</th>
                </tr>
            </thead>
            <tbody id="createTable">
                <form id="upload_form" enctype="multipart/form-data">
                    <tr>
                        <td scope="row"><button id="createAchievement" class="createBtn btn btn-primary btn-sm">Save</button></td>
                        <td scope="row"><input id="name" type="text" class="form-control" name="name"></td>
                        <td scope="row"><input id="description" type="text" class="form-control" name="description"></td>
                        <td><select id="groupId" class="form-control" id="exampleFormControlSelect1" name="groupId">
                        <option value="">Choose a group:</option>
                            <?php foreach ($groups as $group): ?>
                                <option value="<?=$group['id']?>"><?=$group['name']?></option>
                            <?php endforeach; ?>
                        </select></td>
                        <td><div class="custom-file">
                        <input type="file" class="custom-file-input" name="icon" id="customFile" accept=".jpg">
                        <label id="customFileLabel" class="custom-file-label" for="customFile">Icon Image</label>
                        </div></td>
                    </tr>
                </form>
            </tbody>
            </table>
        <?php endif; ?>
        <!-- 5/23/21 OG NEW - If the total amount of groups is greater than 0, display the table -->
        <?php if ($totalAchievements >= 0): ?>
            <table class="table table-sm table-striped table-hover">
            <thead id="tableHead">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Action</th>
                    <th scope="col" class="editable"><div id="name">Name</div></th>
                    <th scope="col" class="editable"><div id="description">description</div></th>
                    <th scope="col"><div id="group_name">Group</div></th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <!-- 5/23/21 OG NEW - For each group -->
                <?php foreach($achievements as $achievement): ?>
                    <!-- 5/23/21 OG NEW - If user has admin rights, display all groups else only show only those that they created -->
                    <?php if (($permissions > 2 || $loggedIn && $userId == $achievement['userId'])): ?>
                        <tr>
                        <td scope="row"><?=$achievement['id']?></td>
                        <td scope="row"><button id="deleteBtn-<?=$achievement['id']?>" class="deleteBtn btn btn-danger btn-sm">Delete</button></td>
                        <td class="editable"><div contenteditable="true" class="edit" id="name-<?=$achievement['id']?>"><?=$achievement['name']?></div></td>
                        <td class="editable"><div contenteditable="true" class="edit" id="description-<?=$achievement['id']?>"><?=$achievement['description']?></div></th>
                        <td><div id="group_name"><?=$achievement['group_name']?></div></th>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
            </table>
        <?php endif; ?>
    </div>
</section>