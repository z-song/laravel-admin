# NumToPersian

## Installation

### npm
```
  npm i num2persian
```
### bower
```
 bower install num2persian
 ```
## Usage
 ```
 import Num2persian from 'num2persian';
 console.log(Num2persian(123));
 ```
## Convert numbers/digits to Persian words in JavaScript

Just call `Num2persian()` or use `.num2persian()` prototype.

#  Example

```javascript
//Global function
Num2persian(1250); //output: یک هزار و دویست و پنجاه

//String Prototype
"2001".num2persian(); //output: دو هزار و یک

//Non-Digits
"%20s01".num2persian(); //output: دو هزار و یک
"2,001".num2persian(); //output: دو هزار و یک

//Number Prototype
(84000).num2persian(); //output: هشتاد و چهار هزار

//Float
(12.450).num2persian(); //output: دوازده ممیز چهل و پنج صدم

//Negative numbers
(-11).num2persian(); //output: منفی یازده

```

## تبدیل عدد به حروف فارسی در جاوا اسکریپت
توانایی پردازش اعداد تا 66 رقم عدد صحیح و 11 رقم اعشار | دسیلیارد

#### برای استفاده از اعداد بزرگ از نوع داده استرینگ استفاده کنید.

## [(CDN)](https://cdn.jsdelivr.net/gh/mahmoud-eskandari/NumToPersian/dist/num2persian-min.js) :
[https://cdn.jsdelivr.net/gh/mahmoud-eskandari/NumToPersian/dist/num2persian-min.js]

## [Github Page](https://mahmoud-eskandari.github.io/NumToPersian/)
