jQuery(document).ready(function ($) {
  var isAjaxInProgress = false;
   function loadResources(page = 1, resource_topic = '', resource_type = '' ,search_resource_data='') {
        if (isAjaxInProgress) {
            return;
        }
        isAjaxInProgress = true; 
        $.ajax({
            url: my_ajax_obj.ajax_url,
            type: 'POST',
            data: {
                action: 'filter_resource',
                page: page,
                search_resource :search_resource_data,
                resource_topic: resource_topic,
                resource_type: resource_type,
               // nonce: ajax_obj.nonce
            },
            success: function (response) {
                $('#posts-container').html(response);
            },
            error: function (error) {
                console.error('Error:', error);
            },
            complete: function () {
                isAjaxInProgress = false; 
            }
        });
    }
    loadResources();

    $('#filter-btn').click(function (e) {
       // alert('sdsdsd');
        e.preventDefault();
        let resource_topic = $('#resource-topic-menu').val();
        let resource_type = $('#resource-type-menu').val();
        let search_resource_data = $('#search_resource_data').val();
        loadResources(1, resource_topic, resource_type, search_resource_data);
    });
    
    $(document).on('click', '.pagination a', function (e) {
        e.preventDefault();
        let page = $(this).attr('href').split('page/')[1];
        let resource_topic = $('#resource-topic-menu').val();
        let resource_type = $('#resource-type-menu').val();
        let search_resource_data = $('#search_resource_data').val();
        loadResources(page, resource_topic, resource_type,search_resource_data);
    });

});
