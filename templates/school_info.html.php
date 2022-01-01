
<div class="row">
    <div class="col col-lg-8">
        <a class="btn btn-primary btn-left" href="index.php?school/form?id=<?=$school['id']?>">Edit</a>
        <?php if ($role > 3): ?>
            <?php if ($status == 2): ?>
                <a class="btn btn-primary btn-left" href="index.php?events/schools/submittal/validate?id=<?=$school['id']?>">Validate</a>
            <?php else: ?>
                <a class="btn btn-primary btn-left" href="index.php?events/schools/submittal/devalidate?id=<?=$school['id']?>">Devalidate</a>
                <a class="btn btn-primary btn-left" href="index.php?events/schools/submittal/validate?id=<?=$school['id']?>">Revalidate</a>
            <?php endif; ?>
        <?php endif; ?>
        <table class="table table-sm">
            <tbody>
                <tr>
                    <th scope="row">School Name</th>
                    <td><?=$school['name']?></td>
                </tr>
                <tr>
                    <th scope="row">School City</th>
                    <td><?=$school['city']?></td>
                </tr>
                <tr>
                    <th scope="row">School Zipcode</th>
                    <td><?=$school['zipcode']?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
    