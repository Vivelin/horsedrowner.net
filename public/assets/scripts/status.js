/**
 * Starts the Steam status update cycle.
 */
function updateSteam()
{
    $("#display-name-loading").show();

    $.getJSON("/status/steam", function(status) {
        console.log(status);
        $("#display-name-loading").hide();

        $("#avatar").attr("class", "avatar " + status.status);

        var $displayName = $("#display-name");
        if ($displayName)
        {
            $displayName.attr("class", "title " + status.status);
            $displayName.text(status.message);
        }
    });

    setTimeout(updateSteam, 28000);
}

/**
 * Starts the Now Playing status update cycle.
 */
function updateNowPlaying()
{
    $("#nowplaying-loading").show();

    $.getJSON("/status/lastfm", function(track) {
        console.log(track);
        $("#nowplaying-loading").hide();

        if (track.playing)
        {
            $("#nowplaying span").text(track.artist + " - " + track.name);
            $("#nowplaying a").attr("href", track.url);
            $("#nowplaying").show();
        }
        else
        {
            $("#nowplaying").hide();
        }
    })

    setTimeout(updateNowPlaying, 28000)
}

updateSteam();
updateNowPlaying();
