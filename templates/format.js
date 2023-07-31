"use strict"

// Burger menu (when window width <= 700px)
let MainHeader = document.querySelector('.main_header');
let logo = document.querySelector('.main_header .logo')
let BurgerHeader = document.querySelector('.burger_header');
let BurgerMenu = document.querySelector('.burger_header img');

if(window.innerWidth <= 700){
    logo.cssText = `display:none`;
    MainHeader.style.cssText = `display:none`;
    document.querySelector('.main_header button').style.cssText = `display: block`;
}
else{
    logo.cssText = `display:block`;
    BurgerHeader.style.cssText = `display:none`;
    document.querySelector('.main_header button').style.cssText = `display: none`;
}

document.querySelector('.main_header button').addEventListener('click', function(){
    MainHeader.style.cssText = `display:none`;
});

BurgerMenu.addEventListener('click', function(){
    MainHeader.style.cssText = `display:flex`;
    document.querySelector('.main_header button').cssText = `display: block`;
});