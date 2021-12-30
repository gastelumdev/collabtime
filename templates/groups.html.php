<!-- ===================================================================
| GROUPS MAP
=================================================================== -->
<section id="groups-map">
    <div class="container">
        <div class="title">
            <h3>Groups Map</h3>
        </div>
        <!-- 2021-07-01 OG NEW - Markup contains a hidden input with the active user's zipcode and an input to update zipcode --> 
        <div class="search-groups">
            <input id="userZipcode" type="hidden" value="<?=$activeUser['zipcode'] ?? '91786'?>">
            <input type="text" id="search-groups" placeholder="Enter address">
            <button id="updateLocation" class="btn">Search</button>
        </div>
        <!-- 2021-07-01 OG NEW - The groups map --> 
        <div id="map"></div>
    </div>
</section>
<!-- 2021-07-01 OG NEW - The group cards will display when they are populated in the map.js script --> 
<section id="your-groups-section" class="groups">
    <div class="container">
        <div class="title">
            <h3>Groups</h3>
        </div>
        <p id="groupCount">Your Groups: 2, All Groups: 30</p>
        <div class="row" id='groupCards'></div>
    </div>
</section>
<script src="js/map.js"></script>
<script src="js/membership.js"></script>


