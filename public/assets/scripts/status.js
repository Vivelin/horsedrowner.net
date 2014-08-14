/**
 * Starts an HTTP request for the a JSON resource at the specified URL.
 *
 * @param url The URL to request
 * @param onSuccess The function to call when the request is completed successfully. Receives the
 *                  parsed JSON object as parameter.
 * @param onError The function to call when the request fails. Receives the request object as
 *                parameter.
 */
function getJson(url, onSuccess, onError)
{
    if (typeof XMLHttpRequest !== "undefined")
    {
        var request = new XMLHttpRequest();
        request.open("GET", url, true);

        request.onreadystatechange = function()
        {
            if (this.readyState === 4)
            {
                if (this.status >= 200 && this.status < 400)
                {
                    data = JSON.parse(this.responseText);
                    if (onSuccess) onSuccess(data);
                }
                else if (onError)
                {
                    onError(this);
                }
            }
        };

        request.send();
        request = null;
    }
}

/**
 * Toggles the loading indicator for the specified element.
 *
 * @param id The ID of the element whose loading indicator to toggle, e.g. "display-name"
 * @param isLoading True to display the loading indicator, false to hide it
 */
function toggleLoading(id, isLoading)
{
    var elem = document.getElementById(id + "-loading");
    if (elem)
    {
        elem.className = isLoading ? "load-indicator" : "hidden";
    }
}

/**
 * Starts the Steam status update cycle.
 */
function updateSteam()
{
    toggleLoading("display-name", true);

    getJson("/status/steam", function(status) {
        console.log(status);
        toggleLoading("display-name", false);

        var avatar = document.getElementById("avatar");
        var displayName = document.getElementById("display-name");

        if (avatar)
        {
            avatar.className = "avatar " + status.status;
        }
        if (displayName)
        {
            displayName.className = "title " + status.status;
            displayName.textContent = status.message;
        }
    }, function(request) {
        console.error("/status/steam returned error " + request.status);
        toggleLoading("display-name", false);
    });

    setTimeout(updateSteam, 14000);
}

updateSteam();
