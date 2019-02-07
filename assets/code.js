(function($){
    $(document).ready(function(){
        for(var key in galleries){
            if(galleries.hasOwnProperty(key)){
                var count =1;
                for(var k in galleries[key])if(galleries[key].hasOwnProperty(k))count++;
                if((count*174)>$('.gallery-image-holder').width()){
                    $('#gallery_'+key+' .gallery-image-holder').css({
                        'resize': 'vertical',
                        'height': '250px'
                    });
                }
            }
        }
        var modal = document.getElementById('wgp-modal');
        function open_wgp_modal() {
            modal.style.display = "block";
        }
        var mediaUploader = wp.media.frames.file_frame = wp.media({
          title: 'Choose Image',
          button: {
          text: 'Choose Image'
        }, multiple: false });


        $('#create-new-gallery').click(function(e){
            var url=$(this).attr('action');
            $.ajax({
                url:url,
                type:'post',
                data:'action=add_gallery',
                success: function (result) {
                    //console.log(result);
                    location.reload();
                }
            });
        });
        $('.update_name_of_gallery').click(function(e){
            var url=$(this).attr('action');
            var id=$(this).attr('id').replace('gal_','');
            var data = $('#gallery_name_'+id).val();
            console.log(url,id,data);
              $.ajax({
                url:url,
                type:'post',
                data:'action=upd_gallery&id='+id+'&name='+data,
                success: function (result) {
                    location.reload();
                }
            });
        });
        $('.new-gallery-image').click(function(e){
            var url=$(this).attr('action');
            var id=$(this).attr('id').replace('new_','');
            mediaUploader.on('select', function() {
              var attachment = mediaUploader.state().get('selection').first().toJSON();
                   console.log('action=add_image&id='+id+'&path='+attachment.url);
              $.ajax({
                url:url,
                type:'post',
                data:'action=add_image&id='+id+'&path='+attachment.url,
                success: function (result) {
                    location.reload();
                }
            });

            });
            // Open the uploader dialog
            mediaUploader.open();
        });
        $('.update-image').click(function(e){
            var url=$(this).attr('action');
            var id=$(this).attr('id').replace('upd_','');
            mediaUploader.on('select', function() {
              var attachment = mediaUploader.state().get('selection').first().toJSON();
                   console.log('action=cha_image&id='+id+'&path='+attachment.url);
              $.ajax({
                url:url,
                type:'post',
                data:'action=rep_image&id='+id+'&path='+attachment.url,
                success: function (result) {
                    location.reload();
                }
            });

            });
            // Open the uploader dialog
            mediaUploader.open();
        });
        $('.delete-gallery').click(function(e){
            if(confirm('do you really want to remove this gallery?')){
                var url=$(this).attr('action');
                var id=$(this).attr('id').replace('del_','');
                console.log(url,id);
                $.ajax({
                    url:url,
                    type:'post',
                    data:'action=del_gallery&id='+id,
                    success: function (result) {
                        location.reload();
                    }
                });
            }
        });
        $('.delete-image').click(function(e){
            if(confirm('do you really want to remove this image?')){
                var url=$(this).attr('action');
                var id=$(this).attr('id').replace('del_','');
                console.log(url,id);
                $.ajax({
                    url:url,
                    type:'post',
                    data:'action=del_image&id='+id,
                    success: function (result) {
                        location.reload();
                    }
                });
            }
        });
        $('.wgp-gallery-image').click(function(e){
            var ids = $(this).attr('id').split('&');
            ids[0] = ids[0].replace('i_','');
            ids[1] = ids[1].replace('g_','');

            open_wgp_modal();
            var t = galleries[ids[1]][ids[0]];
            $('#change_image_entry textarea[name="change_image_entry_caption"]').val(t.caption);
            $('#change_image_entry textarea[name="change_image_entry_sub_caption"]').val(t.sub_caption);
            $('#change_image_entry input[name="change_image_entry_url"]').val(t.url);
            $('#change_image_entry').on('submit',function(e){
                e.preventDefault();
                var url=$(this).closest('form').attr('action'),
                data=$(this).closest('form').serialize();
                var dataToSend = 'action=upd_image&g_id='+ids[1]+'&i_id='+ids[0]+'&'+data;
                $.ajax({
                    url:url,
                    type:'post',
                    data:dataToSend,
                    success:function(){
                        location.reload();
                    }
                });
            });
        });
        document.getElementsByClassName("wgp-close")[0].onclick = function() {
            modal.style.display = "none";
            $('#change_image_entry').unbind('submit');
        }
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
                 $('#change_image_entry').unbind('submit');
            }
        }
      
    });
}(jQuery))
