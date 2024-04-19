jQuery(function ($) {
    $(".has-sub-menu > a").click(function (e) {
        e.preventDefault();
        $(this).next().slideToggle();
        $(this).parent().toggleClass("active");
        $(this).parent().siblings().removeClass("active");
        $(this).parent().siblings().find(".sub-menu").slideUp();
        $(this).parent().siblings().find("> a i").removeClass("fa-minus");
        $(this).parent().siblings().find("> a i").addClass("fa-plus");

        if ($(this).find("i").hasClass("fa-plus")) {
            $(this).find("i").removeClass("fa-plus")
            $(this).find("i").addClass("fa-minus")
        } else {
            $(this).find("i").addClass("fa-plus")
            $(this).find("i").removeClass("fa-minus")
        }
    })

    // Function to update checkbox value to its ID when changed
    $("input[type='checkbox']").change(function () {
        if ($(this).is(":checked")) {
            $(this).val($(this).attr("id"));
        } else {
            $(this).val(""); // If unchecked, set value to empty string
        }
    });

    // Event listener for the change event of the select element
    $('#property').on('change', function () {
        // Get the selected option's value
        var propertyValue = $(this).val();

        // Redirect to the selected URL
        window.location.href = 'http://tracking-portals.test/dashboard?property=' + propertyValue;
    });
})
