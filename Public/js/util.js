/**
 * 公共方法函数
 */

/**
 * 根据像素值，获取数值。
 * @param pixel
 * @returns {number}
 */

var util = {
    getNumPixel: function( pixel ) {
        if ( typeof pixel == 'string' ) {
            return Number( pixel.replace( 'px', '' ) );
        }
    },
    mouseCoords: function( e ) {
        var self = this;
        e = e || window.event;
        if ( e.pageX ) {
            return {
                x: e.pageX,
                y: e.pageY
            };
        }
        return {
            x: e.clientX + document.body.scrollLeft - document.body.clientLeft,
            y: e.clientY + document.body.scrollTop - document.body.clientTop
        }
    },
    mouseShowTip: function( e, con ) {
        var self = this;
        var tipWrap = $( '.floatTips' );
        var leftOffset = self.mouseCoords( e ).x + 15;
        var topOffset = self.mouseCoords( e ).y + 10;
        tipWrap.html( con );
        tipWrap.css( {
            'top': topOffset + 'px',
            'left': leftOffset + 'px'
        } ).fadeIn( 600 );
    },
    /**
     * 倒计时显示
     * @param end_time 倒计时 格式：2014/11/22 18:00:00 或者 秒数值
     * @param day_elem 显示天的Jquery对象
     * @param hour_elem 显示时的Jquery对象
     * @param minute_elem 显示分的Jquery对象
     * @param second_elem 显示秒的Jquery对象
     * @param now_time  指定当前时间 格式：2014/11/22 18:00:00 或者 秒数值
     * @param callback  倒计时完成后回调函数
     */
    timerCountDown: function( end_time, day_elem, hour_elem, minute_elem, second_elem, now_time, callback ) {
        if ( !Number( end_time ) ) {
            end_time = new Date( end_time ).getTime() / 1000;
        }
        if ( now_time ) {
            if ( !Number( now_time ) ) {
                now_time = new Date( now_time ).getTime() / 1000;
            }
        } else {
            now_time = new Date().getTime() / 1000;
        }
        var sys_second = end_time - now_time;
        var timer = setInterval( function() {
            if ( sys_second > 1 ) {
                sys_second -= 1;
                var day = Math.floor( ( sys_second / 3600 ) / 24 );
                var hour = Math.floor( ( sys_second / 3600 ) % 24 );
                var minute = Math.floor( ( sys_second / 60 ) % 60 );
                var second = Math.floor( sys_second % 60 );
                day_elem && day_elem.text( day ); //计算天
                hour_elem && hour_elem.text( hour < 10 ? "0" + hour : hour ); //计算小时
                minute_elem && minute_elem.text( minute < 10 ? "0" + minute : minute ); //计算分
                second_elem && second_elem.text( second < 10 ? "0" + second : second ); // 计算秒
            } else {
                clearInterval( timer );
                if ( callback.constructor == Function ) {
                    callback();
                }
            }
        }, 1000 )
    },
    topMenuToggle: function() {
        var topRight = $( '.topRight' );
        topRight.find( 'li' ).mouseenter( function() {
            $( ':animated' ).stop( true, true );
            $( this ).addClass( 'active' );
            $( this ).find( '.subMenu' ).slideDown();
        } ).mouseleave( function() {
            $( ':animated' ).stop( true, true );
            $( this ).find( '.subMenu' ).slideUp();
            $( this ).removeClass( 'active' );
        } );
    },
    //tab切换
    tabSwitch: function( abs ) {
        if ( abs && typeof abs == 'string' ) {
            var tabBtn = $( abs ).find( ".tabBtn" );
            var tabCon = $( abs ).find( ".tabCon" );
            tabCon.children().first().css( "display", 'block' );
            tabBtn.bind( "click", function() {
                var conFlag = $( this ).attr( 'data-flag' );
                $( ":animated" ).stop( true, true );
                $( conFlag ).siblings().hide();
                tabCon.find( conFlag ).fadeIn();
            } );
        }
    },
    //会员中心左侧菜单效果
    memberNav: function() {
        var $memberNav = $( '.memberNav' );
        $memberNav.find( '.dt' ).bind( 'click', function() {
            $( ':animated' ).stop( true, true );
            if ( !$( this ).hasClass( 'active' ) ) {
                $( this ).addClass( 'active' );
                $( this ).next( '.dd' ).slideUp( 600 );
            } else {
                $( this ).removeClass( 'active' );
                $( this ).next( '.dd' ).slideDown( 600 );
            }
        } );
        //简单判断菜单选中状态 根据实际URL规则调整
        var currentUrl = window.location.pathname;
        $memberNav.find( '.dd a' ).each( function() {
            if ( $( this ).attr( 'href' ).trim() == currentUrl ) {
                $( this ).addClass( 'active' );
            }
        } );
    },
    doAjax: function( options ) {
        if ( options && options.constructor === Object ) {
            $.ajax( options );
        }
    },
    // * @returns {XML|string}
    //   @适用于失焦事件 不适用于keyup事件
    toMoneyFormat: function( value ) {
        value += "";
        value = value.replace( /,/g, "" );
        var point = value.indexOf( "." );
        var len = value.length;
        if ( point < 0 ) {
            value += ".00";
        } else if ( point == len - 1 ) {
            value += "00";
        } else if ( point == len - 2 ) {
            value += "0";
        } else if ( point < len - 3 ) {
            value = value.substring( 0, point ) + ".00";
        }
        value = value.replace( /^(\d+)(\.\d+)$/, function( s, s1, s2 ) {
            return s1.replace( /\d{1,3}(?=(\d{3})+$)/g, "$&," ) + s2;
        } );
        return value;
    },

    parse_url: function( _url ) { //定义函数
        var pattern = /(\w+)=(\w+)/ig; //定义正则表达式
        var parames = {}; //定义数组
        _url.replace( pattern, function( a, b, c ) {
            parames[ b ] = c;
        } );
        return parames;
    },
    //产品页头部搜索下拉框
    //右侧浮动框
    floatBox: {
        backTop: function() {
            var self = this;
            var floatBox = $( '.floatBox' );
            var $body = ( window.opera ) ? ( document.compatMode == "CSS1Compat" ? $( 'html' ) : $( 'body' ) ) : $( 'html,body' );
            floatBox.find( '.floatTopBox' ).click( function() {
                $body.animate( {
                    'scrollTop': '0px'
                }, {
                    easing: 'swing',
                    duration: 1000
                } )
            } );

            function backTopStatus() {
                var scrollTopH = document.body.scrollTop || document.documentElement.scrollTop;
                if ( scrollTopH - 200 > 0 ) {
                    floatBox.find( '.floatTopBox' ).show();
                } else {
                    floatBox.find( '.floatTopBox' ).hide();
                }
            }
            $( window ).scroll( function() {
                backTopStatus();
            } );
            backTopStatus();
        }
    }


}

//    * 字符串函数扩展
//* @type {{toFloat: toFloat, toInt: toInt}}
//*/
String.prototype.toFloat = function() {
    return isNaN( parseFloat( this ) ) ? 0 : parseFloat( this );
};
String.prototype.toInt = function() {
    return isNaN( parseInt( this ) ) ? 0 : parseInt( this );
};
$( function() {
    util.floatBox.backTop();
} )