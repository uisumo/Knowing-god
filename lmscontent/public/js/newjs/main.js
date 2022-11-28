
$(document).ready(function() {
    "use strict";

    /*Sidebar Menu*/
    $("#ag-menu").metisMenu();

    $(".right-side-toggle").click(function() {
        $(".right-sidebar").toggleClass("shw-rside"),$(".fxhdr").click(function () {
            $("body").toggleClass("fix-header")
        }),$("body").hasClass("fix-header") ? $(".fxhdr").attr("checked", !0) : $(".fxhdr").attr("checked", !1)

    });
    // Sidebar open close
    $(".open-close").on('click', function () {
        if ($("body").hasClass("content-wrapper")) {
            $("body").trigger("resize");

            $("body").removeClass("content-wrapper");

        }
        else {
            $("body").trigger("resize");

            $("body").addClass("content-wrapper");

        }
    });

    // This is for resize window
    $(function () {
        $(window).bind("load resize", function () {
            var width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
            if (width < 1170) {
                $('body').addClass('content-wrapper');
            }
            else {
                $('body').removeClass('content-wrapper');
            }
        });
    });

    $(".sidebar-toggle-btn").on('click', function () {
        $(".sidebar ").toggleClass("sidebar-toggle");
        $(".mb-sidebar-close ").toggleClass("show");

    });

    $(".expand-bar-toggle").on('click', function () {
        $(".right-sidbar-added ").toggleClass("expand-toggle-right-sidebar");
    });


    /* Theme color change*/
    var theme_settings = $(".theme-settings").find(".theme-color");
    theme_settings.on('click', function () {
        var val = $(this).attr('data-color');
        $('#style_theme').attr('href', 'css/' + val + '.css');
        console.log(val);
        theme_settings.removeClass('theme-active');
        theme_settings.addClass('theme-active');
        return false;
    });

    /*Text Editor*/
    var $text_editor = $('.textarea_editor');
    if ($($text_editor).length) {
        $($text_editor).wysihtml5();
    }

    /* Enable tooltips*/
    $('[data-toggle="tooltip"]').tooltip()

    /* JRATE Star Rating -- SVG based Rating jQuery plugin -- for docs rafy-fa plugin -- http://jacob87.github.io/raty-fa/ */
    var $star_rate = $('.startRate');
    if ($($star_rate).length) {
        $('.startRate').raty({
            score: 3
        });
    }
} );
