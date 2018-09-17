$('document').ready(function(){
    $title = $('.title-take').html();
    $('.c7n-intro h1').html($title);

    $document_height = $( document ).height();
    if (document.getElementById('home')!=null){
        $('.c7n-slide').height($document_height);
    }
});