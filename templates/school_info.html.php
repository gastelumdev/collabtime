<section id="body">
    <div class="container">
        <a class="btn btn-primary" href="index.php?school/form?id=<?=$school['id']?>">Edit</a>
        <div class="row">
            <div class="col col-lg-8">
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
    </div>
</section>