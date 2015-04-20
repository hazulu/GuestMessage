jQuery(document).ready(function ($) {
    function CloseGuestMessagePopup() {
        days = 30;
        myDate = new Date();
        myDate.setTime(myDate.getTime() + (days * 24 * 60 * 60 * 1000));
        document.cookie = 'CloseGuestMessagePopup=Remove; expires=' + myDate.toGMTString();
    }
    var cookie = document.cookie.split(';')
        .map(function (x) {
            return x.trim().split('=');
        })
        .filter(function (x) {
            return x[0] === 'CloseGuestMessagePopup';
        })
        .pop();
    if (cookie && cookie[1] === 'Remove') {
        $(".guestmessagepopup").hide();
    }
    $(document).on('click', '.close-guestmessagepopup', function () {
        CloseGuestMessagePopup();
        $(this).parent().remove();
        return false;
    });
});