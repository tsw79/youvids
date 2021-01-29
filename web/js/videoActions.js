/**
 * Created by tharwat on 6/1/2019.
 */
function setSelectedThumbnail(thumbnailId, videoId, itemElement) {

    $.post("../../../../ajax/updateThumbnail.php", { videoId: videoId, thumbnailId: thumbnailId })

        .done(function(data) {
            var result = JSON.parse(data);

            var item = $(itemElement);
            var itemClass = item.attr("class");

            $("." + itemClass).removeClass("selected");

            item.addClass("selected");

            alert( hasError(result) ? result.error : result.success );
        });
}

function hasError(result) {
    return result.error != null ? true : false;
}