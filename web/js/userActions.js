/**
 * Created by tharwat on 5/21/2019.
 */
function subscribe(userTo, userFrom, button) {

    if(userTo == userFrom) {
        alert("You can't subscribe to yourself");
        return;
    }

    $.post("../ajax/subscribe.php", { userTo: userTo, userFrom: userFrom })
        .done(function(numSubscribers) {

            console.log(numSubscribers);

            if(numSubscribers != null) {

                $(button).toggleClass("subscribe unsubscribe");
                var buttonText = $(button).hasClass("subscribe") ? "SUBSCRIBE" : "SUBSCRIBED";
                $(button).text(buttonText + " " + numSubscribers);
            }
            else {
                alert("Something went wrong");
            }
        });
}