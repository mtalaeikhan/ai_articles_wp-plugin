jQuery(document).ready(function ($) {
    // Add spinner functionality when saving settings
    $('form').on('submit', function () {
        const $spinner = $('.spinner');
        $spinner.show(); // Show the spinner
        setTimeout(() => {
            $spinner.hide(); // Hide the spinner after 2 seconds (demo purpose)
        }, 2000);
    });

    // Add smooth transitions for input focus
    $('input[type="text"], textarea').on('focus', function () {
        $(this).css('box-shadow', '0 0 5px rgba(0, 115, 170, 0.5)');
    }).on('blur', function () {
        $(this).css('box-shadow', 'none');
    });
});
