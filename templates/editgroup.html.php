<section id="body">
    <div class="container">
        <h1>Groups</h1>
        <?php if (empty($group['id']) || $userId == $group['userId'] || $permissions == 3): ?>

            <form action="index.php?group/create" method="post">
                <div class="form-group">
                    <label for="name">Group Name</label>
                    <input type="text" class="form-control" id="name" name="group[name]" placeholder="Enter group name">
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="group[description]" placeholder="Description"></textarea>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlSelect1">Category</label>
                    <select class="form-control" id="exampleFormControlSelect1" name="group[categoryId]">
                        <option>Choose a category:</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?=$category['id']?>"><?=$category['name']?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="street">Street</label>
                    <input type="text" class="form-control" id="street" name="group[street]" placeholder="Enter your HQ meeting location">
                </div>
                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" class="form-control" id="city" name="group[city]" placeholder="Enter the group's city">
                </div>
                <div class="form-group">
                    <label for="state">State</label>
                    <input type="text" class="form-control" id="state" name="group[state]" placeholder="Enter the group's state">
                </div>
                <div class="form-group">
                    <label for="country">Country</label>
                    <input type="text" class="form-control" id="country" name="group[country]" placeholder="Enter the group's country">
                </div>
                <div class="form-group">
                    <label for="zipcode">Zip Code</label>
                    <input type="text" class="form-control" id="zipcode" name="group[zipcode]" placeholder="Enter the group's zip code">
                </div>
                <button type="submit" name="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
        <?php else: ?>

        <p>You may only edit groups that you posted.</p>

        <?php endif; ?>
    </div>
</section>