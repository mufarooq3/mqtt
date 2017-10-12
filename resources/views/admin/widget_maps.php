<div class="form-group">
    <input type="text" value="" id="lat" name="lat" placeholder="Latitude" class="span12 mTitle black">
</div>
<div class="form-group">
    <input type="text" value="" id="long" name="long" placeholder="Longitude" class="span12 mTitle black">
</div>
<div class="form-group">
    <select  id="d" name="d" class="span12 mTitle black">
        <?php
        for ($i = 10; $i < 250; $i+=10) {
            ?>
            <option value="<?php echo $i ?>"><?php echo $i ?></option>
            <?php
        }
        ?>
    </select>
</div>
<input type="hidden" name="distance" id="dis"/>
<div class="form-group">
    <input id="pac-input" class="span12 mTitle black" type="text" placeholder="Search Location">
</div>
<div id="cols">
    <div id="map-wrapper">
        <div id="map"></div>

    </div>
</div>
<br>
<div id="search">
    <div>
        <label>within <span id="dist"></span> km
            <span id="of"></span></label>

        <input type="button" value="Search!" id="btn"/>
        <label class="pull-right">Found <span  id="countUser">0</span> user(s)</label>
    </div>
</div>
