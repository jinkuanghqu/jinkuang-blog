var FILE_UPDATE_LOCK = false;

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

    $( '#submit' ).on( 'click',
        function( event ) {
            AjaxSubmit();
        } );

    //
    $( '.father' ).on( 'ifChecked',
        function( event ) {
            $( this ).parent().parent().parent().parent().parent().find( ".children" ).iCheck( 'check' );
        } );
    $( '.father' ).on( 'ifUnchecked',
        function( event ) {
            $( this ).parent().parent().parent().parent().parent().find( ".children" ).iCheck( 'uncheck' );
        } );
    $( '.children' ).on( 'ifChecked',
        function( event ) {
            $( this ).parent().parent().parent().parent().parent().find( ".father" ).iCheck( 'check' );
        } );

    $( '.btn-input-file' ).change( function( e ) {
        if ( FILE_UPDATE_LOCK ) {
            layerError( "上传控件中忙" );
            return false;
        }
        var obj = this;
        var file = this.files[ 0 ];
        var ext = $( obj ).data( 'ext' );
        if ( typeof ext != 'undefined' && !eval( ext ).test( file.name ) ) {
            layerError( "文件格式不正确！" );
            return false;
        }
        if ( file.size > 524288 ) {
            layerError( "文件大小不能超过 512 KB" );
            return false;
        }
        FILE_UPDATE_LOCK = true;
        var reader = new FileReader();
        reader.readAsDataURL( file );
        reader.onload = function( e ) {
            $( obj ).parent().parent().find( '.input-file-text' ).val( this.result + "#" + file.name );
            FILE_UPDATE_LOCK = false;
            delete this;
        }
        delete reader;
    } );

    $( "a[data-toggle='collapse']" ).bind( 'click',
        function( e ) {
            var body = $( this ).parent().parent().parent().find( '.collapse' );
            body.toggleClass( 'in' );
            if ( body.is( ':visible' ) ) {
                $( this ).find( 'i' ).removeClass( 'icon-up' );
                $( this ).find( 'i' ).addClass( 'icon-down' );
            } else {
                $( this ).find( 'i' ).removeClass( 'icon-down' );
                $( this ).find( 'i' ).addClass( 'icon-up' );
            }
        } );

    $( ".autoBootstrapValidator" ).validation( function( obj, params ) {
        // ajax验证
        var ajaxUrl = $( obj ).data( 'ajax' );
        if ( typeof ajaxUrl != 'undefined' ) {
            $.post( ajaxUrl, {
                    name: $( obj ).val()
                },
                function( data ) {
                    if ( data.status == 1 ) {
                        params.err = true;
                        params.msg = ( data.msg == 'undefined' ) ? '已存在' : data.msg;
                    }
                }, 'json' );
        }
        // 密码确认
        var confirm = $( obj ).data( 'confirm' );
        // confirm == data-confirm="#input-password"
        if ( typeof confirm != 'undefined' ) {
            if ( $( confirm ).val() != $( obj ).val() ) {
                params.err = true;
                params.msg = '二次输入不正确！';
            }
        }
    }, {
        icon: true,
        reqmark: false
    } );

    $( ".autoBootstrapValidator button[type='submit']" ).on( 'click',
        function( event ) {
            var formObj = $( ".autoBootstrapValidator" );
            // 2.最后要调用 valid()方法。
            if ( formObj.valid( this, "error!" ) == false ) {
                return false;
            }
            if ( typeof formObj.data( 'ajax' ) != 'undefined' ) {
                AjaxSubmit( formObj.attr( 'action' ), formObj );
                return false;
            }
        } );

    $( ".checkbox-del" ).bind( 'submit', function( argument ) {
        var obj = $( this );
        var url = obj.attr( 'action' );
        parent.layer.confirm( '您确定要删除所选数据吗？', {
            btn: [ '确定', '取消' ],
            skin: 'layer-ext-moon'
        }, function( index ) {
            AjaxSubmit( url, obj );
            parent.layer.close( index );
        }, function() {

        } );
        return false;
    } );

    $( ".checkbox-review" ).bind( 'submit', function( argument ) {
        var obj = $( this );
        var url = obj.attr( 'action' );
        parent.layer.confirm( '您确定审核通过吗？', {
            btn: [ '确定', '取消' ],
            skin: 'layer-ext-moon'
        }, function( index ) {
            AjaxSubmit( url, obj );
            parent.layer.close( index );
        }, function() {

        } );
        return false;
    } );

    $( ".ajax-destroy" ).bind( 'click', function( argument ) {
        var url = $( this ).data( 'ajax' );
        parent.layer.confirm( '您确定要删除这条数据吗？', {
            btn: [ '确定', '取消' ],
            skin: 'layer-ext-moon'
        }, function( index ) {
            $.get( url, {}, AjaxRequest, 'json' );
            parent.layer.close( index );
        }, function() {

        } );
        return false;
    } );

    $( ".ajax-destroy-all" ).bind( 'click', function( argument ) {
        var url = $( this ).data( 'url' );
        var domClass = $( this ).data( 'class' );
        var domChecked = $("." + domClass + ":checked");
        if (domChecked.length) {
            var idsArr = new Array();
            $.each(domChecked, function(){
                idsArr.push($(this).val());
            });
            parent.layer.confirm( '您确定要删除这些数据吗？', {
                btn: [ '确定', '取消' ],
                skin: 'layer-ext-moon'
            }, function( index ) {
                $.get( url, {ids:idsArr}, AjaxRequest, 'json' );
                parent.layer.close( index );
            }, function() {

            } );
        } else {
            parent.layer.alert('请至少选择一条记录', {icon: 5});
        }
        return false;
    } );

    $( '.ajax-confirm' ).bind( 'click', function( argument ) {
        var url = $( this ).data( 'ajax' );
        parent.layer.confirm( '确定操作这条数据吗？', {
            btn: [ '确定', '取消' ],
            skin: 'layer-ext-moon'
        }, function( index ) {
            $.get( url, {}, AjaxRequest, 'json' );
            parent.layer.close( index );
        }, function() {

        } );
        return false;
    } );

    $( '.input-ajax-submit' ).bind( 'blur', function( argument ) {
        var url = $( this ).data( 'ajax' );

        $.post( url, {
            'name': $( this ).val()
        }, AjaxRequest, 'json' );

    } );


    $( ".input-file-update" ).fileinput( {
        language: 'zh',
        overwriteInitial: true,
        maxFileSize: 1500,
        showClose: false,
        showCaption: false,
        browseLabel: '',
        removeLabel: '',
        browseIcon: '<i class="glyphicon glyphicon-folder-open"></i>',
        removeIcon: '<i class="glyphicon glyphicon-remove"></i>',
        layoutTemplates: {
            main2: '{preview} {remove} {browse}'
        },
        allowedFileExtensions: [ "jpg", "png", "gif" ]
    } );

    $( 'input.input-icheck' ).iCheck( {
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue'
    } );

    $( '.nav-tabs a' ).click( function( e ) {
        e.preventDefault()
        $( this ).tab( 'show' )
    } )

    $( '[data-toggle="tooltip"]' ).tooltip();

    $('.check-all').click(function(){
        $('.ids').prop("checked", this.checked);
    })


} );
/*
 *
 * @function: 判断浏览器类型是否是Safari、Firefox、ie、chrome浏览器
 * @return: true或false
 *
 */
function isSafari() {
    var userAgent = navigator.userAgent.toLowerCase();
    if ( userAgent.indexOf( "safari" ) > -1 && userAgent.indexOf( "chrome" ) < 0 ) {
        return true;
    }
    return false;
}

function isChrome() {
    if ( navigator.userAgent.indexOf( "Chrome" ) !== -1 ) {
        return true;
    }
    return false;
}

function isFirefox() {
    if ( navigator.userAgent.indexOf( "Firefox" ) > 0 ) {
        return true;
    }
    return false;
}

function isMSIE9() {
    if ( navigator.appName == "Microsoft Internet Explorer" && navigator.appVersion.split( ";" )[ 1 ].replace( /[ ]/g, "" ) == "MSIE9.0" ) {
        return true;
    }
    return false;
}

function isMSIE8() {
    if ( navigator.appName == "Microsoft Internet Explorer" && navigator.appVersion.split( ";" )[ 1 ].replace( /[ ]/g, "" ) == "MSIE8.0" ) {
        return true;
    }
    return false;
}

function isMSIE7() {
    if ( navigator.appName == "Microsoft Internet Explorer" && navigator.appVersion.split( ";" )[ 1 ].replace( /[ ]/g, "" ) == "MSIE7.0" ) {
        return true;
    }
    return false;
}

function percentage( num, total ) {
    return ( Math.round( num / total * 10000 ) / 100.00 + "%" ); // 小数点后两位百分比
}

function checkAll( name ) {
    var el = document.getElementsByTagName( 'input' );
    var len = el.length;
    for ( var i = 0; i < len; i++ ) {
        if ( ( el[ i ].type == "checkbox" ) && ( el[ i ].name == name ) ) {
            el[ i ].checked = true;
        }
    }
    getCheckAll( $( "#tab tbody input[type='checkbox']:checked" ) );
    $( ".checkbox-post" ).prop( "checked", true );
    $( ".allbutton" ).removeAttr( "disabled" );
}

function clearAll( name ) {
    var el = document.getElementsByTagName( 'input' );
    var len = el.length;
    for ( var i = 0; i < len; i++ ) {
        if ( ( el[ i ].type == "checkbox" ) && ( el[ i ].name == name ) ) {
            el[ i ].checked = false;
        }
    }
    $( ".checkbox-post" ).removeProp( 'checked' ).val( "" );
    $( ".allbutton" ).attr( "disabled", "disabled" );
}

function getCheckAll( elm ) {
    var checkedVal = "";
    elm.each( function( index, el ) {
        checkedVal += $( this ).val() + ",";
    } );
    $( ".checkbox-post" ).val( checkedVal );
}

/**
 * [newRemind 标题闪烁]
 * @param  {[type]} pageTitle  [原页面的标题]
 * @param  {[type]} showRemind [闪烁时显示的东东：如【新提醒】]
 * @param  {[type]} hideRemind [闪烁时隐藏的东东：如【　　　】]
 * @param  {[type]} time       [闪烁间隔的时间]
 */
function newRemind( pageTitle, showRemind, hideRemind, time ) {
    if ( newRemindFlag == 1 ) {
        document.title = showRemind + pageTitle;
        newRemindFlag = 2;
    } else {
        document.title = hideRemind + pageTitle;
        newRemindFlag = 1;
    }

    setTimeout( "newRemind('" + pageTitle + "','" + showRemind + "','" + hideRemind + "'," + time + ")", time );
}

function layerSuccess( info ) {
    parent.layer.open( {
        type: 1,
        title: false,
        closeBtn: 0,
        //不显示关闭按钮
        scrollbar: false,
        shade: 0,
        time: 2000,
        //2秒后自动关闭
        offset: '55px',
        shift: 5,
        content: '<div class="HTooltip bounceInDown animated" style="width:350px;padding:7px;text-align:center;position:fixed;right:7px;background-color:#5cb85c;color:#fff;z-index:100001;box-shadow:1px 1px 5px #333;-webkit-box-shadow:1px 1px 5px #333;font-size:14px;">' +
            info + '</div>',
        //iframe的url，no代表不显示滚动条
    } );
}

function layerError( info ) {
    parent.layer.open( {
        type: 1,
        title: false,
        closeBtn: 0,
        //不显示关闭按钮
        scrollbar: false,
        shade: 0,
        time: 2000,
        //2秒后自动关闭
        offset: [ '55px', '100%' ],
        shift: 6,
        content: '<div class="HTooltip bounceInDown animated" style="width:350px;padding:7px;text-align:center;position:fixed;right:7px;background-color:#D84C31;color:#fff;z-index:100001;box-shadow:1px 1px 5px #333;-webkit-box-shadow:1px 1px 5px #333;font-size:14px;">' +
            info + '</div>',
        //iframe的url，no代表不显示滚动条
    } );
}

function AjaxRequest( data ) {
    //                var data = eval("(" + data + ")");
    if ( data.status == 1 ) {
        layerSuccess( data.info );
    } else {
        layerError( data.info );
        return false;
        //my_error(data.info);
    }

    if ( data.url && data.url != '' ) {
        setTimeout( function() {
                location.href = data.url;
            },
            2000 );
    }
    if ( typeof data.url == 'undefined' || data.url == '' && data.status == 1 ) {
        setTimeout( function() {
                window.location.reload();
            },
            1000 );
    }
}
/**
 * 通用AJAX提交
 * @param  {string} url    表单提交地址
 * @param  {string} formObj    待提交的表单对象或ID
 */
function AjaxSubmit( url, formObj ) {
    if ( !formObj || formObj == '' ) {
        var formObj = $( '#formData' );
    }
    //console.log(formObj);
    if ( !url || url == '' ) {
        var url = document.URL;
    }
    //console.log(url);
    $( formObj ).ajaxSubmit( {
        url: url,
        type: "POST",
        success: AjaxRequest
    } );
    return false;
}
