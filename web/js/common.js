/**
 * Created by tharwat on 2/26/2019.
 */
$(document).ready(function() {

    $(".navShowHide").on("click", function() {

        var main = $("#mainSectionContainer");
        var nav = $("#sideNavContainer");

        if (main.hasClass("leftPadding")) {
            nav.hide();
            localStorage.setItem('sidenav.isOpen', 0);
        }
        else {
            nav.show();
            localStorage.setItem('sidenav.isOpen', 1);
        }
        main.toggleClass("leftPadding");
    });
});

function notSignedIn() {
    alert("You must be signed in to perform this action");
}