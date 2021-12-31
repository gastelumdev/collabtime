
<div class="row">
    <?php foreach ($events as $event): ?>
        <div class="col-lg-3 col-md-6">
            <a href="index.php?admin/event?id=<?=$event['id']?>">
                <div class="card card-small">
                    <div class="card-image"><img src="images/hero/<?= $event['image'] ?>" alt="">
                        <div class="card-details">
                            <div class="card-title">
                                <h4 class="card-title"><p><?= $event['name'] ?></p></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    <?php endforeach; ?>
    <?php if($role >= 4): ?>
    <div class="col-lg-3 col-md-6">
        <a href="index.php?events/create">
            <div class="card card-small card-add">
                <div class="card-image"><img src="images/backgrounds/plus_sign.png" alt="">
                    <div class="card-details">
                        <div class="card-title">
                            <h4 class="card-title"><p>Add Event</p></h4>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <?php endif; ?>
</div>
    

