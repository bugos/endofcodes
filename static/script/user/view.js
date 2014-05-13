var UserView = {
    showUploadedImage: function( source ) {
        $( "#userImage" ).attr( "src", source );
    },
    createImageError: function() {
        $( '#image-form' ).prepend( "<div class='alert alert-danger'>This isn't an image</div>" )
    },
    removeImageError: function() {
        $( '#image-form .alert.alert-danger' ).remove();
    },
    toggleSubmit: function() {
        $( "#imageSubmit" ).toggle();
        $( "#uploading" ).toggle();
    },
    ready: function() {
        var height, width;
        var $avatar = $( '.avatar' );
        var $image = $( '.avatar img' );
        var imgWidth = $image.width();
        var imgHeight = $image.height();

        height = width = 168;

        if ( imgWidth > imgHeight ) {
            $image.height( height );
            $image.css( 'top', 0 );
            $image.css( 'left', -Math.floor( ( $image.width() - width ) / 2 ) );
        }
        else {
            $image.width( width );
            $image.css( 'left', 0 );
            $image.css( 'top', -Math.floor( ( $image.height() - height ) / 2 ) );
        }
        $( '#unfollow' ).click( function() {
            $( '#unfollow-form' ).submit();
            return false;
        } );
        $( '#follow' ).click( function() {
            $( '#follow-form' ).submit();
            return false;
        } );
        $( "#image-form" ).submit( function() {
            var image = document.getElementById( "image" ).files[ 0 ];
            var token = $( "input[type=hidden]" ).val();
            var formData = new FormData();

            UserView.removeImageError();

            if ( !image ) {
                UserView.createImageError();
                return false;
            }

            UserView.toggleSubmit();

            formData.append( "image", image );
            formData.append( "token", token );

            $.ajax( {
                url: "image/create",
                type: "POST",
                data: formData,
                cache: false,
                dataType: "json",
                processData: false,
                contentType: false,
                success: function( res ) {
                    var reader = new FileReader();

                    reader.onloadend = function ( e ) {
                        UserView.showUploadedImage( e.target.result );
                    }
                    reader.readAsDataURL( image );

                    UserView.toggleSubmit();
                },
                error: function( jqXHR, textStatus, errorThrown ) {
                    UserView.createImageError();
                    $( "#imageSubmit" ).show();
                    $( "#uploading" ).hide();
                }
            } );

            return false;
        } );
    }
}
$( document ).ready( UserView.ready );
