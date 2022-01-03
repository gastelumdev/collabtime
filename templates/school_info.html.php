
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
                    <th scope="row">Group Name</th>
                    <td><?=$school['group_name']?></td>
                </tr>
                <tr>
                    <th scope="row">School - Street</th>
                    <td><?=$school['street']?></td>
                </tr>
                <tr>
                    <th scope="row">School - City</th>
                    <td><?=$school['city']?></td>
                </tr>
                <tr>
                    <th scope="row">School - Zipcode</th>
                    <td><?=$school['zipcode']?></td>
                </tr>
                <tr>
                    <th scope="row">Band Director Name</th>
                    <td><?=$school['band_director_name']?></td>
                </tr>
                <tr>
                    <th scope="row">Band Director Phone</th>
                    <td><?=$school['band_director_phone']?></td>
                </tr>
                <tr>
                    <th scope="row">Band Director Email</th>
                    <td><?=$school['band_director_email']?></td>
                </tr>
                <tr>
                    <th scope="row">Booster Parent Main Name</th>
                    <td><?=$school['booster_parent_name']?></td>
                </tr>
                <tr>
                    <th scope="row">Booster Parent Main Phone</th>
                    <td><?=$school['booster_parent_phone']?></td>
                </tr>
                <tr>
                    <th scope="row">Booster Parent Main Email</th>
                    <td><?=$school['booster_parent_email']?></td>
                </tr>
                <tr>
                    <th scope="row">Parade March Title</th>
                    <td><?=$school['parade_march_title']?></td>
                </tr>
                <tr>
                    <th scope="row">Parade March Composer</th>
                    <td><?=$school['parade_march_composer']?></td>
                </tr>
                <tr>
                    <th scope="row">Additional Band Staff Names</th>
                    <td><?=$school['additional_band_staff_names']?></td>
                </tr>
                <tr>
                    <th scope="row">Drum Major</th>
                    <td><?=$school['drum_major']?></td>
                </tr>
                <tr>
                    <th scope="row">Drum Major Name</th>
                    <td><?=$school['drum_major_name']?></td>
                </tr>
                <tr>
                    <th scope="row">Color Guard Advisor</th>
                    <td><?=$school['color_guard_advisor']?></td>
                </tr>
                <tr>
                    <th scope="row">Color Guard Captains</th>
                    <td><?=$school['color_guard_captains']?></td>
                </tr>
                <tr>
                    <th scope="row">Drill Team</th>
                    <td><?=$school['drill_team']?></td>
                </tr>
                <tr>
                    <th scope="row">Drill Team Advisor</th>
                    <td><?=$school['drill_team_advisor']?></td>
                </tr>
                <tr>
                    <th scope="row">Drill Team Captains</th>
                    <td><?=$school['drill_team_captains']?></td>
                </tr>
                <tr>
                    <th scope="row">School Enrollment</th>
                    <td><?=$school['school_enrollment']?></td>
                </tr>
                <tr>
                    <th scope="row">Number of Students in Band</th>
                    <td><?=$school['number_of_students_in_band']?></td>
                </tr>
                <tr>
                    <th scope="row">Number of Students in Color Guard</th>
                    <td><?=$school['number_of_students_in_color_guard']?></td>
                </tr>
                <tr>
                    <th scope="row">Number of Students in Drill Team</th>
                    <td><?=$school['number_of_students_in_drill_team']?></td>
                </tr>
                <tr>
                    <th scope="row">Number of Buses</th>
                    <td><?=$school['number_of_buses']?></td>
                </tr>
                <tr>
                    <th scope="row">Number of Box Trucks</th>
                    <td><?=$school['number_of_box_trucks']?></td>
                </tr>
                <tr>
                    <th scope="row">Number of Trailers</th>
                    <td><?=$school['number_of_trailers']?></td>
                </tr>
                <tr>
                    <th scope="row">School Tractor Trailer Rigs</th>
                    <td><?=$school['number_of_tractor_trailer_rigs']?></td>
                </tr>
                <tr>
                    <th scope="row">Special Instructions</th>
                    <td><?=$school['special_instructions']?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
    