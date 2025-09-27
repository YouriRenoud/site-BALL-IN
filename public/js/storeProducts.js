$(document).ready(function() {
    $("#product-select").select2({
        placeholder: "Search for a product...",
        allowClear: true,
        ajax: {
            url: "../../app/functions/searchProduct.php",
            type: "GET",
            dataType: "json",
            delay: 250,
            data: function(params) {
                return { term: params.term };
            },
            processResults: function(data) {
                return { results: data };
            },
            cache: true
        }
    });
});
