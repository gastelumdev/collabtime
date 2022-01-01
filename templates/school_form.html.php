<section id="body">
    <div class="container">
    <form action="index.php?school/saveForm" method="post">
        <input type="hidden" name="id" value="<?=$school['id'] ?? ''?>">
        <div class="form-group">
            <label for="city">School City</label>
            <input type="text" class="form-control" id="city" name="city" aria-describedby="emailHelp" placeholder="Enter city" value="<?=$school['city'] ?? ''?>">
        </div>
        <div class="form-group">
            <label for="zipcode">Zipcode</label>
            <input type="text" class="form-control" id="zipcode" name="zipcode" placeholder="Enter zipcode" value="<?=$school['zipcode'] ?? ''?>">
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
    </div>
</section>