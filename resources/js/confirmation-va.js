'use strict';

$(document).ready(function () {
  // const payment = $("#elementAttribute").data("element-input");
  // const divParseElement = document.createElement('div');
  // divParseElement.style.display = 'none';
  // divParseElement.setAttribute('id', 'parseElement');
  // document.getElementById('formInvoice').append(divParseElement);

  let timeDisplay = document.getElementById("leftTime");
  console.log(timeDisplay);

  function refreshTime() {
    let dateString = new Date().toLocaleString();
    let formattedString = dateString.replace(", ", " - ");
    timeDisplay.innerHTML = formattedString;
  }

  // setInterval(refreshTime, 1000);


});