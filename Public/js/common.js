/* 文字上下滚动对象
 * @param options
 * @constructor
 */
function Roll( options ) {
    var defaultOptions = {
        parent: "#roll", //滚动父元素
        direction: "up", //滚动方向
        rollTime: 1000, //滚动时间
        spaceTime: 1000 //滚动间隔时间
    };
    this.options = jQuery.extend( {}, defaultOptions, options || {} );
    this.init();
    this.roll();
}
Roll.prototype = {
    init: function() {
        this.box = $( this.options.parent );
        this.clone = this.box.clone();
        this.boxH = this.box.height();
        this.boxW = this.box.width();
        this.childTotal = this.box.children().length;
        this.moveH = this.boxH / this.childTotal;
        this.bindEvent();
    },
    bindEvent: function() {
        var _this = this;
        this.box.children().bind( "mouseenter", function() {
            _this.timer && clearInterval( _this.timer );
        } ).bind( "mouseleave", function() {
            _this.options.direction == "up" && _this.rollUp();
            _this.options.direction == "down" && _this.rollDown();
        } );
        this.clone.children().bind( "mouseenter", function() {
            _this.timer && clearInterval( _this.timer );
        } ).bind( "mouseleave", function() {
            _this.options.direction == "up" && _this.rollUp();
            _this.options.direction == "down" && _this.rollDown();
        } );
    },
    roll: function() {
        if ( this.options.direction == "up" ) {
            this.box.after( this.clone );
            this.rollUp();
        } else if ( this.options.direction == "down" ) {
            this.clone.css( {
                "margin-top": "-" + this.boxH + "px"
            } );
            this.box.before( this.clone );
            this.rollDown();
        }
    },
    rollUp: function() {
        var _this = this;
        this.timer && clearInterval( this.timer );
        this.timer = setInterval( function() {
            var marginTop = util.getNumPixel( _this.box.css( "margin-top" ) ) - _this.moveH;
            _this.box.animate( {
                'marginTop': marginTop
            }, _this.options.rollTime, function() {
                if ( util.getNumPixel( _this.box.css( "marginTop" ) ) + _this.boxH < 1 ) {
                    _this.box.css( {
                        'marginTop': 0
                    } );
                }
            } );
        }, this.options.spaceTime + this.options.rollTime );
    },
    rollDown: function() {
        var _this = this;
        this.timer && clearInterval( this.timer );
        this.timer = setInterval( function() {
            var marginTop = util.getNumPixel( _this.clone.css( "margin-top" ) ) + _this.moveH;
            _this.clone.animate( {
                'marginTop': marginTop
            }, _this.options.rollTime, function() {
                if ( util.getNumPixel( _this.clone.css( "marginTop" ) ) > -1 ) {
                    _this.clone.css( {
                        'marginTop': "-" + _this.boxH + "px"
                    } );
                }
            } );
        }, this.options.spaceTime + this.options.rollTime );
    }
};

/**
 * 图片轮播对象
 * @param options
 * @constructor
 */
function Banner( options ) {
    this.leftBtn = options.leftBtn || null; //左按钮（JQuery元素对象）
    this.rightBtn = options.rightBtn || null; //右按钮（JQuery元素对象）
    this.bannerBox = options.bannerBox; //主体框（JQuery元素对象）不能为空
    this.banner = options.banner; //轮播对象（数组JQuery元素对象）不能为空
    this.indexBtn = options.indexBtn || null; //快捷按钮（数组JQuery元素对象）
    this.indexBtnCurrent = options.indexBtnCurrent || null; //当前快捷按钮样式
    this.autoPlay = options.autoPlay || !1; //是否自动播放
    this.autoPlayTime = options.autoPlayTime || 2000; //自动播放间隔时间
    this.scrollSpeed = options.scrollSpeed || 500; //轮播速度
    this.scrollAnimate = options.scrollAnimate || "opacity"; //轮播效果
    this.bgColorSwicth = options.bgColorSwicth || false; //是否启用背景色切换
    this.bgColorArr = options.bgColorArr; //banner背景色
    this.bgColorSpeed = options.bgColorSpeed || 500; //banner背景色切换时间
    this.init().bindEvent();
}
Banner.prototype = {
    init: function() {
        this.banner.not( ":first" ).css( {
            display: "none",
            opacity: 0,
            filter: "alpha(opacity=0)"
        } );
        if ( this.bgColorSwicth ) {
            this.bannerBox.css( 'background-color', this.bgColorArr[ 0 ] );
        }
        this.currentIndex = 0;
        this.targetIndex = 0;
        this.bannerNum = this.banner.length;
        this.autoPlay && this.auto();
        return this;
    },
    bindEvent: function() {
        var _this = this;
        this.leftBtn && this.leftBtn.bind( "click", function() {
            _this.targetIndex--;
            _this.targetIndex < 0 && ( _this.targetIndex = _this.bannerNum - 1 );
            _this.scroll();
        } );
        this.rightBtn && this.rightBtn.bind( "click", function() {
            _this.targetIndex++;
            _this.targetIndex >= _this.bannerNum && ( _this.targetIndex = 0 );
            _this.scroll();
        } );
        this.indexBtn && this.indexBtn.bind( "mouseover", function() {
            _this.targetIndex != $( this ).index() && ( _this.targetIndex = $( this ).index(), _this.scroll() );
        } );
        this.bannerBox.bind( "mouseenter", function() {
            _this.autoPlay && clearInterval( _this.timer );
            // _this.leftBtn.show();
            // _this.rightBtn.show();
        } ).bind( "mouseleave", function() {
            _this.autoPlay && _this.auto();
            //  _this.leftBtn.hide();
            //  _this.rightBtn.hide();
        } );
        return this;
    },
    scroll: function() {
        var _this = this;
        this.indexBtn.removeClass( this.indexBtnCurrent ).eq( this.targetIndex ).addClass( this.indexBtnCurrent );
        var current = this.banner.eq( this.currentIndex ).show();
        var target = this.banner.eq( this.targetIndex ).show();
        switch ( this.scrollAnimate ) {
            case "opacity":
                target.css( "opacity", 0 );
                if ( _this.bgColorSwicth ) {
                    _this.bannerBox.animate( {
                        backgroundColor: _this.bgColorArr[ _this.targetIndex ]
                    }, _this.bgColorSpeed );
                }
                current.stop().animate( {
                    opacity: 0
                }, _this.scrollSpeed, function() {
                    current.hide();
                } );
                target.stop().animate( {
                    opacity: 1
                }, _this.scrollSpeed );
        }
        this.currentIndex = this.targetIndex;
    },
    auto: function() {
        var _this = this;
        this.timer && clearInterval( this.timer );
        this.timer = setInterval( function() {
            _this.targetIndex++;
            _this.targetIndex >= _this.bannerNum && ( _this.targetIndex = 0 );
            _this.scroll();
        }, _this.autoPlayTime );
    }
};
//drag
function Drag( options ) {
    this.moveElement = options.moveElement; //移动元素
    this.focusElement = options.focusElement || this.moveElement; //触发移动函数
    this.moveArea = options.moveArea; //移动区域，以移动元素的左上角为准，数组【x轴最小值,x轴最大值,y轴最小值,y轴最大值】
    this.direction = options.direction || "all"; //移动方向
    this.beforeDrag = options.beforeDrag || null; //移动前触发函数
    this.afterDrag = options.afterDrag || null; //移动后触发函数
    this.draging = false; //是否在移动中
    this.dragParam = {
        diffX: '',
        diffY: '',
        moveX: '',
        moveY: ''
    }; //diffX|Y : 初始时，鼠标与被移动元素原点的距离,moveX|Y : 移动时，被移动元素定位位置 (新鼠标位置与diffX|Y的差值)
    this.init();
}
Drag.prototype = {
    init: function() {
        this.moveElement.css( {
            "position": "absolute"
        } );
        var _this = this;
        $( document ).unbind( "mousemove" ).mousemove( function( e ) {
            if ( _this.draging ) {
                if ( _this.beforeDrag ) {
                    _this.beforeDrag();
                }
                _this.dragParam.moveX = util.mouseCoords( e ).x - _this.dragParam.diffX;
                _this.dragParam.moveY = util.mouseCoords( e ).y - _this.dragParam.diffY;
                if ( _this.moveArea ) {
                    if ( _this.dragParam.moveX < _this.moveArea[ 0 ] ) {
                        _this.dragParam.moveX = _this.moveArea[ 0 ]
                    }
                    if ( _this.dragParam.moveX > _this.moveArea[ 1 ] ) {
                        _this.dragParam.moveX = _this.moveArea[ 1 ]
                    }
                    if ( _this.dragParam.moveY < _this.moveArea[ 2 ] ) {
                        _this.dragParam.moveY = _this.moveArea[ 2 ]
                    }
                    if ( _this.dragParam.moveY > _this.moveArea[ 3 ] ) {
                        _this.dragParam.moveY = _this.moveArea[ 3 ]
                    }
                }
                if ( _this.direction == 'all' ) {
                    _this.moveElement.css( {
                        'left': _this.dragParam.moveX + 'px',
                        'top': _this.dragParam.moveY + 'px'
                    } );
                } else if ( _this.direction == 'vertical' ) {
                    _this.moveElement.css( {
                        'top': _this.dragParam.moveY + 'px'
                    } );
                } else if ( _this.direction == 'horizontal' ) {
                    _this.moveElement.css( {
                        'left': _this.dragParam.moveX + 'px'
                    } );
                }
                if ( _this.afterDrag ) {
                    _this.afterDrag();
                }
            }
        } );
        this.focusElement.unbind( "mousedown mouseup" ).bind( "mousedown", function( e ) {
            _this.draging = true;
            $( this ).css( {
                'cursor': 'move'
            } );
            // 捕获事件。
            //            if ($(this).get(0).setCapture) {
            //                $(this).get(0).setCapture();
            //            }
            _this.dragParam.diffX = util.mouseCoords( e ).x - _this.moveElement.position().left;
            _this.dragParam.diffY = util.mouseCoords( e ).y - _this.moveElement.position().top;
        } ).bind( "mouseup", function( e ) {
            _this.draging = false;
            $( this ).css( {
                'cursor': 'default'
            } );
            //            if ($(this).get(0).releaseCapture) {
            //                $(this).get(0).releaseCapture();
            //            }
        } );
    }
};
//Dialog
function Dialog( element, options ) {
    this.element = element;
    this.options = $.extend( {}, Dialog.defaluts, options || {} );
    this.init().showDialog();
}
Dialog.prototype = {
    init: function() {
        var d = $( ".dialog" );
        if ( d.length > 0 && d.find( ".dialog_main" ).css( "-index" ) == this.options.zIndex ) {
            this.dialog = d;
        } else {
            this.dialog = $( '<div class="dialog none"><div class="dialog_bg none"></div><div class="dialog_main"><div class="dialog_box">' +
                '<div class="dialog_top"><span class="f16 black_text dialog_title none"></span><span class="close_dialog"></span></div>' +
                '<div class="dialog_body padding10"></div><div class="dialog_bottom none"></div>' +
                '</div></div></div>' );
        }
        this.modal = this.dialog.find( ".dialog_bg" );
        this.main = this.dialog.find( ".dialog_main" );
        this.box = this.dialog.find( ".dialog_box" );
        this.top = this.dialog.find( ".dialog_top" );
        this.body = this.dialog.find( ".dialog_body" );
        this.bottom = this.dialog.find( ".dialog_bottom" );
        this.body.html( this.element );
        this.showModal().showTop().showBox().showBottom().bindEvent();
        $( document ).find( "body" ).append( this.dialog );
        return this;
    },
    showDialog: function() {
        var _this = this;
        if ( this.options.beforeShow ) {
            this.options.beforeShow();
        }
        this.dialog.show();
        this.showDrag();
        var top = $( document ).scrollTop() + ( $( window ).height() - this.main.height() ) / 2;
        var left = $( document ).scrollLeft() + ( $( window ).width() - this.main.width() ) / 2;
        this.main.offset( {
            top: top > 0 ? top : 0,
            left: left > 0 ? left : 0
        } );
        this.main.fadeIn( 1000, function() {
            if ( _this.options.afterShow ) {
                _this.options.afterShow();
            }
        } );
        if ( this.options.changeByWindow && !this.bindResize ) {
            this.changeByWindow();
        }
        return this;
    },
    showDrag: function() {
        if ( this.options.dragAble ) {
            new Drag( {
                moveElement: this.main,
                focusElement: this.options.drag || this.top,
                beforeDrag: this.options.beforeDrag,
                afterDrag: this.options.afterDrag,
                moveArea: [ 0, $( document ).width() - this.main.width() - util.getNumPixel( this.main.css( "padding" ) ) * 2, 0, $( document ).height() - this.main
                    .height() - util.getNumPixel( this.main.css( "padding" ) ) * 2
                ]
            } );
        } else {
            var drag = this.options.drag || this.top;
            drag.unbind( "mousedown mouseup" );
            $( document ).unbind( "mousemove" );
        }
    },
    showModal: function() {
        if ( this.options.modal ) {
            this.modal.show();
            //            var bgColor = hexToRab(this.options.modalColor,this.options.modalTransparency,false);
            //            this.modal.css({"background-color":bgColor,"z-index":this.options.modalZIndex});
            var opacity = Number( this.options.modalTransparency ) * 100;
            this.modal.css( {
                "z-index": this.options.modalZIndex,
                "background-color": this.options.modalColor,
                "filter": "alpha(opacity=" + opacity + ")",
                "opacity": this.options.modalTransparency
            } );
        } else {
            this.modal.hide();
        }
        return this;
    },
    showBox: function() {
        if ( this.options.showBorder ) {
            var padding = this.options.borderWidth;
            //            var bgColor = hexToRab(this.options.borderColor,this.options.borderTransparency,false);
            //            this.main.css({"z-index":this.options.zIndex,"padding":padding,"background-color":bgColor});
            var opacity = Number( this.options.borderTransparency ) * 100;
            this.main.css( {
                "z-index": this.options.zIndex,
                "padding": padding,
                "background-color": this.options.borderColor
            } );
        } else {
            this.main.css( {
                "padding": 0,
                "z-index": this.options.zIndex
            } );
        }
        //        var bg = hexToRab(this.options.bgColor,this.options.transparency,false);
        //        this.box.css({"background-color":bg});
        var box_width = util.getNumPixel( this.options.width ) + 20 + "px";
        var opacity2 = Number( this.options.transparency ) * 100;
        this.box.css( {
            "background-color": this.options.bgColor,
            "filter": "alpha(opacity=" + opacity2 + ")",
            "opacity": this.options.transparency,
            "width": box_width
        } );
        this.body.css( {
            "width": this.options.width,
            "height": this.options.height
        } );
        return this;
    },
    showTop: function() {
        if ( this.options.showTitle ) {
            this.top.find( ".dialog_title" ).show().html( this.options.title );
        } else {
            this.top.find( ".dialog_title" ).hide().html( "" );
        }
        return this;
    },
    showBottom: function() {
        if ( this.options.showFooter ) {
            if ( this.options.showCloseBtn ) {
                if ( this.bottom.find( ".close_btn" ).length == 0 ) {
                    this.bottom.append( $( '<span class="bottom_btn close_btn">关闭</span>' ) );
                }
            } else {
                this.bottom.find( ".close_btn" ).remove();
            }
            if ( this.options.showCancelBtn ) {
                if ( this.bottom.find( ".cancel_btn" ).length == 0 ) {
                    this.bottom.append( $( '<span class="bottom_btn cancel_btn">取消</span>' ) );
                }
            } else {
                this.bottom.find( ".cancel_btn" ).remove();
            }
            if ( this.options.showOKBtn ) {
                if ( this.bottom.find( ".OK_btn" ).length == 0 ) {
                    this.bottom.append( $( '<span class="bottom_btn OK_btn">确认</span>' ) );
                }
            } else {
                this.bottom.find( ".OK_btn" ).remove();
            }
            this.bottom.show();
        } else {
            this.bottom.hide().html( "" );
        }
        return this;
    },
    bindEvent: function() {
        var _this = this;
        this.top.find( ".close_dialog" ).unbind( "click" ).click( function() {
            _this.closeDialog();
        } );
        this.bottom.find( ".close_btn" ).unbind( "click" ).click( function() {
            _this.closeDialog();
            if ( _this.options.closeCallBack ) {
                _this.options.closeCallBack();
            }
        } );
        this.bottom.find( ".cancel_btn" ).unbind( "click" ).click( function() {
            _this.closeDialog();
            if ( _this.options.cancelCallBack ) {
                _this.options.cancelCallBack();
            }
        } );
        this.bottom.find( ".OK_btn" ).unbind( "click" ).click( function() {
            _this.closeDialog();
            if ( _this.options.okCallBack() ) {
                _this.options.okCallBack();
            }
        } );
        return this;
    },
    closeDialog: function() {
        if ( this.options.beforeHide ) {
            this.options.beforeHide();
        }
        this.dialog.hide();
        //this.main.animate({opacity:0,filter:"alpha(opacity=0)"});
        $( window ).unbind( "resize" );
        if ( this.options.afterHide ) {
            this.options.afterHide();
        }
    },
    changeByWindow: function() {
        this.bindResize = true;
        var _this = this;
        $( window ).unbind( "resize" ).resize( function() {
            var top = $( document ).scrollTop() + ( $( window ).height() - _this.main.height() ) / 2;
            var left = $( document ).scrollLeft() + ( $( window ).width() - _this.main.width() ) / 2;
            _this.main.offset( {
                top: top > 0 ? top : 0,
                left: left > 0 ? left : 0
            } );
        } );
    }
};
$.extend( Dialog, {
    defaluts: {
        title: "提示信息", //弹出框标题
        showTitle: true, //是否显示标题
        dragAble: true, //是否可拖拽
        drag: null, //拖拽触发元素，默认弹出框头部
        zIndex: 9990, //弹出框显示层
        width: "300px", //弹出框宽度
        height: "200px", //弹出框高度（除去头部和尾部）
        transparency: 1, //透明度
        bgColor: "#fff", //弹出框背景颜色

        showBorder: true, //是否显示弹出框边框
        borderWidth: "0", //边框宽度
        borderColor: "#9C9C9C", //边框颜色
        borderTransparency: 0.28, //边框透明度

        modal: true, //是否显示画布，覆盖body
        modalTransparency: 0.4, //画布透明度
        modalColor: "#000", //画布颜色
        modalZIndex: 9900, //  画布显示层

        showFooter: false, //是否显示弹出框底部（底部有确认，取消，关闭按钮）
        showCloseBtn: false, //是否显示关闭按钮
        showCancelBtn: false, //是否显示取消按钮
        showOKBtn: false, //是否显示确定按钮
        closeCallBack: null, //关闭按钮触发事件
        cancelCallBack: null, //取消按钮触发事件
        okCallBack: null, //确认按钮触发事件

        //弹出框事件
        afterShow: null, //弹出框显示后
        beforeShow: null, //弹出框显示前
        afterHide: null, //弹出框消失后
        beforeHide: null, //弹出框消失前
        afterDrag: null, //弹出框拖动后
        beforeDrag: null, //弹出框拖动前

        changeByWindow: true, //位置随window变化而变化

        //后续扩展
        resizeAble: false, //弹出框是否允许缩放
        afterResize: null, //缩放后触发函数
        beforeResize: null //缩放前触发函数
    },
    /**
     * 提示信息，不带提示图标
     * {content:"提示框的提示信息",afterHide:提示框关闭后的回调函数,……}
     * 显示底部关闭按钮
     * @param options
     */
    info: function( options ) {
        var element = options.content;
        delete options.content;
        options = $.extend( {
            showFooter: true,
            showCloseBtn: true,
            title: "提示信息"
        }, options );
        new Dialog( element, options );
    },
    /**
     * 提示信息，带有感叹号图标,默认没有标题
     * {content:"提示框的提示信息",afterHide:提示框关闭后的回调函数,……}
     * 显示底部关闭按钮
     * @param options
     */
    alert: function( options ) {
        var content = options.content;
        delete options.content;
        var element = '<span class="tip_pic warn_tip"></span><p class="f14 tip_text">' + content + '</p>';
        options = $.extend( {
            showFooter: true,
            showCloseBtn: true,
            title: "提示信息"
        }, options );
        new Dialog( element, options );
    },
    /**
     * 提示信息，带有问号图标，提问
     * {content:"提示框的提示信息",afterHide:提示框关闭后的回调函数,okCallBack:确认按钮的回调函数,……}
     * 显示底部确认按钮，取消按钮
     * @param options
     */
    confirm: function( options ) {
        var content = options.content;
        delete options.content;
        var element = '<span class="tip_pic confirm_tip"></span><p class="f14 tip_text">' + content + '</p>';
        options = $.extend( {
            showFooter: true,
            showCancelBtn: true,
            showOKBtn: true,
            title: "提示信息"
        }, options );
        new Dialog( element, options );
    },
    /**
     * 错误提示信息，带有红叉图标
     * {content:"提示框的提示信息",afterHide:提示框关闭后的回调函数,closeCallBack:关闭按钮提示信息，……}
     * 显示底部关闭按钮
     * @param options
     */
    error: function( options ) {
        var content = options.content;
        delete options.content;
        var element = '<span class="tip_pic error_tip"></span><p  class="f14 tip_text">' + content + '</p>';
        options = $.extend( {
            showFooter: true,
            showCloseBtn: true,
            title: "提示信息"
        }, options );
        new Dialog( element, options );
    },
    /**
     * 成功提示框，带有对勾图标
     * {content:"提示框的提示信息",afterHide:提示框关闭后的回调函数,……}
     * @param options
     */
    success: function( options ) {
        var content = options.content;
        delete options.content;
        var element = '<span class="tip_pic right_tip"></span><p  class="f14 tip_text">' + content + '</p>';
        options = $.extend( {
            showFooter: true,
            showCloseBtn: true,
            title: "提示信息"
        }, options );
        new Dialog( element, options );
    }
} );

/**
 * 移动对象
 * @param options
 * @constructor
 */
function Drag( options ) {
    this.moveElement = options.moveElement; //移动元素
    this.focusElement = options.focusElement || this.moveElement; //触发移动函数
    this.moveArea = options.moveArea; //移动区域，以移动元素的左上角为准，数组【x轴最小值,x轴最大值,y轴最小值,y轴最大值】
    this.direction = options.direction || "all"; //移动方向
    this.beforeDrag = options.beforeDrag || null; //移动前触发函数
    this.afterDrag = options.afterDrag || null; //移动后触发函数
    this.draging = false; //是否在移动中
    this.dragParam = {
        diffX: '',
        diffY: '',
        moveX: '',
        moveY: ''
    }; //diffX|Y : 初始时，鼠标与被移动元素原点的距离,moveX|Y : 移动时，被移动元素定位位置 (新鼠标位置与diffX|Y的差值)

    this.init();
}
Drag.prototype = {
    init: function() {
        this.moveElement.css( {
            "position": "absolute"
        } );
        _this = this;
        $( document ).unbind( "mousemove" ).mousemove( function( e ) {
            if ( _this.draging ) {
                if ( _this.beforeDrag ) {
                    _this.beforeDrag();
                }
                _this.dragParam.moveX = util.mouseCoords( e ).x - _this.dragParam.diffX;
                _this.dragParam.moveY = util.mouseCoords( e ).y - _this.dragParam.diffY;
                if ( _this.moveArea ) {
                    if ( _this.dragParam.moveX < _this.moveArea[ 0 ] ) {
                        _this.dragParam.moveX = _this.moveArea[ 0 ]
                    }
                    if ( _this.dragParam.moveX > _this.moveArea[ 1 ] ) {
                        _this.dragParam.moveX = _this.moveArea[ 1 ]
                    }
                    if ( _this.dragParam.moveY < _this.moveArea[ 2 ] ) {
                        _this.dragParam.moveY = _this.moveArea[ 2 ]
                    }
                    if ( _this.dragParam.moveY > _this.moveArea[ 3 ] ) {
                        _this.dragParam.moveY = _this.moveArea[ 3 ]
                    }
                }
                if ( _this.direction == 'all' ) {
                    _this.moveElement.css( {
                        'left': _this.dragParam.moveX + 'px',
                        'top': _this.dragParam.moveY + 'px'
                    } );
                } else if ( _this.direction == 'vertical' ) {
                    _this.moveElement.css( {
                        'top': _this.dragParam.moveY + 'px'
                    } );
                } else if ( _this.direction == 'horizontal' ) {
                    _this.moveElement.css( {
                        'left': _this.dragParam.moveX + 'px'
                    } );
                }
                if ( _this.afterDrag ) {
                    _this.afterDrag();
                }
            }
        } );
        this.focusElement.unbind( "mousedown mouseup" ).bind( "mousedown", function( e ) {
            _this.draging = true;
            $( this ).css( {
                'cursor': 'move'
            } );
            // 捕获事件。
            //            if ($(this).get(0).setCapture) {
            //                $(this).get(0).setCapture();
            //            }
            _this.dragParam.diffX = util.mouseCoords( e ).x - _this.moveElement.position().left;
            _this.dragParam.diffY = util.mouseCoords( e ).y - _this.moveElement.position().top;
        } ).bind( "mouseup", function( e ) {
            _this.draging = false;
            $( this ).css( {
                'cursor': 'default'
            } );
            //            if ($(this).get(0).releaseCapture) {
            //                $(this).get(0).releaseCapture();
            //            }
        } );
    }
};

/**
 * tips插件，显示提示
 * @param ele
 * @param options
 * @constructor
 */
function Tip( ele, options ) {
    var defaults = {
        position: "right",
        eventType: "hover",
        trigger: "", //触发提示元素选择字符串，为ele的子元素
        showEle: "", //显示地方的依赖元素，为trigger获取元素的父元素
        content: "" //提示内容，可为空，默认是提示元素中的tips-content属性
    };
    this.ele = $( ele );
    this.options = $.extend( defaults, options );
    this.trigger = this.options.trigger ? this.ele.find( this.options.trigger ) : this.ele;
    this.flag = 1;
    this.init();
}

Tip.prototype = {
    init: function() {
        var wrap = $( ".tips_" + this.options.position );
        if ( wrap.length > 0 ) {
            this.wrap = wrap;
        } else {
            this.wrap = $( '<div class="tips tips_"' + this.options[ 'position' ] + '><div class="tips_wrap"><div class="tips_arrow tips_arrow_' + this.options[ 'position' ] +
                '"><em></em><span></span><div class="tips_content"></div></div></div></div>' );
            $( "body" ).append( this.wrap );
        }
        this.bindEvent();
        return this;
    },
    bindEvent: function() {
        var _this = this;
        if ( _this.options.eventType == "hover" ) {
            this.trigger.hover( function() {
                _this.showTips( $( this ) );
            }, function() {
                _this.hideTips();
            } );
        } else if ( _this.options.eventType == "focus" ) {
            this.trigger.focus( function() {
                _this.showTips( $( this ) );
            } );
            this.trigger.blur( function() {
                _this.hideTips();
            } );
        }
    },
    hideTips: function() {
        this.wrap.stop( true, true ).fadeOut();
    },
    showTips: function( ele ) {
        var showEle = ele;
        if ( this.options.showEle && ele.parents( this.options.showEle ).length > 0 ) {
            showEle = ele.parents( this.options.showEle );
        }
        var width = parseInt( showEle.outerWidth() ); //被提示元素的宽度（包括内边距和边框）
        var height = parseInt( showEle.outerHeight() ); //被提示元素的高度（包括内边距和边框）
        var top = parseInt( showEle.offset().top ); //被提示元素top
        var left = parseInt( showEle.offset().left ); //被提示元素left
        var content = this.options.content || ele.attr( 'tips_content' ); //提示内容
        var tipsWidth = 0; //提示框宽度
        var tipsHeight = 0;
        var tipsTop = 0; //提示框top
        var tipsLeft = 0; //提示框left
        if ( !content ) {
            return false;
        }
        this.wrap.find( ".tips_content" ).html( content );
        //设置提示框宽度
        if ( this.wrap.outerWidth() > 400 ) {
            this.wrap.css( {
                width: "350px"
            } );
        }
        tipsWidth = this.wrap.outerWidth();
        tipsHeight = this.wrap.outerHeight();
        switch ( this.options.position ) {
            case "top":
                tipsTop = top - tipsHeight - 16;
                tipsLeft = left + width / 2 - tipsWidth / 2;
                this.wrap.find( ".tips_arrow em,.tips_arrow span" ).css( {
                    left: ( tipsWidth / 2 - 8 ) + "px"
                } );
                break;
            case "bottom":
                tipsTop = top + height + 16;
                tipsLeft = left + width / 2 - tipsWidth / 2;
                this.wrap.find( ".tips_arrow em,.tips_arrow span" ).css( {
                    left: ( tipsWidth / 2 - 8 ) + "px"
                } );
                break;
            case "right":
                tipsTop = top + height / 2 - tipsHeight / 2;
                tipsLeft = left + width + 16;
                this.wrap.find( ".tips_arrow em,.tips_arrow span" ).css( {
                    top: ( tipsHeight / 2 - 8 ) + "px"
                } );
                break;
            case "left":
                tipsTop = top + height / 2 - tipsHeight / 2;
                tipsLeft = left - tipsWidth - 16;
                this.wrap.find( ".tips_arrow em,.tips_arrow span" ).css( {
                    top: ( tipsHeight / 2 - 8 ) + "px"
                } );
                break;
        }
        this.wrap.css( {
            top: tipsTop + 'px',
            left: tipsLeft + 'px'
        } ).stop( true, true ).fadeIn();
    }
};
/**
 * 校验对象
 * @param ele
 * @param options
 * {
 *  bind : "blur"   //校验触发方式。为show的时候是及时校验。blur校验将忽略required。
 *  valid : {       //校验方法，可自定义函数。
 *      required : "……不能为空" //校验规则：错误提示信息，可多个。
 *      }
 *  err : $(".error") //错误显示框
 *  border : "" 颜色边框对象，默认为本身，可以父元素。
 * }
 * @constructor
 */
function Validator( ele, options ) {
    this.elem = $( ele );
    this.validResult = true;
    this.opt = $.extend( {}, Validator.defaults, options );
    var _this = this;
    this.err = this.opt.err;
    this.border = this.opt.border;
    if ( !this.err ) {
        this.err = $( "." + this.elem.attr( "id" ) + "_tips" );
    }
    if ( this.opt.bind == "show" ) {
        _this.verify();
    } else {
        this.elem.bind( this.opt.bind, function() {
            _this.verify();
        } );
    }
}



function sinob2bAlert( msg, options ) {
    var iconMap = {
        'ok': 1,
        'error': 2,
        'query': 3,
        'fail': 5,
        'success': 6,
        'info': 7,
    };
    iconCode = 7;
    if(typeof options == "string"){
        iconCode = iconMap[options];
    }else if(typeof options == "object"){
        iconCode = iconMap[options['status']];
    }
    if(iconCode == undefined) iconCode = 7;

    layer.alert( msg, {
        icon: iconCode,
        title: 'MESSAGE',
        btn: [],
        skin: 'layer-ext-moon' //该皮肤由layer.seaning.com友情扩展。关于皮肤的扩展规则，去这里查阅
    } );
}

// 收藏商品
function collect( goods_id,obj) {
    $.post( '/Goods/addToWish', {
        'goods_id': goods_id
    }, function( result ) {
        var data = JSON.parse( result );
        if(data['status'] == 401) {
            askLogin();
        } else {
            var statusCode = (data['status'] == 0 ? 'success' : (data['status'] == 1 ? 'fail' : 'info'));
            sinob2bAlert( data[ 'info' ] , statusCode);
            if(data['status'] == 0){
                $(obj).prev().remove();
            }
        }
    });
}

// 收藏店铺
function collectStore( store_id,obj) {
    $.post( '/Store/addToWish', {
        'store_id': store_id
    }, function( result ) {
        var data = JSON.parse( result );
        if(data['status'] == 401) {
            askLogin();
        } else {
            var statusCode = (data['status'] == 0 ? 'success' : (data['status'] == 1 ? 'fail' : 'info'));
            sinob2bAlert( data[ 'info' ] , statusCode);
            if(data['status'] == 0){
                $(obj).prev().remove();
            }
        }
    });
}

// 询问是否登录
function askLogin(loginUrl) {
    layer.confirm('You need to login to do this.<br/>Would you like to login now?', {
          title: 'MESSAGE',
          btn: ['Yes ','No, later'] 
        }, function(index){
            if(typeof loginUrl == "undefined") {
                loginUrl = "/Member/login" + '?return_url=' + location.href;
            }
            location.href = loginUrl;
        }, function(index){
            layer.close(index);
    });
}

function sinob2bParseFloat( str ) {
    if (str !== undefined) {
        return window.parseFloat( str.replace( ',', '' ) );
    } else {
        //return 0.0;
    }
}

function sinob2bMoneyFormat( s, n ) {
    n = n > 0 && n <= 20 ? n : 2;
    s = parseFloat( ( s + "" ).replace( /[^\d\.-]/g, "" ) ).toFixed( n ) + "";
    var l = s.split( "." )[ 0 ].split( "" ).reverse(),
        r = s.split( "." )[ 1 ];
    t = "";
    for ( i = 0; i < l.length; i++ ) {
        t += l[ i ] + ( ( i + 1 ) % 3 == 0 && ( i + 1 ) != l.length ? "," : "" );
    }
    return t.split( "" ).reverse().join( "" ) + "." + r;
}

function sinob2bAjaxReturn (Data) {
     if(typeof Data.info != 'undefined'){
        sinob2bAlert(Data.info);
     }
     console.log(Data);
}


// $(function(){
    
//     $("#formData").validator({
//         messages:{
//             required : "Not null",
//         }
//     })    
    
// })
    
   