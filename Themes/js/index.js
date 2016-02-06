function TriggerLoadingGif(){
    $('#processingGif').modal('show');
}

function TriggerPagination(textboxId, ele){
    var paginationValue = $('#'+textboxId).val();
    var hrefValue = ele.data('paginationhref');
    var newHref = hrefValue.replace('page=page-index', 'page='+paginationValue);
    window.location = newHref;
    
}