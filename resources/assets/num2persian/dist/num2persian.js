/**
 * Name:Javascript Number To Persian Convertor.
 * License: GPL-2.0
 * Generated on 2021-01-13
 * Author:Mahmoud Eskanadri.
 * Copyright:2018 http://Webafrooz.com.
 * version:3.2.2
 * Email:info@webafrooz.com,sbs8@yahoo.com
 * coded with ♥ in Webafrooz.
 * big numbers refrence: https://fa.wikipedia.org/wiki/%D9%86%D8%A7%D9%85_%D8%A7%D8%B9%D8%AF%D8%A7%D8%AF_%D8%A8%D8%B2%D8%B1%DA%AF
 */

"use strict";

/**
 *
 * @type {string}
 */
var delimiter = ' و ';
/**
 *
 * @type {string}
 */

var zero = 'صفر';
/**
 *
 * @type {string}
 */

var negative = 'منفی ';
/**
 *
 * @type {*[]}
 */

var letters = [['', 'یک', 'دو', 'سه', 'چهار', 'پنج', 'شش', 'هفت', 'هشت', 'نه'], ['ده', 'یازده', 'دوازده', 'سیزده', 'چهارده', 'پانزده', 'شانزده', 'هفده', 'هجده', 'نوزده', 'بیست'], ['', '', 'بیست', 'سی', 'چهل', 'پنجاه', 'شصت', 'هفتاد', 'هشتاد', 'نود'], ['', 'یکصد', 'دویست', 'سیصد', 'چهارصد', 'پانصد', 'ششصد', 'هفتصد', 'هشتصد', 'نهصد'], ['', ' هزار', ' میلیون', ' میلیارد', ' بیلیون', ' بیلیارد', ' تریلیون', ' تریلیارد', ' کوآدریلیون', ' کادریلیارد', ' کوینتیلیون', ' کوانتینیارد', ' سکستیلیون', ' سکستیلیارد', ' سپتیلیون', ' سپتیلیارد', ' اکتیلیون', ' اکتیلیارد', ' نانیلیون', ' نانیلیارد', ' دسیلیون', ' دسیلیارد']];
/**
 * Decimal suffixes for decimal part
 * @type {string[]}
 */

var decimalSuffixes = ['', 'دهم', 'صدم', 'هزارم', 'ده‌هزارم', 'صد‌هزارم', 'میلیونوم', 'ده‌میلیونوم', 'صدمیلیونوم', 'میلیاردم', 'ده‌میلیاردم', 'صد‌‌میلیاردم'];
/**
 * Clear number and split to 3 sections
 * @param {*} num
 */

var prepareNumber = function prepareNumber(num) {
  var out = num;

  if (typeof out === 'number') {
    out = out.toString();
  } //make first part 3 chars


  if (out.length % 3 === 1) {
    out = "00".concat(out);
  } else if (out.length % 3 === 2) {
    out = "0".concat(out);
  } // Explode to array


  return out.replace(/\d{3}(?=\d)/g, '$&*').split('*');
}; //tinyNumToWord convert 3tiny parts to word


var tinyNumToWord = function tinyNumToWord(num) {
  // return zero
  if (parseInt(num, 0) === 0) {
    return '';
  }

  var parsedInt = parseInt(num, 0);

  if (parsedInt < 10) {
    return letters[0][parsedInt];
  }

  if (parsedInt <= 20) {
    return letters[1][parsedInt - 10];
  }

  if (parsedInt < 100) {
    var _one = parsedInt % 10;

    var _ten = (parsedInt - _one) / 10;

    if (_one > 0) {
      return letters[2][_ten] + delimiter + letters[0][_one];
    }

    return letters[2][_ten];
  }

  var one = parsedInt % 10;
  var hundreds = (parsedInt - parsedInt % 100) / 100;
  var ten = (parsedInt - (hundreds * 100 + one)) / 10;
  var out = [letters[3][hundreds]];
  var secondPart = ten * 10 + one;

  if (secondPart === 0) {
    return out.join(delimiter);
  }

  if (secondPart < 10) {
    out.push(letters[0][secondPart]);
  } else if (secondPart <= 20) {
    out.push(letters[1][secondPart - 10]);
  } else {
    out.push(letters[2][ten]);

    if (one > 0) {
      out.push(letters[0][one]);
    }
  }

  return out.join(delimiter);
};
/**
 * Convert Decimal part
 * @param decimalPart
 * @returns {string}
 * @constructor
 */


var convertDecimalPart = function convertDecimalPart(decimalPart) {
  // Clear right zero
  decimalPart = decimalPart.replace(/0*$/, "");

  if (decimalPart === '') {
    return '';
  }

  if (decimalPart.length > 11) {
    decimalPart = decimalPart.substr(0, 11);
  }

  return ' ممیز ' + Num2persian(decimalPart) + ' ' + decimalSuffixes[decimalPart.length];
};
/**
 * Main function
 * @param input
 * @returns {string}
 * @constructor
 */


var Num2persian = function Num2persian(input) {
  // Clear Non digits
  input = input.toString().replace(/[^0-9.-]/g, '');
  var isNegative = false;
  var floatParse = parseFloat(input); // return zero if this isn't a valid number

  if (isNaN(floatParse)) {
    return zero;
  } // check for zero


  if (floatParse === 0) {
    return zero;
  } // set negative flag:true if the number is less than 0


  if (floatParse < 0) {
    isNegative = true;
    input = input.replace(/-/g, '');
  } // Declare Parts


  var decimalPart = '';
  var integerPart = input;
  var pointIndex = input.indexOf('.'); // Check for float numbers form string and split Int/Dec

  if (pointIndex > -1) {
    integerPart = input.substring(0, pointIndex);
    decimalPart = input.substring(pointIndex + 1, input.length);
  }

  if (integerPart.length > 66) {
    return 'خارج از محدوده';
  } // Split to sections


  var slicedNumber = prepareNumber(integerPart); // Fetch Sections and convert

  var out = [];

  for (var i = 0; i < slicedNumber.length; i += 1) {
    var converted = tinyNumToWord(slicedNumber[i]);

    if (converted !== '') {
      out.push(converted + letters[4][slicedNumber.length - (i + 1)]);
    }
  } // Convert Decimal part


  if (decimalPart.length > 0) {
    decimalPart = convertDecimalPart(decimalPart);
  }

  return (isNegative ? negative : '') + out.join(delimiter) + decimalPart;
}; //@depercated


String.prototype.toPersianLetter = function () {
  return Num2persian(this);
}; //@depercated


Number.prototype.toPersianLetter = function () {
  return Num2persian(parseFloat(this).toString());
};

String.prototype.num2persian = function () {
  return Num2persian(this);
};

Number.prototype.num2persian = function () {
  return Num2persian(parseFloat(this).toString());
};