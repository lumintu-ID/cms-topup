'use strict';

import CryptoJS from 'crypto-js';

const dataPayment = JSON.parse(document.getElementsByTagName('body')[0].dataset.payment);
const apiKey = dataPayment['initrequest']['key'];

const keyDecode = (keyEncode) => {
  let key = atob(keyEncode).split('-');
  if (key[0] === CryptoJS.MD5(import.meta.env.VITE_FRONTEND_APP_KEY).toString()) return key;
  return ['Invalid key', 'no API Key'];
}

const key = keyDecode(apiKey);
// console.log(atob(key[1]));
// console.log(dataPayment);
delete dataPayment.initrequest.key;
dataPayment.initrequest.apiKey = atob(key[1]);
console.log(dataPayment);



window.addEventListener("load", function () {
  let iframe = document.getElementById("idIframe");
  iframe.src = "https://sandbox.codapayments.com/airtime/begin";
  // iframe.contentWindow.location.href = "https://fightoflegends.co.id/";
});