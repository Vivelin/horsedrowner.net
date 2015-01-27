/*global $, console, followedChannels*/

/**
 * Starts the "live on Twitch" update cycle.
 * @param {Array} [channels] A string array containing the names of Twitch channels to display.
 * @param {Number} [interval=60000] The interval between status update calls in milliseconds.
 * @returns {Number} A numerical id which can be used later with `clearInterval`.
 */
function beginUpdateStreams(channels, interval) {
    "use strict";
    interval = interval || 60000;
    
    function update() {
        var url = "https://api.twitch.tv/kraken/streams/"
                + "?channel=" + channels.join()
                + "&callback=?"; // Fuck browsers.
        
        $("#home-twitch-updating").show();
        $.getJSON(url, function (data) {
            $("#home-twitch-updating").hide();
            console.log(data);
            
            var $twitch = $("#twitch");
            $twitch.empty();
            
            $.each(data.streams, function (index, stream) {
                var isFucked = !stream.channel.hasOwnProperty("status"),
                    url = stream.channel.url
                        || "http://www.twitch.tv/" + stream.channel.name,
                    $div = $("<div>", { "class": "live twitch stream" }),
                    $icon = $("<i>", {
                        "class": "large live icon",
                        "title": "Live"
                    }),
                    $status = $("<a>", {
                        "href": url,
                        "title": stream.channel.status
                    }),
                    $name = $("<strong>", {
                        "text": stream.channel.display_name
                    }),
                    $game = $("<strong>", {
                        "text": stream.game
                    }),
                    $title = $("<em>", {
                        "text": stream.channel.status
                    }),
                    $viewers = $("<span>", {
                        "class": "viewers",
                        "title": "Watching now",
                        "text": parseInt(stream.viewers, 10).toLocaleString()
                    });
                
                $status.append($name);
                $status.append(" playing ");
                $status.append($game);
                if (!isFucked) {
                    $status.append(": ");
                    $status.append($title);
                }
                
                $icon.appendTo($div);
                $status.appendTo($div);
                $viewers.appendTo($div);
                
                $div.appendTo($twitch);
            });
        });
    }
    
    update();
    return setInterval(update, interval);
}

beginUpdateStreams(followedChannels);