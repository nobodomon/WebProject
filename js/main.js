/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(document).ready(function () {
    var commentToggle = true;
    $('.commentToggle').click(function () {
        if (commentToggle) {
            $(this).parent().parent().parent().next().css("display", "block")
            $(this).children("span:first").text("chat_bubble")
            commentToggle = false;
        } else {
            $(this).parent().parent().parent().next().css("display", "none")
            $(this).children("span:first").text("chat_bubble_outline")
            commentToggle = true;
        }
    });
    var commentToggleViewPost = false;
    $('.commentToggleViewPost').click(function () {
        if (commentToggleViewPost) {
            $(this).parent().parent().parent().next().css("display", "block")
            $(this).children("span:first").text("chat_bubble")
            $(this).setAttribute('aria-pressed', 'true')
            commentToggleViewPost = false;
        } else {
            $(this).parent().parent().parent().next().css("display", "none")
            $(this).children("span:first").text("chat_bubble_outline")
            $(this).setAttribute('aria-pressed', 'false')
            commentToggleViewPost = true;
        }
    });
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

function searchUpdate() {
    var input, filter, container, label,checkbox, currLabel, i, txtValue;
    input = document.getElementById('searchTagBox');
    filter = input.value.toUpperCase();
    container = document.getElementById("interestTagGrp");
    label = container.getElementsByTagName('label');
    checkbox = container.getElementsByTagName('input');

    // Loop through all list items, and hide those who don't match the search query
    for (i = 0; i < label.length; i++) {
        currLabel = label[i];
        txtValue = currLabel.text || currLabel.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            label[i].style.display = "";
            checkbox[i].style.display = "";
        } else {
            label[i].style.display = "none";
            checkbox[i].style.display = "none";
        }
    }
}

function titleCharacterCount(){
    var inputCount, counter;
    inputCount = document.getElementById("title").value.length;
    counter = inputCount + "/255";
    document.getElementById("title-label").textContent = counter;
    
    document.getElementById("title-label").classList.remove("btn-dark");
    if(inputCount >= 255){
        document.getElementById("title-label").classList.remove("btn-success");
        document.getElementById("title-label").classList.add("btn-danger");
    }else{
        
        document.getElementById("title-label").classList.remove("btn-danger");
        document.getElementById("title-label").classList.add("btn-success");
    }
    
}

function registrationCharacterCount(inputField,labelToEdit,maxCount){
    var inputCount, counter;
    inputCount = document.getElementById(inputField).value.length;
    counter = inputCount + "/" + maxCount;
    document.getElementById(labelToEdit).textContent = counter;
    
    document.getElementById(labelToEdit).classList.remove("btn-dark");
    if(inputCount >= maxCount){
        document.getElementById(labelToEdit).classList.remove("btn-success");
        document.getElementById(labelToEdit).classList.add("btn-danger");
    }else{
        
        document.getElementById(labelToEdit).classList.remove("btn-danger");
        document.getElementById(labelToEdit).classList.add("btn-success");
    }
    
}
const oldTogglePassword = document.querySelector('#oldTogglePassword');
const oldPassword = document.querySelector('#oldPassword');

oldTogglePassword.addEventListener('click', function (e) {
    // toggle the type attribute
    const type = oldPassword.getAttribute('type') === 'password' ? 'text' : 'password';
    oldPassword.setAttribute('type', type);
    // toggle the eye / eye slash icon
    this.classList.toggle('bi-eye');
});

const newTogglePassword = document.querySelector('#newTogglePassword');
const newPassword = document.querySelector('#newPassword');

newTogglePassword.addEventListener('click', function (e) {
    // toggle the type attribute
    const type = newPassword.getAttribute('type') === 'password' ? 'text' : 'password';
    newPassword.setAttribute('type', type);
    // toggle the eye / eye slash icon
    this.classList.toggle('bi-eye');
});

const confirmNewTogglePassword = document.querySelector('#confirmNewTogglePassword');
const confirmNewPassword = document.querySelector('#confirmNewPassword');

confirmNewTogglePassword.addEventListener('click', function (e) {
    // toggle the type attribute
    const type = confirmNewPassword.getAttribute('type') === 'password' ? 'text' : 'password';
    confirmNewPassword.setAttribute('type', type);
    // toggle the eye / eye slash icon
    this.classList.toggle('bi-eye');
});
