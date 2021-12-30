<div class="container">
<?php if (!empty($errors)): ?>
    <div class="errors">
        <p>Your account could not be created, please check the following:</p>
        <ul>
        <?php foreach ($errors as $error): ?>
            <li><?= $error ?></li>
        <?php endforeach; 	?>
        </ul>
    </div>
<?php endif; ?>
<?php if ($permissions > 2 || $userId == $user['id']):

?>
<form action="index.php?user/save" method="post">
	<input type="hidden" name="user[id]" value="<?=$user['id'] ?? ''?>">
    <div class="form-group row">
        <label for="firstname" class="col-sm-2 col-form-label">Name:</label>
        <div class="col-sm-10">
            <input type="text" id="firstname" name="user[firstname]" class="form-control form-control-sm" placeholder="Firstname" value="<?=$user['firstname'] ?? ''?>">
        </div>
    </div>
    <div class="form-group row">
        <label for="lastname" class="col-sm-2 col-form-label">Lastname:</label>
        <div class="col-sm-10">
            <input type="text" id="lastname" name="user[lastname]" class="form-control form-control-sm" placeholder="Lastname" value="<?=$user['lastname'] ?? ''?>">
        </div>
    </div>
    <div class="form-group row">
        <label for="email" class="col-sm-2 col-form-label">Email:</label>
        <div class="col-sm-10">
            <input type="email" id="email" name="user[email]" class="form-control form-control-sm" placeholder="Email" value="<?=$user['email'] ?? ''?>">
        </div>
    </div>
    <?php if ($showPasswordField): ?>
        <div class="form-group row">
            <label for="password" class="col-sm-2 col-form-label">Password:</label>
            <div class="col-sm-10">
                <input type="password" id="password" name="user[password]" class="form-control form-control-sm" placeholder="password" value="<?=$user['password'] ?? ''?>">
            </div>
        </div>
    <?php endif; ?>

    <!-- 5/22/2021 OG NEW 1L - Administrator has the option to update permission rights -->
    <?php if ($permissions > 2): ?>
    <div class="form-group row">
        <label for="permissions" class="col-sm-2 col-form-label">Permissions:</label>
        <div class="col-sm-10">
            <select id="permissions" name="user[permissions]" class="form-control form-control-sm">
                <!-- 5/22/2021 OG NEW 1L - Iterate through 1-3 which is the amount of permission codes -->
                <?php for ($i = 1; $i <= 3; $i++): ?>
                    <!-- 5/22/2021 OG NEW 1L - If the index is equal to the user's id being edited, display as selected -->
                    <!--                       else, display option tag with the index number -->
                    <?php if ($i == $user['permissions']): ?>
                        <option value="<?=$i?>" selected><?=$i?></option>
                    <?php else: ?>
                        <option value="<?=$i?>"><?=$i?></option>
                    <?php endif; ?>
                <?php endfor; ?>
            </select>
        </div>
    </div>
    <?php endif; ?>
    
    <div class="form-group row">
        <div class="col-sm-10">
            <input type="submit" name="submit" value="Save">
        </div>
    </div>
</form>
<?php else: ?>

<p>You are not authorized to view this page.</p>

<?php endif; ?>
</div>