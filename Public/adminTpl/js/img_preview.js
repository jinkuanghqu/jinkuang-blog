function my_preview(el,previewer){
    var pv = document.getElementById(previewer);
    // IE5.5~9使用滤镜
    if (pv.filters && typeof(pv.filters.item) === 'function'){
        pv.filters.item("DXImageTransform.Microsoft.AlphaImageLoader").src = el.value;
    }
    else{
        // 其他浏览器和IE10+（不支持滤镜）则使用FileReader
        var fr = new FileReader();
        fr.onload = function(evt){
            var pvImg = new Image();
            pv.src = evt.target.result;
        };
        fr.readAsDataURL(el.files[0]);
    }
}

function trigger_click(eleId){
    var ele = document.getElementById(eleId);
    ele.click();
}