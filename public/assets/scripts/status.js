/*global $, console */

/**
 * Starts the Steam status update cycle.
 * @param {Number} [interval=30000] The interval between status update calls in milliseconds.
 * @returns {Number} A numerical id, which can be used later with clearInterval.
 */
function beginUpdateSteam(interval) {
    "use strict";
    interval = interval || 30000;
    
    function update() {
        $("#display-name-loading").show();

        $.getJSON("/status/steam", function (status) {
            console.log(status);
            $("#display-name-loading").hide();

            $("#avatar").attr("class", "avatar " + status.status);

            var $displayName = $("#display-name");
            if ($displayName) {
                $displayName.attr("class", "title " + status.status);
                $displayName.text(status.message);
            }
        });
    }
    
    update();
    return setInterval(update, interval);
}

/**
 * Starts the Now Playing status update cycle.
 * @param {Number} [interval=30000] The interval between status update calls in milliseconds.
 * @returns {Number} A numerical id, which can be used later with clearInterval.
 */
function beginUpdateNowPlaying(interval) {
    "use strict";
    interval = interval || 30000;
    
    function update() {
        $("#nowplaying-loading").show();

        $.getJSON("/status/lastfm", function (track) {
            console.log(track);
            $("#nowplaying-loading").hide();

            if (track.playing) {
                $("#nowplaying span").text(track.text);
                $("#nowplaying a").attr("href", track.url);
                $("#nowplaying").show();
            } else {
                $("#nowplaying").hide();
            }
        });
    }
    
    update();
    return setInterval(update, interval);
}

/**
 * Starts the Twitch status update cycle.
 * @param {Number} [interval=60000] The interval between status update calls in milliseconds.
 * @returns {Number} A numerical id, which can be used later with clearInterval.
 */
function beginUpdateTwitch(interval) {
    "use strict";
    interval = interval || 60000;
    
    function update() {
        $("#twitch-alert-loading").show();

        $.getJSON("/status/twitch", function (stream) {
            console.log(stream);
            $("#twitch-alert-loading").hide();

            if (stream.live) {
                $("#twitch-status").text(stream.status);
                $("#twitch-game").text(stream.game);
                $("#twitch-alert a").attr("href", stream.link);
                $("#twitch-alert").show();
            } else {
                $("#twitch-alert").hide();
            }
        });
    }

    update();
    return setInterval(update, interval);
}

beginUpdateSteam();
beginUpdateNowPlaying();
beginUpdateTwitch();
