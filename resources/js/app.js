import './bootstrap';
import '../scss/app.scss';

let loader = document.getElementById("loader");

window.addEventListener('load', (event) => {  
  loader.style.display = "none";
  // loader.style.transition = "10s linear display";
  // loader.addEventListener("transitionend", () => {
  //   // console.log('transtionend');
  // });
});

