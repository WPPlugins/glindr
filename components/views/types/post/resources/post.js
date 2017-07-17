jQuery(document).ready(function($) {
    var validate = function(event) {
        var $syndicatePost = $('#gc_syndicate_post'),
            $syndicateCats = $('.gc_syndicate_cat:checked');
            $syndicateCont = $('#gc_syndicate_cats'),
            $editorCont    = $('#wp-content-wrap'),
            $title         = $('#titlewrap'),
            content        = $.trim($editorCont.is('.tmce-active') ? tinyMCE.get('content').getContent() : $editorCont.find('#content').val());
            syndicating    = $syndicatePost.prop('checked'),
            submittable    = true,
            title          = $.trim($title.find('#title').val());
           
        if(syndicating) {
            if(0 === $syndicateCats.size()) {
                submittable = false;
            
                $syndicateCont.pointer(GC_Views_Post.pointers.categories).pointer('open').data('gc_pointer', true);
            } else if($syndicateCont.data('gc_pointer')) {
                $syndicateCont.pointer('close');
            }
            
            if('' === content) {
                submittable = false;
                
                $editorCont.pointer(GC_Views_Post.pointers.content).pointer('open').data('gc_pointer', true);
            } else if($editorCont.data('gc_pointer')) {
                $editorCont.pointer('close');
            }
            
            if('' === title) {
                submittable = false;
                
                $title.pointer(GC_Views_Post.pointers.title).pointer('open').data('gc_pointer', true);
            } else if($title.data('gc_pointer')) {
                $title.pointer('close');
            }
        
            if(!submittable) {
                event.stopPropagation();
                event.stopImmediatePropagation();
                event.preventDefault();
            }
        } else if(!$syndicatePost.data('gc_pointer') && 0 !== $syndicateCats.size()) {
            $syndicatePost.pointer(GC_Views_Post.pointers.syndication).pointer('open').data('gc_pointer', true);
            
            event.stopPropagation();
            event.stopImmediatePropagation();
            event.preventDefault();
        } else if($syndicatePost.data('gc_pointer')) {
            $syndicatePost.pointer('close');
        }
    };
    
    $(document).on('click',  'input#publish', validate);
    $(document).on('submit', 'form#post',     validate);
});
