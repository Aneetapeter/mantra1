$(document).ready(function () {

    // -------------------------------------------------
    // SIDEBAR / DASHBOARD UI LOGIC (UI ONLY)
    // -------------------------------------------------

    // When user clicks Dashboard but is NOT logged in → open login sidebar
    $(document).on('click', '#nav-dashboard-sidebar', function () {
        $('#profile-sidebar').addClass('active');
    });

    // Close sidebar button
    $('#close-sidebar').click(function () {
        $('#profile-sidebar').removeClass('active');
    });

    // Close sidebar when clicking outside
    $(document).mouseup(function (e) {
        var container = $("#profile-sidebar");
        if (!container.is(e.target) &&
            container.has(e.target).length === 0 &&
            !$(e.target).is('#nav-dashboard-sidebar')) {
            container.removeClass('active');
        }
    });

    // -------------------------------------------------
    // SIDEBAR LOGIN FORM → REDIRECT TO LARAVEL LOGIN PAGE
    // -------------------------------------------------

    $('#sidebar-login-form').on('submit', function (e) {
        e.preventDefault();

        // Instead of fake JS login, go to real Laravel login page
        window.location.href = '/login';
    });

});
