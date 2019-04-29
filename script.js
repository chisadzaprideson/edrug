// detect enter keypress
$(document).keypress(function(e) {
    var keycode = (e.keyCode ? e.keyCode : e.which);
    if (keycode == '13') {
        alert('You pressed enter! - keypress');
    }
});

// write small plugin for keypress enter detection
$.fn.enterKey = function (fnc) {
    return this.each(function () {
        $(this).keypress(function (e) {
            var keycode = (e.keyCode ? e.keyCode : e.which);
            if (keycode == '13') {
                fnc.call(this, e);
            }
        })
    })
};

// use custom plugin
$(document).enterKey(function () {
    alert('You pressed enter! - enterKey');
});

// handle enter plain javascript
function handleEnter(e){
    var keycode = (e.keyCode ? e.keyCode : e.which);
    if (keycode == '13') {
        alert('You pressed enter! - plain javascript');
    }
}
