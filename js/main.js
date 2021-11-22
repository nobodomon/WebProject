/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function () {
    var toggled = false;
    $('.img-thumbnail').click(function () {
        if (toggled) {
            $(".pop-up").remove();
            toggled = false;
        } else {
            const popUp = document.createElement("span");
            popUp.setAttribute("class", "pop-up");
            const image = document.createElement("img");
            image.src = this.src.replace("small", "large");
            popUp.appendChild(image);
            var parent = $(this).parent();
            parent.append(popUp);
            toggled = true;
        }
    }
    );
    activateMenu()
    //$('.img-thumbnail').hover(function () {
    //alert("You are hovering over "+ this.alt)
    //}

    //);
    function activateMenu() {
        var current_page_URL = location.href;
        $(".navbar-nav li a").each(function () {
            var target_URL = $(this).prop("href");
            if (target_URL === current_page_URL) {
                $('nav a').parents('li, ul').removeClass('active');
                $(this).parent('li').addClass('active');
                return false;
            }
        });
    }
    ;
    $('.nav-link').click(activateMenu());
});
