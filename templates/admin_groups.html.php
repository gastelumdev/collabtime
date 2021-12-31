
<input type="hidden" name="type" id="type" value="group">
<h1>Groups</h1>
<!-- 5/23/21 OG NEW - Display the number of groups calculated in the controller -->
<p>Total groups created: <?=$totalGroups?>
<!-- 5/23/21 OG NEW - if the user has author rights then display the create group button -->
<?php if ($loggedIn && $permissions >= 2): ?>
    <table class="table table-sm table-striped table-hover">
    <thead>
        <tr>
            <th scope="col">Action</th>
            <th scope="col">Name</th>
            <th scope="col">description</th>
            <th scope="col">Category</th>
            <th scope="col">Street</th>
            <th scope="col">City</th>
            <th scope="col">State</th>
            <th scope="col">Country</th>
            <th scope="col">Zip Code</th>
        </tr>
    </thead>
    <tbody id="inputs">
        <tr>
        <td scope="row"><button id="create" class="createBtn btn btn-primary btn-sm">Save</button></td>
        <td scope="row"><input id="name" type="text" class="form-control"></td>
        <td scope="row"><input id="description" type="text" class="form-control"></td>
        <td><select id="categoryId" class="form-control" id="exampleFormControlSelect1" name="group[categoryId]">
        <option value="">Choose a group:</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?=$category['id']?>"><?=$category['name']?></option>
            <?php endforeach; ?>
        </select></td>
        <td scope="row"><input id="street" type="text" class="form-control"></td>
        <td scope="row"><input id="city" type="text" class="form-control"></td>
        <td scope="row"><input id="state" type="text" class="form-control"></td>
        <td scope="row"><input id="country" type="text" class="form-control"></td>
        <td scope="row"><input id="zipcode" type="text" class="form-control"></td>
        </tr>
    </tbody>
    </table>
<?php endif; ?>
<!-- 5/23/21 OG NEW - If the total amount of groups is greater than 0, display the table -->
<?php if ($totalGroups >= 0): ?>
    <table class="table table-sm table-striped table-hover">
    <thead id="tableHead">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Action</th>
            <th scope="col" class="editable"><div id="name">Name</div></th>
            <th scope="col" class="editable"><div id="description">description</div></th>
            <th scope="col"><div id="creator">Created By</div></th>
            <th scope="col"><div id="category">Category</div></th>
            <th scope="col" class="editable"><div id="street">Street</div></th>
            <th scope="col" class="editable"><div id="city">City</div></th>
            <th scope="col" class="editable"><div id="state">State</div></th>
            <th scope="col" class="editable"><div id="country">Country</div></th>
            <th scope="col" class="editable"><div id="zipcode">Zip Code</div></th>
            <th scope="col"><div id="date">Date Created</div></th>
        </tr>
    </thead>
    <tbody id="tableBody">
        <!-- 5/23/21 OG NEW - For each group -->
        <?php foreach($groups as $group): ?>
            <!-- 5/23/21 OG NEW - If user has admin rights, display all groups else only show only those that they created -->
            <?php if (($permissions > 2 || $loggedIn && $userId == $group['userId'])): ?>
                <tr>
                <td scope="row"><?=$group['id']?></td>
                <td scope="row"><button id="deleteBtn-<?=$group['id']?>" class="deleteBtn btn btn-danger btn-sm">Delete</button></td>
                <td class="editable"><div contenteditable="true" class="edit" id="name-<?=$group['id']?>"><?=$group['name']?></div></td>
                <td class="editable"><div contenteditable="true" class="edit" id="description-<?=$group['id']?>"><?=$group['description']?></div></th>
                <td><div id="creator"><?=$group['creator']?></div></th>
                <td><div id="category"><?=$group['category']?></div></th>
                <td class="editable"><div contenteditable="true" class="edit" id="street-<?=$group['id']?>"><?=$group['street']?></div></th>
                <td class="editable"><div contenteditable="true" class="edit" id="city-<?=$group['id']?>"><?=$group['city']?></div></th>
                <td class="editable"><div contenteditable="true" class="edit" id="state-<?=$group['id']?>"><?=$group['state']?></div></th>
                <td class="editable"><div contenteditable="true" class="edit" id="country-<?=$group['id']?>"><?=$group['country']?></div></th>
                <td class="editable"><div contenteditable="true" class="edit" id="zipcode-<?=$group['id']?>"><?=$group['zipcode']?></div></th>
                <td><div id="date"><?=$group['date']?></div></td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
    </tbody>
    </table>
<?php endif; ?>
        
    
