/*!
 * sinob2b前台js
 * @author luffy<luffyzhao@vip.126.com>
 * @dateTime 2016-03-04T16:29:41+0800
 * @param    {[type]}                 ) {}          [description]
 * @return   {[type]}                   [description]
 */
$( function() {

    var ajaxLayerLoadIndex;
    $( document ).ajaxStart( function( argument ) {
        ajaxLayerLoadIndex = layer.load( 0, {
            shade: false
        } );
    } );

    $( document ).ajaxComplete( function( argument ) {
        layer.close( ajaxLayerLoadIndex );
    } );


    $( 'img' ).each( function( argument ) {
        var src = $( this ).attr( 'src' );
        var parames = util.parse_url( src );
        // console.log( parames.width );
        if ( typeof parames.width == 'undefined' || typeof parames.height == 'undefined' ) {
            // continue;
        } else {
            $( this ).css( {
                width: parames.width,
                height: parames.height
            } );
        }

    } );

} );