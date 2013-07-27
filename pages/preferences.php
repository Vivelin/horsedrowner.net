<?php
function getCookie($cookie) {
    $value = false;
    if (isset($_COOKIE[$cookie]))
        $value = $_COOKIE[$cookie];
    return ($value == "1");
}
?>
<form id="preferences" action="preferences.php" method="post">
    <fieldset>
        <p>
            <input type="checkbox" id="pref-dark" name="dark" value="1" <?php if (getCookie("dark")) echo 'checked="checked"'; ?> />
            <label for="pref-dark">Use alternative background color</label>
            <br>
            <input type="checkbox" id="pref-caps" name="caps" value="1" <?php if (getCookie("caps")) echo 'checked="checked"'; ?> />
            <label for="pref-caps">Capitalize items in navigation bar</label>
        <p><input type="submit" value="Save preferences" />
    </fieldset>
    <p class="Subtle">
        This shit uses <a href="http://allrecipes.com/recipes/desserts/cookies/">cookies</a>. Once 
        you click this button, you agree that this site can store cookies to save the options you 
        selected above.
</form>