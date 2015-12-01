(function () {
    function createSubmitEventHandler(form) {
        return function(e) {
            if (form) {
                window.setTimeout(function () {
                    if (e.target.value) {
                        form.submit();
                    }
                }, 1);
            }
        };
    }

    var input = document.getElementById('input');
    if (input) {
        var string = input.elements['string'];
        if (string) {
            string.addEventListener('blur', createSubmitEventHandler(input));
            string.addEventListener('paste', createSubmitEventHandler(input));
        }
    }
}());