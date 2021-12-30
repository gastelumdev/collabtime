<section id="body">
    <div class="container">
        <form action="index.php?admin/event" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Event Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Event name">
            </div>
            <div class="form-group">
                <label for="exampleFormControlTextarea1">Event Description</label>
                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="description"></textarea>
            </div>
            <div class="form-group">
                <input type="date" id="date" name="date" class="form-control">
            </div>
            <div class="form-group">
                <input type="time" id="time" name="time" class="form-control">
            </div>
            <div class="form-group">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" name="image" id="customFile" accept=".jpg, .png, .gif">
                    <label id="customFileLabel" class="custom-file-label" for="customFile">Profile Image</label>
                    <span class="alert"><?=$errors['userImage'] ?? ''?></span>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Add Event</button>
        </form>
    </div>
</section>