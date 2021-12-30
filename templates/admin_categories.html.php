<section id="body">
    <div class="container">
        <input type="hidden" name="type" id="type" value="category">
        <h1>Categories</h1>
        <!-- 5/23/21 OG NEW - Display the number of groups calculated in the controller -->
        <p>Total categories created: <?=$totalCategories?>
        <!-- 5/23/21 OG NEW - if the user has author rights then display the create group button -->
        <?php if ($loggedIn && $permissions >= 2): ?>
            <table class="table table-sm table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col">Action</th>
                    <th scope="col">Name</th>
                </tr>
            </thead>
            <tbody id="createTable">
                <tr>
                <td scope="row"><button id="create" class="createBtn btn btn-primary btn-sm">Save</button></td>
                <td scope="row"><input id="name" type="text" class="form-control"></td>
                </tr>
            </tbody>
        <?php endif; ?>
        <!-- 5/23/21 OG NEW - If the total amount of groups is greater than 0, display the table -->
        <?php if ($totalCategories > 0): ?>
            <table class="table table-sm table-striped table-hover">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Action</th>
                        <th scope="col" class="editable"><div id="name">Name</div></th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <!-- 5/23/21 OG NEW - For each group -->
                    <?php foreach($categories as $category): ?>
                        <!-- 5/23/21 OG NEW - If user has admin rights, display all groups else only show only those that they created -->
                        <?php if (($permissions > 2 || $loggedIn && $userId == $event['userId'])): ?>
                            <tr>
                            <td scope="row"><?=$category['id']?></td>
                            <td scope="row"><button id="deleteBtn-<?=$category['id']?>" class="deleteBtn btn btn-danger btn-sm">Delete</button></td>
                            <td class="editable"><div contenteditable="true" class="edit" id="name-<?=$category['id']?>"><?=$category['name']?></div></td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</section>