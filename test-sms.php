<?php

$templates = [
    'remind_sms' => "^Уважаемый\/-ая[а-яА-ЯЁёa-zA-Z0-9 ]*, Вы записаны на прием[0-9_\-: ]*к доктору[а-яА-ЯЁёa-zA-Z0-9 ]*\nС заботой, Stomaservice$",
    'money_credited' => "^Уважаемый\/-ая[а-яА-ЯЁёa-zA-Z0-9 ]*, ваш баланс пополнен на [0-9,.]* сум. \nС заботой, Stomaservice$",
    'money_withdrawn' => "^Уважаемый\/-ая[а-яА-ЯЁёa-zA-Z0-9 ]*, с вашего баланса списано [0-9,.]* сум. \nС заботой, Stomaservice$",
    'invoice_paid' => "^Уважаемый\/-ая[а-яА-ЯЁёa-zA-Z0-9 ]*, ваш счет [0-9№]* на [0-9,.]* сум оплачен на [0-9,.]* сум. \nС заботой, Stomaservice$",
    'have_debt' => "^Уважаемый\/-ая[а-яА-ЯЁёa-zA-Z0-9 ]*, у вас имеется долг, просим своевременно оплатить счета. \nС заботой, Stomaservice$",
    'reset_code' => "^Ваш код для восстановления в системе: [0-9]{4,9}, \nКоманда Stomaservice$",
];

function test($template,$text){
    if ( preg_match("/$template/u", $text) ) {
        echo 'true';
    } else echo 'false';
}

$text = "Ваш код для восстановления в системе: 555555, \nКоманда Stomaservice";

test($templates['reset_code'],$text);