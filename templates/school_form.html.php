<section id="body">
    <div class="container">
    <form id="school-info" action="index.php?school/saveForm" method="post">
        <input type="hidden" name="id" value="<?=$school['id'] ?? ''?>">

        <div class="form-group">
            <label for="name">School Name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Enter name" value="<?=$school['name'] ?? ''?>" required>
        </div>
        <div class="form-group">
            <label for="group_name">Group Name (to be used by the Band Review Announcer)</label>
            <input type="text" class="form-control" id="group_name" name="group_name" placeholder="Enter group name" value="<?=$school['group_name'] ?? ''?>" required>
        </div>
        <div class="form-group">
            <label for="street">School - Street Address</label>
            <input type="text" class="form-control" id="street" name="street" placeholder="Enter street" value="<?=$school['street'] ?? ''?>" required>
        </div>
        <div class="form-group">
            <label for="city">School - City</label>
            <input type="text" class="form-control" id="city" name="city" placeholder="Enter city" value="<?=$school['city'] ?? ''?>" required>
        </div>
        <div class="form-group">
            <label for="zipcode">School - Zipcode</label>
            <input type="text" class="form-control" id="zipcode" name="zipcode" placeholder="Enter zipcode" value="<?=$school['zipcode'] ?? ''?>" required>
        </div>
        <div class="form-group">
            <label for="band_director_name">Band Director Name</label>
            <input type="text" class="form-control" id="band_director_name" name="band_director_name" placeholder="Enter band director name" value="<?=$school['band_director_name'] ?? ''?>" required>
        </div>
        <div class="form-group">
            <label for="band_director_phone">Band Director Phone</label>
            <input type="text" class="form-control" id="band_director_phone" name="band_director_phone" placeholder="Enter band director phone" value="<?=$school['band_director_phone'] ?? ''?>" required>
        </div>
        <div class="form-group">
            <label for="band_director_email">Band Director Email</label>
            <input type="text" class="form-control" id="band_director_email" name="band_director_email" placeholder="Enter band director email" value="<?=$school['band_director_email'] ?? ''?>" required>
        </div>
        <div class="form-group">
            <label for="booster_parent_name">Booster Parent Main Name</label>
            <input type="text" class="form-control" id="booster_parent_name" name="booster_parent_name" placeholder="Enter booster parent name" value="<?=$school['booster_parent_name'] ?? ''?>" required>
        </div>
        <div class="form-group">
            <label for="booster_parent_phone">Booster Parent Main Phone</label>
            <input type="text" class="form-control" id="booster_parent_phone" name="booster_parent_phone" placeholder="Enter booster parent phone" value="<?=$school['booster_parent_phone'] ?? ''?>" required>
        </div>
        <div class="form-group">
            <label for="booster_parent_email">Booster Parent Main Email</label>
            <input type="text" class="form-control" id="booster_parent_email" name="booster_parent_email" placeholder="Enter booster parent email" value="<?=$school['booster_parent_email'] ?? ''?>" required>
        </div>
        <div class="form-group">
            <label for="parade_march_title">Parade March Title</label>
            <input type="text" class="form-control" id="parade_march_title" name="parade_march_title" placeholder="Enter parade march title" value="<?=$school['parade_march_title'] ?? ''?>" required>
        </div>
        <div class="form-group">
            <label for="parade_march_composer">Parade March Composer</label>
            <input type="text" class="form-control" id="parade_march_composer" name="parade_march_composer" placeholder="Enter parade march composer" value="<?=$school['parade_march_composer'] ?? ''?>" required>
        </div>
        <div class="form-group">
            <label for="additional_band_staff_names">Additional Band Staff Names</label>
            <input type="text" class="form-control" id="additional_band_staff_names" name="additional_band_staff_names" placeholder="Enter additional band staff names" value="<?=$school['additional_band_staff_names'] ?? ''?>" required>
        </div>
        <div class="form-group">
            <label for="drum_major">Drum Major</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="drum_major" id="drum_major1" value="military" checked>
                <label class="form-check-label" for="drum_major1">
                    Military
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="drum_major" id="drum_major2" value="mace">
                <label class="form-check-label" for="drum_major2">
                    Mace
                </label>
            </div>
        </div>
        <div class="form-group">
            <label for="drum_major_name">Drum Major Name</label>
            <input type="text" class="form-control" id="drum_major_name" name="drum_major_name" placeholder="Enter drum major name" value="<?=$school['drum_major_name'] ?? ''?>" required>
        </div>
        <div class="form-group">
            <label for="color_guard_advisor">Color Guard Advisor</label>
            <input type="text" class="form-control" id="color_guard_advisor" name="color_guard_advisor" placeholder="Enter color guard advisor" value="<?=$school['color_guard_advisor'] ?? ''?>" required>
        </div>
        <div class="form-group">
            <label for="color_guard_captains">Color Guard Captains</label>
            <input type="text" class="form-control" id="color_guard_captains" name="color_guard_captains" placeholder="Enter color guard captains" value="<?=$school['color_guard_captains'] ?? ''?>" required>
        </div>
        <div class="form-group">
            <label for="drill_team">Drill Team</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="drill_team" id="drill_team1" value="Yes" checked>
                <label class="form-check-label" for="drill_team1">
                    Yes
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="drill_team" id="drill_team2" value="No">
                <label class="form-check-label" for="drill_team2">
                    No
                </label>
            </div>
        </div>
        <div class="form-group">
            <label for="drill_team_advisor">Drill Team Advisor</label>
            <input type="text" class="form-control" id="drill_team_advisor" name="drill_team_advisor" placeholder="Enter drill team advisor" value="<?=$school['drill_team_advisor'] ?? ''?>" required>
        </div>
        <div class="form-group">
            <label for="drill_team_captains">Drill Team Captains</label>
            <input type="text" class="form-control" id="drill_team_captains" name="drill_team_captains" placeholder="Enter drill team captains" value="<?=$school['drill_team_captains'] ?? ''?>" required>
        </div>
        <div class="form-group">
            <label for="school_enrollment">School Enrollment</label>
            <input type="text" class="form-control" id="school_enrollment" name="school_enrollment" placeholder="Enter school enrollment" value="<?=$school['school_enrollment'] ?? ''?>" required>
        </div>
        <div class="form-group">
            <label for="number_of_students_in_band">Number of students in band</label>
            <input type="text" class="form-control" id="number_of_students_in_band" name="number_of_students_in_band" placeholder="Enter number of students in band" value="<?=$school['number_of_students_in_band'] ?? ''?>" required>
        </div>
        <div class="form-group">
            <label for="number_of_students_in_color_guard">Number of students in color guard</label>
            <input type="text" class="form-control" id="number_of_students_in_color_guard" name="number_of_students_in_color_guard" placeholder="Enter number of students in color guard" value="<?=$school['number_of_students_in_color_guard'] ?? ''?>" required>
        </div>
        <div class="form-group">
            <label for="number_of_students_in_drill_team">Number of students in drill team</label>
            <input type="text" class="form-control" id="number_of_students_in_drill_team" name="number_of_students_in_drill_team" placeholder="Enter number of students in drill team" value="<?=$school['number_of_students_in_drill_team'] ?? ''?>" required>
        </div>
        <div class="form-group">
            <label for="number_of_buses">Number of buses</label>
            <input type="text" class="form-control" id="number_of_buses" name="number_of_buses" placeholder="Enter number of buses" value="<?=$school['number_of_buses'] ?? ''?>" required>
        </div>
        <div class="form-group">
            <label for="number_of_box_trucks">Number of box trucks</label>
            <input type="text" class="form-control" id="number_of_box_trucks" name="number_of_box_trucks" placeholder="Enter number of box trucks" value="<?=$school['number_of_box_trucks'] ?? ''?>" required>
        </div>
        <div class="form-group">
            <label for="number_of_trailers">Number of trailers</label>
            <input type="text" class="form-control" id="number_of_trailers" name="number_of_trailers" placeholder="Enter number of trailers" value="<?=$school['number_of_trailers'] ?? ''?>" required>
        </div>
        <div class="form-group">
            <label for="number_of_tractor_trailer_rigs">Number of tractor-trailer rigs</label>
            <input type="text" class="form-control" id="number_of_tractor_trailer_rigs" name="number_of_tractor_trailer_rigs" placeholder="Enter number of tractor-trailer rigs" value="<?=$school['number_of_tractor_trailer_rigs'] ?? ''?>" required>
        </div>
        <div class="form-group">
            <label for="special_instructions">Any Special Instructions?</label>
            <input type="text" class="form-control" id="special_instructions" name="special_instructions" placeholder="Enter Any Special Instructions" value="<?=$school['special_instructions'] ?? ''?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
    </div>
</section>