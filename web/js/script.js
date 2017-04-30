﻿if ($(".js-popup-close").length) {
    $(".js-popup-close").on("click", function () {
        $(".ah-popup").each(function () {
            $(this).addClass("ah-hidden");
        })
    })
    $(".ah-popup-bg").on("click", function () {
        $(".ah-popup").each(function () {
            $(this).addClass("ah-hidden");
        })
    })
}
if ($(".js-button-auth").length) {
    $(".js-button-auth").on("click", function () {
        $(".js-popup-auth").removeClass("ah-hidden");
    })
}
if ($(".js-button-register").length) {
    $(".js-button-register").on("click", function () {
        $(".js-popup-register").removeClass("ah-hidden");
    })
}
if ($(".js-button-comment").length) {
    $(".js-button-comment").on("click", function () {
        $(".js-popup-comment").removeClass("ah-hidden");
    })
}
if ($(".js-button-reset").length) {
    $(".js-button-reset").on("click", function () {
        $(".js-popup-reset").removeClass("ah-hidden");
    })
}
if ($(".js-button-question").length) {
    $(".js-button-question").on("click", function () {
        $(".js-popup-question").removeClass("ah-hidden");
    })
}

$(document).ready(function(){
    $('.ah-give').on('click', 'ul li', function(){
        $('.ah-get ul.scrollbar').scrollbar("resize");
        $('.ah-reserve ul.scrollbar').scrollbar("resize");
    });
});