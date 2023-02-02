'use strict';

$(document).ready(function () {
  const payment = $("#elementAttribute").data("element-input");
  const divParseElement = document.createElement('div');
  divParseElement.style.display = 'none';
  divParseElement.setAttribute('id', 'parseElement');
  document.getElementById('formInvoice').append(divParseElement);

  if (!payment.hasOwnProperty('dataParse')) {
    for (const key in payment) {
      if (Object.hasOwnProperty.call(payment, key)) {
        const element = payment[key];
        if (element.methodAction) {
          $("#formInvoice").attr({ 'method': element[Object.keys(element)] });
          continue;
        }
        if (element.urlAction) {
          $("#formInvoice").attr({ 'action': element[Object.keys(element)] });
          continue;
        }
        createElementInput({
          name: Object.keys(element),
          value: element[Object.keys(element)],
          idForm: divParseElement.getAttribute('id')
        });
      }
    }
  }
});

const createElementInput = ({ name, value, idForm }) => {
  const elmentInput = document.createElement('input');
  elmentInput.setAttribute('name', name);
  elmentInput.hidden = true;
  elmentInput.value = value || 'no value';
  document.getElementById(idForm || 'formInvoice').append(elmentInput);
  return;
}