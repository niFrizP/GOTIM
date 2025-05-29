function estilizarSelect2(selector = '.select2-container--default .select2-selection--single') {
    setTimeout(() => {
        $(selector).each(function () {
            $(this).css({
                'background-color': '#fff',
                'border': '1px solid #d1d5db',
                'border-radius': '0.5rem',
                'height': '42px',
                'padding': '0.5rem 0.75rem',
                'font-size': '0.875rem',
                'color': '#000'
            });
        });

        $('.select2-selection__rendered').css({
            'color': '#000',
            'line-height': '1.5rem',
        });

        $('.select2-selection__arrow').css({
            'top': '8px',
            'right': '0.75rem'
        });
    }, 50);
}

function inicializarSelect2(selector, placeholder = 'Seleccione una opción') {
    $(selector).select2({
        placeholder,
        allowClear: true,
        width: 'resolve'
    });

    estilizarSelect2();
}
