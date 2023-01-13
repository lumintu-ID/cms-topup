import './bootstrap';
import '../scss/app.scss';

const loader = document.getElementById("loader");
const body = document.getElementsByTagName("body")[0];
body.style.overflow = 'hidden';

window.addEventListener('load', (event) => {  
  loader.style.display = 'none';
  body.style.removeProperty('overflow');
});

