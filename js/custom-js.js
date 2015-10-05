/**
 * Created by AEscobar on 10/4/15.
 */

(function ($) {
    $('#repeatable-add').on('click', function () {
        field = $(this).closest('td').find('.custom_repeatable li:last').clone(true);
        fieldLocation = $(this).closest('td').find('.custom_repeatable li:last');
        $('input', field).val('').attr('name', function (index, name) {
            return name.replace(/(\d+)/, function (fullMatch, n) {
                return Number(n) + 1;
            });
        });
        field.insertAfter(fieldLocation, $(this).closest('td'))
        return false;
    });

    $('.repeatable-remove').click(function () {

        $(this).parent().remove();
        return false;
    });

    $('.custom_repeatable').sortable({
        opacity: 0.6,
        revert: true,
        cursor: 'move',
        handle: '.sort'
    });
})(jQuery);