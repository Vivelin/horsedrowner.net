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

    function createKeyDownEventHandler(form) {
        return function(e) {
            if (form && e.keyCode == 13) {
                form.submit();
                return false;
            }
        };
    }

    var input = document.getElementById('input');
    if (input) {
        var string = input.elements['string'];
        if (string) {
            string.addEventListener('blur', createSubmitEventHandler(input));
            string.addEventListener('paste', createSubmitEventHandler(input));
            string.addEventListener('keydown', createKeyDownEventHandler(input));
        }
    }
}());