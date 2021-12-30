<section id="body">
    <div class="container">
        <h1><?=$title?></h1>
        <input type="hidden" name="type" id="type" value="school">
        
        <!-- 5/23/21 OG NEW - Display the number of groups calculated in the controller -->
        <p>Total schools invited: <span id="count"><?=$totalSchools?></span></p>
        <!-- 5/23/21 OG NEW - if the user has author rights then display the create group button -->
        <?php if ($loggedIn && $role >= 4): ?>
            <table class="table table-sm table-striped table-hover">
                <thead>
                    <tr>
                        <th scope="col">Action</th>
                        <th scope="col">School Name</th>
                        <th scope="col">School Email</th>
                    </tr>
                </thead>
                <tbody id="inputs">
                    <tr>
                        <td scope="row"><button id="createSchool" class="createBtn btn btn-primary btn-sm">Invite</button></td>
                        <td scope="row"><input id="name" type="text" class="form-control"></td>
                        <td scope="row"><input id="email" type="email" class="form-control"></td>
                    </tr>
                </tbody>
            </table>
        <?php endif; ?>
        <!-- 5/23/21 OG NEW - If the total amount of groups is greater than 0, display the table -->
        <?php if ($totalSchools > 0): ?>
            <div class="table-responsive">
                <table class="table table-sm table-striped table-hover">
                <thead id="tableHead">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Action</th>
                        <th scope="col" class="editable"><div id="name">Name</div></th>
                        <th scope="col" class="editable"><div id="email">Email</div></th>
                        <th scope="col"><div id="status">Status</div></th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <!-- 5/23/21 OG NEW - For each group -->
                    <?php foreach($schools as $school): ?>
                        <!-- 5/23/21 OG NEW - If user has admin rights, display all groups else only show only those that they created -->
                        <tr>
                            <td scope="row"><?=$school['id']?></td>
                            <td scope="row"><button id="deleteBtn-<?=$school['id']?>" class="deleteBtn btn btn-danger btn-sm">Delete</button></td>
                            <td class="editable"><div contenteditable="true" class="edit" id="name-<?=$_SESSION['event']['id']?>"><?=$school['name']?></div></td>
                            <td class="editable"><div contenteditable="true" class="edit" id="email-<?=$_SESSION['event']['id']?>"><?=$school['email']?></div></td>
                            <td><div id="status">
                                    <?php
                                        if ($school['status'] == 1) {
                                            echo '<button type="button" class="btn btn-secondary btn-sm" disabled>Pending School Submittal</button>';
                                        } elseif ($school['status'] == 2) {
                                            echo '<button class="btn btn-primary btn-sm" href="">Click to Verify</button>';
                                        } else {
                                            echo '<button class="btn btn-success btn-sm" href="#" role="button"><i class="fa fa-check-circle" aria-hidden="true"></i> View Submittal</button>';
                                        }
                                    ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</section>