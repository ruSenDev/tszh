$(document).ready(function () {
    $('.debt-button').click(function () {
        $(".debt-list-filter")[0].classList.toggle('show');
        $(".debt-button")[0].classList.toggle('show');
    });
});

$(document).ready(function () {
    $('.data-table').filter(function () {
        return $(this).find('td').length <= 1;
    }).hide();
});

$(document).ready(function () {
    $('.js-select2').select2({
        placeholder: "Поиск по квартирам",
        maximumSelectionLength: 2,
        language: "ru"
    });
});

$(document).ready(function () {
    $("#select1").change(function () {
        if ($(this).data('options') == undefined) {
            $(this).data('options', $('#select2 option').clone());
        }
        var id = $(this).val();
        var options = $(this).data('options').filter('[data-value=' + id + ']');
        $('#select2').html(options);
    });
    $('#select1').change();
});

function funonload() {
    document.querySelector('.select2-selection__placeholder').innerText = 'Поиск по адресу';
}

window.onload = funonload;