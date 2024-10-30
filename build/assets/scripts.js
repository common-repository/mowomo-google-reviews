// Function that fetch the data from a place from google places api and show the review
function fetchData(block, index, placeID, numVal, minVal, rand) {
    // Create a map
    map = new google.maps.Map(document.getElementById("map" + index), {
        center: { lat: -33.8666, lng: 151.1958 },
        zoom: 15
    });

    // Prepare the request
    var request = {
        placeId: placeID
    };

    // Make the fetch
    service = new google.maps.places.PlacesService(map);
    service.getDetails(request, function(results, status) {
        if (status == google.maps.places.PlacesServiceStatus.OK) {
            // Clean the block container
            block.find("#map" + index).remove();
            block.find("script").remove();
            console.log(results);
            // Make the magic!
            let reviews = results.reviews;
            reviews.forEach(rev => {
                console.log(rev);
                // We filter the reviews by the minimum score
                if (rev.rating >= parseInt(minVal)) {
                    // We limit the number of reviews shown
                    if (numVal >= 1) {
                        let stars = "";
                        for (let index = 1; index <= 5; index++) {
                            if (index <= rev.rating) {
                                stars += "<i class='fas fa-star'></i>";
                            } else {
                                stars += "<i class='far fa-star'></i>";
                            }
                        }
                        block.append(
                            "<article class='valoracion'>" +
                                "<div class='google-icon'><i class='fab fa-google'></i></div>" +
                                "<div class='valoracion__metas'>" +
                                "<div class='valoracion__img'>" +
                                "<img src='" +
                                rev.profile_photo_url +
                                "' alt='" +
                                rev.author_name +
                                "'>" +
                                "</div>" +
                                "<div class='valoracion__text'>" +
                                "<div class='valoracion__nombre'>" +
                                "<a href='" +
                                rev.author_url +
                                "'>" +
                                rev.author_name +
                                "</a>" +
                                "</div>" +
                                "<div class='valoracion__star'>" +
                                stars +
                                "</div>" +
                                "<div class='valoracion__tiempo'>" +
                                "<p>" +
                                rev.relative_time_description +
                                "</p>" +
                                "</div>" +
                                "</div>" +
                                "</div>" +
                                "<div class='valoracion__texto'>" +
                                "<p>" +
                                rev.text +
                                "</p>" +
                                "</div>" +
                                "</article>"
                        );
                        numVal--;
                    }
                }
            });
        }
    });
}

jQuery(window).on("load", function() {
    // Initiating data for google
    let index = 0;

    // Enumerating elements
    jQuery(".bgr").each(function() {
        jQuery(this).attr("mwm-i", index);
        jQuery(this)
            .find("#map")
            .attr("id", "map" + index);
        index++;
    });

    // Invoke the fetch to show every reviews
    jQuery(".bgr").each(function() {
        let index = jQuery(this).attr("mwm-i");
        let placeID = jQuery(this).attr("mwm-place");
        let numVal = jQuery(this).attr("mwm-num-val");
        let minVal = jQuery(this).attr("mwm-min-val");
        let rand = jQuery(this).attr("mwm-rand");
        fetchData(jQuery(this), index, placeID, numVal, minVal, rand);
    });
});
