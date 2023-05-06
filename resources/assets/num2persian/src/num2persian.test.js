/* eslint no-use-before-define: 0 */  // --> OFF

import Num2persian from './num2persian';
import tinyNumToWord from './num2persian';

test('Three Numbers To Letter 123', () => {
  expect(tinyNumToWord(123)).toBe('یکصد و بیست و سه');
});

test('Extra Chars  2.3345', () => {
  expect(Num2persian('%2.33a45')).toBe('دو ممیز سه هزار و سیصد و چهل و پنج ده‌هزارم');
});

test('Decimal Test  2.3345', () => {
  expect(Num2persian('2.3345')).toBe('دو ممیز سه هزار و سیصد و چهل و پنج ده‌هزارم');
});

test('Negative test', () => {
  expect(Num2persian('-11')).toBe('منفی یازده');
});

test('Three Numbers To Letter 10', () => {
  expect(tinyNumToWord(10)).toBe('ده');
});
test('test 0', () => {
  expect(Num2persian(0)).toBe('صفر');
});
test('test 100', () => {
  expect(Num2persian(100)).toBe('یکصد');
});
test('test 101', () => {
  expect(Num2persian(101)).toBe('یکصد و یک');
});
test('test 1250', () => {
  expect(Num2persian(1250)).toBe('یک هزار و دویست و پنجاه');
});
test('test 10001', () => {
  expect(Num2persian(10001)).toBe('ده هزار و یک');
});
test('test 101009', () => {
  expect(Num2persian(101009)).toBe('یکصد و یک هزار و نه');
});
test('test 5000000', () => {
  expect(Num2persian(5000000)).toBe('پنج میلیون');
});
test('test 10,000,000,000,000,001', () => {
  expect(Num2persian('10000000000000001')).toBe('ده بیلیارد و یک');
});
test('test 10,000,000,000,000,000,000,000,000,000,000,000,000,000,000,000,000,000,000,002,001', () => {
  expect(Num2persian('10000000000000000000000000000000000000000000000000000000002001')).toBe('ده دسیلیون و دو هزار و یک');
});
test('test 100,000,000,000,000,000,000,000,000,000,000,000,000,000,000,000,000,000,000,000,000,001', () => {
  expect(Num2persian('100000000000000000000000000000000000000000000000000000000000000001')).toBe('یکصد دسیلیارد و یک');
});
