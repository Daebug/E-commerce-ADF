$(document).ready(function() {

     // Show modal when edit icon is clicked
     $("#editIcon").click(function() {
        $("#myModal").show();
    });

    // Close modal when close button is clicked
    $(".close").click(function() {
        $("#myModal").hide();
    });

    // Close modal when clicking outside the modal content
    $(window).click(function(event) {
        if ($(event.target).is("#myModal")) {
            $("#myModal").hide();
        }
    });


    // Function to handle tab clicks
    $('.tabs label').on('click', function() {
        var target = $(this).data('target');
        $('.tab-content').hide();
        $('#' + target).show();
    });

    // Ensure the default tab is displayed
    $('.tab-content').hide();
    $('#content-cart').show();

    // Function to handle toggling additional items
    $('.show-more').click(function() {
        var $button = $(this);
        var $container = $button.closest('.order-container');
        var $additionalItems = $container.find('.additional-item');

        // Toggle additional items
        $additionalItems.toggle();

        // Toggle button text
        if ($button.text() === 'Show More') {
            $button.text('Show Less');
        } else {
            $button.text('Show More');
        }
    });

    // Search function for content-cart
    $('#search-cart').on('input', function() {
        var searchText = $(this).val().toLowerCase();
        $('#content-cart table tbody tr').each(function() {
            var brand = $(this).find('td:nth-child(2)').text().toLowerCase();
            var name = $(this).find('td:nth-child(3)').text().toLowerCase();
            if (brand.includes(searchText) || name.includes(searchText)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Search function for content-ordered
    $('#search-ordered').on('input', function() {
        var searchText = $(this).val().toLowerCase();
        $('#content-ordered .order-summary').each(function() {
            var orderId = $(this).find('.order-id').text().toLowerCase();
            if (orderId.includes(searchText)) {
                $(this).closest('.order-container').show();
            } else {
                $(this).closest('.order-container').hide();
            }
        });
    });

    // Search function for content-to-receive
    $('#search-to-receive').on('input', function() {
        var searchText = $(this).val().toLowerCase();
        $('#content-to-receive table tbody tr').each(function() {
            var brand = $(this).find('td:nth-child(2)').text().toLowerCase();
            if (brand.includes(searchText)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Search function for content-completed
    $('#search-completed').on('input', function() {
        var searchText = $(this).val().toLowerCase();
        $('#content-completed .order-summary').each(function() {
            var orderId = $(this).find('.order-id').text().toLowerCase();
            if (orderId.includes(searchText)) {
                $(this).closest('.order-container').show();
            } else {
                $(this).closest('.order-container').hide();
            }
        });
    });

    // Search function for content-cancelled
    $('#search-cancelled').on('input', function() {
        var searchText = $(this).val().toLowerCase();
        $('#content-cancelled .order-summary').each(function() {
            var orderId = $(this).find('.order-id').text().toLowerCase();
            if (orderId.includes(searchText)) {
                $(this).closest('.order-container').show();
            } else {
                $(this).closest('.order-container').hide();
            }
        });
    });

});