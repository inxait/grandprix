import $ from 'jquery';

export function isDataEmpty(obj) {
  let empty = false;

  Object.keys(obj).forEach((key) => {
    if (Object.prototype.hasOwnProperty.call(obj, key)) {
      if (obj[key] === '') {
        empty = true;
      }
    }
  });

  return empty;
}

export function getJsonFormData(form) {
  const formArray = $(form).serializeArray();
  const indexedData = {};
  /* eslint dot-notation: "off" */
  $.map(formArray, (n) => {
    indexedData[n['name']] = n['value'];
  });

  return indexedData;
}

export function isValidEmail(val) {
  const reg = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/;

  if (!reg.test(val)) {
    return false;
  }

  return true;
}

