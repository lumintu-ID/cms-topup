'use strict';

// import sha256 from 'crypto-js/sha256';
// import hmacSHA512 from 'crypto-js/hmac-sha512';
// import Base64 from 'crypto-js/enc-base64';

// const message = 'hello';
// const nonce = 'nonce';
// const path = 'path'
// const privateKey = 'privatekey';
// const hashDigest = sha256(nonce + message);
// const hmacDigest = Base64.stringify(hmacSHA512(path + hashDigest, privateKey));

// console.log(hmacDigest);
import CryptoJS from 'crypto-js';

const token = document.querySelector('meta[name="token"]').content;
const dataPayment = JSON.parse(document.getElementsByTagName('body')[0].dataset.payment);
const keyGenrate = document.querySelector('meta[name="key-genarate"]').content;

console.log(dataPayment['initrequest']['key']);

console.log(keyGenrate);
console.log(token);

// Encrypt
let ciphertext = CryptoJS.AES.encrypt('tablorrr', keyGenrate).toString();
console.log(ciphertext); // 'my message'

// // Decrypt
// let bytes = CryptoJS.AES.decrypt(ciphertext, 'secret key 123');
// let originalText = bytes.toString(CryptoJS.enc.Utf8);

// console.log(originalText); // 'my message'



// Decrypt
let bytes = CryptoJS.AES.decrypt(ciphertext, keyGenrate);
// console.log(bytes);
let originalText = bytes.toString(CryptoJS.enc.Utf8);

// let token = document.querySelector("meta[name='token']").getAttribute("content");
// console.log(token);

console.log(originalText); // 'my message'


// window.addEventListener("load", function () {
//   var iframe = document.getElementById("idIframe");
//   iframe.contentWindow.location.href = "https://fightoflegends.co.id/";
// });