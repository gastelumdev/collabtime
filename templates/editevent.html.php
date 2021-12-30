<section id="body">
    <div class="container">
        <h1>Events</h1>
        <?php if (empty($group['id']) || $userId == $group['userId'] || $permissions == 3): ?>

            <form action="index.php?event/create" method="post">
                <div class="form-group">
                    <label for="name">Event Name</label>
                    <input type="text" class="form-control" id="name" name="event[name]" placeholder="Enter event name">
                </div>
                <div class="form-group">
                    <label for="desctiption">Description</label>
                    <textarea class="form-control" id="description" name="event[description]" placeholder="Description"></textarea>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlSelect1">Group</label>
                    <select class="form-control" id="exampleFormControlSelect1" name="event[groupId]">
                        <option>Choose a group:</option>
                        <?php foreach ($groups as $group): ?>
                            <option value="<?=$group['id']?>"><?=$group['name']?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="date">Event Date:</label><br>
                    <input type="date" id="date" name="event[date]">
                </div>
                <div class="form-group">
                    <label for="time">Event Time:</label><br>
                    <input type="time" id="time" name="event[time]">
                </div>
                <button type="submit" name="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
        <?php else: ?>

        <p>You may only edit groups that you posted.</p>

        <?php endif; ?>
    </div>
</section>