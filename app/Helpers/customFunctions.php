
<?php

function getRandomNumberArray($startRange, $endRange, $numbers)
{
    $randomNumber = range($startRange, $endRange);
    shuffle($randomNumber);
    return array_slice($randomNumber, 0, $numbers);
}

function faq($number, $title)
{

    $a = array(
        "<div class='accordion-item'>
        <h2 class='accordion-header' id='headingOne'>
            <button type='button' class='accordion-button' data-bs-toggle='collapse'
                data-bs-target='#collapseOne'>What are the 2 types of $title?</button>
        </h2>
        <div id='collapseOne' class='accordion-collapse show' data-bs-parent='#myAccordion'>
            <div class='card-body'>
                <p>Ensure that you typed your details
                    correctly means if some of the letters
                    are in the capital or symbol then please
                    enter all that very carefully. If there is
                    an option for viewing your password,
                    use it. Providing there is no one that
                    can not see your password around.</p>
            </div>
        </div>
    </div>",

        "<div class='accordion-item'>
    <h2 class='accordion-header' id='headingOne'>
        <button type='button' class='accordion-button' data-bs-toggle='collapse'
            data-bs-target='#collapseOne'>What are the 2 types of $title?</button>
    </h2>
    <div id='collapseOne' class='accordion-collapse show' data-bs-parent='#myAccordion'>
        <div class='card-body'>
            <p>Ensure that you typed your details
                correctly means if some of the letters
                are in the capital or symbol then please
                enter all that very carefully. If there is
                an option for viewing your password,
                use it. Providing there is no one that
                can not see your password around.</p>
        </div>
    </div>
</div>",

        "<div class='accordion-item'>
<h2 class='accordion-header' id='headingOne'>
    <button type='button' class='accordion-button' data-bs-toggle='collapse'
        data-bs-target='#collapseOne'>What are the 2 types of $title?</button>
</h2>
<div id='collapseOne' class='accordion-collapse show' data-bs-parent='#myAccordion'>
    <div class='card-body'>
        <p>Ensure that you typed your details
            correctly means if some of the letters
            are in the capital or symbol then please
            enter all that very carefully. If there is
            an option for viewing your password,
            use it. Providing there is no one that
            can not see your password around.</p>
    </div>
</div>
</div>",
    );
    $random_keys = array_rand($a, $number);
    foreach (range(1, $number) as $index => $value) {
        echo $a[$random_keys[$index]];
    }
}